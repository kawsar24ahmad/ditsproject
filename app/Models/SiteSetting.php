<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_url',
        'logo',
        'favicon',
        'bkash_account_no',
        'bkash_type',
        'nagad_account_no',
        'nagad_type',
        'bank_name',
        'account_name',
        'bank_account_no',
        'bank_branch',
    ];
}
