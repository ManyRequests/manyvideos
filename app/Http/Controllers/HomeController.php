<?php

namespace App\Http\Controllers;

use App\Enums\VideoStatusEnum;
use App\Http\Requests\IndexHomeRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(IndexHomeRequest $request)
    {
        $perpage = $request->perpage ?? 10;

        // it will show only the videos that are processed
        $videos = Video::where('status', VideoStatusEnum::Processed)
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%$search%");
            })
            ->when($request->size_min, function ($query, $size_min) {
                $size_min = $size_min * 1024 * 1024;
                $query->where('size', '>=', $size_min);
            })
            ->when($request->size_max, function ($query, $size_max) {
                $size_max = $size_max * 1024 * 1024;
                $query->where('size', '<=', $size_max);
            })
            ->when($request->duration_min, function ($query, $duration_min) {
                $duration_min = $duration_min * 60;
                $query->where('duration', '>=', $duration_min);
            })
            ->when($request->duration_max, function ($query, $duration_max) {
                $duration_max = $duration_max * 60;
                $query->where('duration', '<=', $duration_max);
            })
            ->withMinAttributes()
            ->with('user', 'tags')
            ->withCommentsCount()
            ->latest()
            ->paginate($perpage);

        return Inertia::render('Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'videos' => $videos,
        ]);
    }
}
