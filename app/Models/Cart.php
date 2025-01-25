<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->productVariant->price * $item->quantity;
        });
    }

    public function calculateTotal()
    {
        $subtotal = $this->cartItems->sum('subtotal');
        $discount = 0;

        if ($this->voucher) {
            $applicableItems = $this->cartItems->filter(
                fn($item) =>
                $this->voucher->isValidFor($item->product_variant->product)
            );

            $applicableAmount = $applicableItems->sum('subtotal');
            $discount = $this->voucher->calculateDiscount($applicableAmount);
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max(0, $subtotal - $discount)
        ];
    }
}
