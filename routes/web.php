<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\VideoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
