<?php

namespace App\Models;

use App\Models\User;
use App\Models\FacebookPage;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Model;

class FacebookAd extends Model
{
    protected $fillable = [
        'user_id',
        'facebook_page_id',
        'wallet_transaction_id',
        'page_link',
        'budget',
        'duration',
        'min_age',
        'max_age',
        'location',
        'button',
        'greeting',
        'status',
        'url',
        'number',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class);
    }
    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class);
    }

}
