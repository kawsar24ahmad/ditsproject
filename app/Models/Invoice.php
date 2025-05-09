<?php

namespace App\Models;

use App\Models\ServiceAssign;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'service_assign_id', 'invoice_number', 'total_amount','paid_amount', 'status'
    ];

    // ইনভয়েসের সাথে সার্ভিস অ্যাসাইন সম্পর্ক
    public function serviceAssign()
    {
        return $this->belongsTo(ServiceAssign::class, 'service_assign_id');
    }
}
