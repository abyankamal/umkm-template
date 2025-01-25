<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function hasVariants()
    {
        return $this->variants()->exists();
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->isDiscountValid()) {
            return $this->calculateDiscount();
        }
        return $this->price;
    }

    protected function isDiscountValid()
    {
        $now = now();
        return $this->discount_value &&
            $this->discount_start <= $now &&
            $this->discount_end >= $now;
    }

    protected function calculateDiscount()
    {
        if ($this->discount_type === 'percentage') {
            return $this->price * (1 - ($this->discount_value / 100));
        }
        return max(0, $this->price - $this->discount_value);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class);
    }
}
