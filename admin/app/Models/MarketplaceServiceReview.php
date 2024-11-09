<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceServiceReview extends Model
{
    use HasFactory;

    protected $table = 'marketplace_service_reviews';

    public function user()
    {
        return $this->belongsTo(UserList::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(MarketplaceService::class, 'id');
    }

    public static function serviceUserRating($row)
    {
        $user_mode = "Guest";
        if (!empty($row->user_id)) {
            $user = UserList::find($row->user_id);
            $user_mode = $user->name;
        }
        return $user_mode;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y h:i A');
    }
}
