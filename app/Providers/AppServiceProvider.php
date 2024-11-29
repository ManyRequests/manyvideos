<?php

namespace App\Providers;

use App\Interfaces\MultimediaService;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Services\FFmpegService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            MultimediaService::class,
            FFmpegService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
