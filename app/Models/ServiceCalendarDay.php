<?php

namespace App\Models;

use App\Models\Service;
use App\Models\ServiceCalendarTask;
use Illuminate\Database\Eloquent\Model;

class ServiceCalendarDay extends Model
{
    protected $fillable = [
        'service_id',
        'day_number',
        'notes',
        'sort_order',
    ];

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function tasks() {
        return $this->hasMany(ServiceCalendarTask::class, 'service_calendar_day_id');
    }
}
