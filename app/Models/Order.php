<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'voucher_id',
        'total_discount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function getTotalItems()
    {
        return $this->orderItems->sum('quantity');
    }

    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function applyVoucher(Voucher $voucher)
    {
        $discount = $voucher->calculateDiscount($this->total_amount);

        $this->update([
            'voucher_id' => $voucher->id,
            'total_discount' => $discount,
            'total_amount' => $this->total_amount - $discount
        ]);

        $voucher->increment('used_count');
    }
}
