<?php

namespace App\Models;

use App\Models\User;
use App\Models\ServiceAssign;
use Illuminate\Database\Eloquent\Model;

class ServiceTaskReport extends Model
{
    protected $fillable = [
        'service_assign_id',
        'employee_id',
        'date',
        'work_details',
    ];

    public function serviceAssign()
    {
        return $this->belongsTo(ServiceAssign::class);
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
