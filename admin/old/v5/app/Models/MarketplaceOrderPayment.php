<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrderPayment extends Model
{
    use HasFactory;

    protected $table = 'marketplace_order_payments';

    public function order()
    {
        return $this->belongsTo(MarketplaceOrder::class, 'order_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y h:i A');
    }
}
