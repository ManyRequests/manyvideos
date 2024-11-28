<?php

namespace App\Models;

use App\Enums\VideoStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'thumbnail',
        'size',
        'duration',
        'width',
        'height',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => VideoStatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
