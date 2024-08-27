<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtotal',
        'shipping',
        'grand_total',
        'payment_method',
        'delivery_method',
        'is_opened',
    ];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
