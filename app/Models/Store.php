<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'store_settings';
    protected $fillable = [
        'cp_name',
        'cp_logo',
        'cp_email',
        'cp_phone',
        'global_currency',
        'cp_address',
        'cp_city',
        'cp_country',
        'cp_zip',
        'cp_pib',
        'cp_mb',
        'cp_bank_account',
        'facebook_url',
        'twitter_url',
        'instagram_url'
    ];
}
