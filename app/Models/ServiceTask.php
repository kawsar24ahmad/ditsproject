<?php

namespace App\Models;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;

class ServiceTask extends Model
{
    protected $fillable = ['service_id', 'title', 'is_completed'];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
