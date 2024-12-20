<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use App\Models\Transaction;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function getStats()
    {
        $userCount = User::count();
        $gameCount = Game::count();
        $transactionCount = Transaction::count();

        return response()->json([
            'users' => $userCount,
            'games' => $gameCount,
            'transactions' => $transactionCount,
        ]);
    }
}
