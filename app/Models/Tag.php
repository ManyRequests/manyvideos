<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'video_tag', 'tag_id', 'video_id');
    }
}
