<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\View\Components\Task;

class AssignedTask extends Model
{
    protected $fillable = [
        'service_assign_id',	'service_task_id',	'is_completed',	'completed_at', 'title',	'notes',	'added_by',
    ];

    public function task()
    {
        return $this->belongsTo(ServiceTask::class, 'service_task_id');
    }

    public function serviceAssign()
    {
        return $this->belongsTo(ServiceAssign::class);
    }
    protected $casts = [
        'completed_at' => 'datetime',
    ];


}
