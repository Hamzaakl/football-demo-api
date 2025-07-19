<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FootballController;
use App\Http\Controllers\Api\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Football API Routes
Route::prefix('football')->group(function () {
    Route::get('/live-scores', [FootballController::class, 'getLiveScores']);
    Route::get('/todays-matches', [FootballController::class, 'getTodaysMatches']);
    Route::get('/popular-leagues', [FootballController::class, 'getPopularLeagues']);
    Route::get('/match/{id}', [FootballController::class, 'getMatchDetails']);
    Route::get('/league/{id}/matches', [FootballController::class, 'getLeagueMatches']);
    Route::get('/team/{id}/matches', [FootballController::class, 'getTeamMatches']);
    Route::post('/sync-data', [FootballController::class, 'syncData']);
});

// Chat API Routes
Route::prefix('chat')->group(function () {
    Route::get('/fixture/{id}/messages', [ChatController::class, 'getMessages']);
    Route::post('/fixture/{id}/send', [ChatController::class, 'sendMessage']);
    Route::post('/fixture/{id}/system-message', [ChatController::class, 'sendSystemMessage']);
    Route::delete('/message/{id}', [ChatController::class, 'deleteMessage']);
    Route::get('/fixture/{id}/active-users', [ChatController::class, 'getActiveUsers']);
    Route::get('/fixture/{id}/stats', [ChatController::class, 'getChatStats']);
});
