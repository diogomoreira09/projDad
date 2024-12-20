<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showMe(Request $request)
    {
        return new UserResource($request->user());
    }

    public function profile(Request $request) {
        return response()->json($request->user());
        /* $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nickname' => $user->nickname,
            'type' => $user->type,
            'photo_filename' => $user->photo_filename,
            'brain_coins_balance' => $user->brain_coins_balance,
        ]); */

    }

    public function update(Request $request) {

        // Obtenha o usuário autenticado
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $request->user()->id,
            'nickname' => 'string|max:50|unique:users,nickname,' . $request->user()->id,
            'password' => 'string|min:8|nullable',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('profile_photos', 'public');
            $user->photo_filename = $photoPath;
        }

        $user->update(array_filter($validated, fn($value) => !is_null($value)));

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function destroy(Request $request) {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully'], 204);
    }
}
