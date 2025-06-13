<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordedClass extends Model
{
    protected $fillable = [
        'title',
        'description',
        'youtube_link',
        'video_path',
    ];
}
