<?php

namespace App\Models;

use App\Models\User;
use App\Models\ServiceCalendarDay;
use Illuminate\Database\Eloquent\Model;

class ServiceCalendarTask extends Model
{
    protected $fillable = [
        'service_calendar_day_id',
        'title',
        'description',
        'status'	,
        'sort_order',
    ];
    public function employees()
    {
        return $this->belongsToMany(
            User::class,
            'service_task_user', // pivot table
            'service_calendar_task_id',
            'user_id'
        );
    }


    public function day()
    {
        return $this->belongsTo(ServiceCalendarDay::class, 'service_calendar_day_id');
    }


}
