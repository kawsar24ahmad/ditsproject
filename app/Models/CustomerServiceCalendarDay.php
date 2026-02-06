<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerServiceCalendarTask;

class CustomerServiceCalendarDay extends Model
{
    protected $fillable = ['service_assign_id', 'day_number'];

    public function serviceAssign()
    {
        return $this->belongsTo(ServiceAssign::class, 'service_assign_id');
    }

    public function tasks()
    {
        return $this->hasMany(CustomerServiceCalendarTask::class, 'calendar_day_id');
    }

}
