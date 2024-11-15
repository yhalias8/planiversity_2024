<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    use HasFactory;

    protected $table = 'marketplace_orders';

    protected $appends = ['order_payments'];

    public function services()
    {
        return $this->belongsTo(MarketplaceService::class, 'service_id');
    }

    public function getOrderPaymentsAttribute()
    {
        $orders = MarketplaceOrderPayment::select('transaction_id', 'payment_type', 'status')->where('order_id', $this->id)->first();

        return $orders;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y h:i A');
    }

    public static function orderUserProcess($row)
    {
        $user_mode = "Guest";
        if ($row->guest == 0) {
            $user = UserList::find($row->user_id);
            $user_mode = $user->name;
        }
        return $user_mode;
    }

    public static function orderCategoryProcess($category_id)
    {
        $category = MarketplaceCategory::find($category_id);
        return $category->category_name;
    }
}
