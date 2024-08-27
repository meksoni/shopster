<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyOrder extends Model
{
    
    protected $fillable = [
        'subtotal',
        'shipping',
        'grand_total',
        'full_name',
        'email',
        'address',
        'phone_number',
        'city',
        'zip_code',
        'company_name',
        'company_owner',
        'company_pib',
        'bank_account_number',
        'payment_method',
        'delivery_method',
    ];

    public function order()
    {
        return $this->morphOne(Order::class, 'orderable');
    }
}
