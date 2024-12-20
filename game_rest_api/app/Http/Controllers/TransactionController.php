<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

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
}
