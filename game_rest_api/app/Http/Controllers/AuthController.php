<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validatedData = $request->validated();

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->nickname = $validatedData['nickname'];
        $user->blocked = false;
        $user->brain_coins_balance = $validatedData['type'] === 'A' ? 0 : 10;
        $user->password = Hash::make($request->password);
        $user->type = $validatedData['type'];

        if ($user->save()) {

            if ($request->photo_filename) {
            $base64Image = $request->photo_filename;

            $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
            $imageData = base64_decode($base64Image);

            $extension = 'png';
            if (str_starts_with($request->photo_filename, 'data:image/jpeg;base64,')) {
                 $extension = 'jpg';
            }

            $filename = $user->id . '_' . uniqid() . '.' . $extension;
            Storage::disk('public')->put('photos/' . $filename, $imageData);

            $user->photo_filename = $filename;
            $user->save();
            }

            return new UserResource($user);
        }

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    
    private function purgeExpiredTokens()
    {
        // Only deletes if token expired 2 hours ago
        $dateTimetoPurge = now()->subHours(2);
        DB::table('personal_access_tokens')->where('expires_at', '<', $dateTimetoPurge)->delete();
    }

    private function revokeCurrentToken(User $user)
    {
        $currentTokenId = $user->currentAccessToken()->id;
        $user->tokens()->where('id', $currentTokenId)->delete();
    }

    public function login(LoginRequest $request)
    {
        $this->purgeExpiredTokens();
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token = $request->user()->createToken('authToken', ['*'], now()->addHours(2))->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        $this->purgeExpiredTokens();
        $this->revokeCurrentToken($request->user());
        return response()->json(null, 204);
    }

    public function refreshToken(Request $request)
    {
        // Revokes current token and creates a new token
        $this->purgeExpiredTokens();
        $this->revokeCurrentToken($request->user());
        $token = $request->user()->createToken('authToken', ['*'], now()->addHours(2))->plainTextToken;
        return response()->json(['token' => $token]);
    }

}
