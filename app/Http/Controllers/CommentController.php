<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Video;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Video $video)
    {
        $user = $request->user();
        $comment = $video->comments()->create([
            ...$request->validated(),
            'user_id' => $user->id,
        ]);

        return redirect()->route('videos.show', $video);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Video $video, Comment $comment)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video, Comment $comment)
    {
        $comment->delete();

        return redirect()->route('videos.show', $video);
    }
}
