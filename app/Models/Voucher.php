<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $dates = ['start_date', 'end_date'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function isValidFor(Product $product)
    {
        return $this->isActive() &&
            ($this->products->contains($product) ||
                $this->categories->contains($product->category));
    }

    public function isActive()
    {
        return $this->is_active &&
            now()->between($this->start_date, $this->end_date) &&
            ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function calculateDiscount($amount)
    {
        if ($this->type === 'percentage') {
            return $amount * ($this->value / 100);
        }
        return min($this->value, $amount);
    }
}
