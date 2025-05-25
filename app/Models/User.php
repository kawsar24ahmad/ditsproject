<?php

namespace App\Models;

use App\Models\FacebookAd;
use App\Models\FacebookPage;
use App\Models\WalletTransaction;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider_id',
        'provider',
        'fb_access_token',
        'fb_page_id',
        'fb_page_token',
        'wallet_ballance',
        'avatar',
        'role',
        'phone',
        'status',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
    public function facebookAds()
    {
        return $this->hasMany(FacebookAd::class);
    }

    public function facebookPages()
    {
        return $this->hasMany(FacebookPage::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }


}
