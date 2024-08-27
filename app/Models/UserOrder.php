<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    protected $fillable = [
        'subtotal',
        'shipping',
        'grand_total',
        'full_name',
        'email',
        'address',
        'city',
        'phone_number',
        'zip_code',
        'payment_method',
        'delivery_method',
    ];

    public function order()
    {
        return $this->morphOne(Order::class, 'orderable');
    }
}
