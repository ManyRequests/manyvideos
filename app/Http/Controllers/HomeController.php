<?php

namespace App\Http\Controllers;

use App\Enums\VideoStatusEnum;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // it will show only the videos that are processed
        $videos = Video::where('status', VideoStatusEnum::Processed)
            ->withMinAttributes()
            ->with('user', 'tags')
            ->latest()
            ->get();

        return Inertia::render('Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'videos' => $videos,
        ]);
    }
}
