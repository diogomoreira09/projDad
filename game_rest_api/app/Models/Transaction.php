<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'transaction_datetime',
        'user_id',
        'game_id',
        'type',
        'euros',
        'brain_coins',
        'payment_type',
        'payment_reference'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game() : BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function getTransactions()
{
    $user = auth()->user();

    $transactions = Transaction::where('user_id', $user->id)
        ->orderBy('transaction_datetime', 'desc')
        ->get();

    return response()->json($transactions);
}

    
}
