<?php

namespace App\Models;

use App\Models\FacebookAd;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'method',
        'payment_method',
        'sender_number',
        'transaction_id',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function facebookAd()
    {
        return $this->hasOne(FacebookAd::class);
    }
}
