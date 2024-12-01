<?php

return [
    'path' => 'videos',
    'disk' => 'public',
    'max-upload-size' => env('VIDEO_MAX_UPLOAD_SIZE_MB', 20) * 1024 * 1024,
    'max-upload-size-human' => env('VIDEO_MAX_UPLOAD_SIZE_MB', 20) . 'MB',
    'mime-types' => [
        'video/mp4',
    ],
    'extensions' => [
        'mp4',
    ],
];
