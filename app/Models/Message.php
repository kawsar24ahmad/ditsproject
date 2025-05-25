<?php

namespace App\Models;

use App\Models\ServiceAssign;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'service_assign_id',
        'sender_id',
        'message',
    ];

    public function serviceAssign()
    {
        return $this->belongsTo(ServiceAssign::class, 'service_assign_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}
