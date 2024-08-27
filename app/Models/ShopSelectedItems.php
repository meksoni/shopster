<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSelectedItems extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id', 'category_id', 'sub_category_id', 'sub_sub_category_id'];
}
