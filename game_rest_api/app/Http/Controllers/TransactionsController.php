<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionsController extends Controller
{
    /**
     * Retrieve authenticated user's transactions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTransactions()
    {
        try {
            $transactions = Transaction::where('user_id', Auth::id())
                ->orderBy('transaction_datetime')
                ->paginate(10);

            return response()->json($transactions);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Transaction Failed.'], 500);
        }
    }
    public function purchaseCoins(Request $request)
    {
        $request->validate([
            'type' => 'required|in:MBWAY,PAYPAL,IBAN,MB,VISA',
            'reference' => 'required|string',
            'value' => 'required|integer|min:1|max:99',
        ]);

        $user = auth()->user();

        // Validate reference based on type
        $this->validateReference($request->type, $request->reference);

        // Simulate external API call
        $response = Http::post('https://dad-202425-payments-api.vercel.app/api/debit', [
            'type' => $request->type,
            'reference' => $request->reference,
            'value' => $request->value,
        ]);

        if ($response->status() !== 201) {
            return response()->json(['error' => 'Payment failed'], 422);
        }

        // Calculate Brain Coins (e.g., 1€ = 10 coins)
        $brainCoins = $request->value * 10;

        // Update user's brain coin balance
        $user->brain_coins_balance += $brainCoins;
        $user->save();

        // Log transaction
        Transaction::create([
            'type' => 'P',
            'transaction_datetime' => now(),
            'user_id' => $user->id,
            'euros' => $request->value,
            'payment_type' => $request->type,
            'payment_reference' => $request->reference,
            'brain_coins' => $brainCoins,
        ]);

        return response()->json(['message' => 'Purchase successful', 'brain_coins' => $brainCoins]);
    }

    private function validateReference($type, $reference)
    {
        switch ($type) {
            case 'MBWAY':
                if (!preg_match('/^9\d{8}$/', $reference)) {
                    throw new \Exception('Invalid MBWAY reference');
                }
                break;
            case 'PAYPAL':
                if (!filter_var($reference, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('Invalid PayPal reference');
                }
                break;
            case 'IBAN':
                if (!preg_match('/^[A-Z]{2}\d{23}$/', $reference)) {
                    throw new \Exception('Invalid IBAN reference');
                }
                break;
            case 'MB':
                if (!preg_match('/^\d{5}-\d{9}$/', $reference)) {
                    throw new \Exception('Invalid MB reference');
                }
                break;
            case 'VISA':
                if (!preg_match('/^4\d{15}$/', $reference)) {
                    throw new \Exception('Invalid VISA reference');
                }
                break;
            default:
                throw new \Exception('Unknown payment type');
        }
    }

}
