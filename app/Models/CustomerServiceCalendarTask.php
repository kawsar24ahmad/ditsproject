<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CustomerServiceCalendarDay;

class CustomerServiceCalendarTask extends Model
{
    protected $fillable = ['calendar_day_id','service_task_id','title','status'];

    public function day()
    {
        return $this->belongsTo(CustomerServiceCalendarDay::class, 'calendar_day_id');
    }

    public function employees()
    {
        return $this->belongsToMany(User::class,'customer_service_calendar_task_employee','calendar_task_id','employee_id');
    }

    public function serviceAssign()  {
        return $this->hasOneThrough(
            ServiceAssign::class,
            CustomerServiceCalendarDay::class,
            'id',
            'id',
            'calendar_day_id',
            'service_assign_id'
        );
    }
}
