<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'short_description', 'vat_rate', 'price', 
        'discount_price','discount_percentage', 'discount_start_date', 'discount_end_date',
        'category_id', 'sub_category_id', 'sub_sub_category_id', 
        'brand_id', 'unit_measure', 'sku', 'barcode', 
        'track_quantity', 'is_featured', 'quantity', 'status', 'is_low_stock_notified'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function sub_sub_category()
    {
        return $this->belongsTo(SubSubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }

    public function  product_images() {
        return $this->hasMany(ProductImage::class);
    }

    public static function getEnumValues($column)
    {
        $result = DB::select("SHOW COLUMNS FROM products WHERE Field = '{$column}'");
        $type = $result[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach(explode(',', $matches[1]) as $value) {
            $enum[] = trim($value, "'");
        }

    return $enum;
    }

    public function getDiscountPriceAttribute()
    {
        if ($this->discount_percentage) {
            return $this->price - ($this->price * $this->discount_percentage / 100);
        }
        return null;
    }

    public function getCurrentPriceAttribute()
    {
        $now = Carbon::now();

        if ($this->discount_percentage && $this->discount_start_date && $this->discount_end_date) {
            if ($now->between($this->discount_start_date, $this->discount_end_date)) {
                return $this->discount_price;
            }
        }

        return $this->price;
    }
}
