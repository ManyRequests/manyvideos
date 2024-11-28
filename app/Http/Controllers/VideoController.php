<?php

namespace App\Http\Controllers;

use App\Enums\VideoStatusEnum;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Jobs\GenerateVideoThumbnail;
use App\Jobs\ProcessVideo;
use App\Jobs\SaveVideoMetadata;
use App\Jobs\SendVideoProcessingCompletedNotification;
use App\Jobs\UpdateVideoStatus;
use App\Models\Video;
use Illuminate\Support\Facades\Bus;
use Inertia\Inertia;

class VideoController extends Controller
{
    /**
     * Display a listing of the user videos.
     */
    public function index()
    {
        return Inertia::render('Videos/Index', [
            'videos' => Video::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Videos/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {
        $data = [
            ...$request->only('title', 'description'),
            'thumbnail' => 'thumbnails/default.jpg',
            'size' => $request->file('file')->getSize(),
            'duration' => 60,
            'url' => $request->file('file')->store('videos', 'public'),
            'user_id' => $request->user()->id,
        ];

        // process video
        $video = Video::create($data);

        Bus::chain([
            new ProcessVideo($video),
            new GenerateVideoThumbnail($video),
            new SaveVideoMetadata($video),
            new UpdateVideoStatus($video, VideoStatusEnum::Processed),
            new SendVideoProcessingCompletedNotification($video),
        ])->dispatch();

        return redirect()->route('videos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        //
    }
}
