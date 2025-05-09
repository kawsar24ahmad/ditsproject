<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_method',
        'comment',
        'paid_at',
    ];

}
