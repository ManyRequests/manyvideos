<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::redirect('/dashboard', '/videos')->name('dashboard');

    Route::resource('videos', VideoController::class);

    Route::group(['prefix' => 'videos/{video}', 'as' => 'videos.'], function () {
        Route::resource('comments', CommentController::class)->only(['store', 'destroy'])
            ->middleware('can:create,App\Models\Comment,video');
    });

    Route::resource('tags', TagController::class);
});
