<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FixtureController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\RankingNotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RankingCommentController;
use App\Http\Controllers\RankingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RankingController::class, 'index'])->name('ranking');
Route::post('/comentarios', [RankingCommentController::class, 'store'])->name('ranking.comments.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('participants', ParticipantController::class)->except(['show']);

    Route::get('/fixtures', [FixtureController::class, 'index'])->name('fixtures.index');
    Route::put('/fixtures', [FixtureController::class, 'update'])->name('fixtures.update');

    Route::post('/notifications/ranking', [RankingNotificationController::class, 'store'])
        ->name('notifications.ranking');

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
});
