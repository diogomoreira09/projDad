<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TransactionsController;





Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/games/total', [GameController::class, 'totalGames']);

Route::get('/games/top-scores', [GameController::class, 'topScores']);

Route::get('/boards', [GameController::class, 'getBoardOptions']);
Route::post('/memory-game', [GameController::class, 'createMemoryGame']);

Route::get('/statistics', [StatisticsController::class, 'getStats']);


// Routes inside the following group require authentication (must include Authentication header)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refreshtoken', [AuthController::class, 'refreshToken']);
    Route::get('/users/me', [UserController::class, 'showMe']);
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{game}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::patch('/games/{game}', [GameController::class, 'updateStatus']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);
    // Rotas protegidas por autenticação
    Route::get('/user', [UserController::class, 'profile']);
    Route::get('/games/history', [GameController::class, 'gameHistory']);
    Route::get('/transactions', [TransactionsController::class, 'showTransactions']);

});

Route::middleware('auth:api')->group(function () {
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
});