<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePurchase extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'price',
        'wallet_transaction_id',
        'status',
        'approved_at',
    ];
    protected $casts = [
        'approved_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class);
    }
}
