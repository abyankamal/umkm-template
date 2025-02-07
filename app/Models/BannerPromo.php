<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPromo extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'url',
        'voucher_id',
        'is_active',
        'start_date',
        'end_date'
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
