<?php

namespace App\Models;

use App\Models\AssignedTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceAssign extends Model
{
    protected $fillable = [
        'customer_id', 'employee_id', 'service_id', 'price', 'paid_payment'
    ];

    // একে এক সম্পর্ক ইনভয়েসের সাথে
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'service_assign_id');
    }
    public function customer()  {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function employee()  {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function service()  {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function assignedTasks()
    {
        return $this->hasMany(AssignedTask::class);
    }
}
