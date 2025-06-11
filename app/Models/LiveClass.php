<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
    protected $fillable = [
        'title',
        'description',
        'meeting_url',
        'start_time',
        'end_time',
    ];
}
