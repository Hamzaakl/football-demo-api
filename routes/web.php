<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchTrackingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ana sayfa - Maç takip sayfasına yönlendir
Route::get('/', [MatchTrackingController::class, 'index'])->name('home');

// Maç takip route'ları
Route::prefix('match-tracking')->name('match-tracking.')->group(function () {
    Route::get('/', [MatchTrackingController::class, 'index'])->name('index');
    Route::get('/match/{id}', [MatchTrackingController::class, 'show'])->name('show');
    Route::get('/league/{id}', [MatchTrackingController::class, 'league'])->name('league');
    Route::get('/team/{id}', [MatchTrackingController::class, 'team'])->name('team');
    Route::get('/live-scores', [MatchTrackingController::class, 'liveScores'])->name('live');
    Route::get('/date/{date}', [MatchTrackingController::class, 'matchesByDate'])->name('date');
    Route::get('/search', [MatchTrackingController::class, 'search'])->name('search');
});
