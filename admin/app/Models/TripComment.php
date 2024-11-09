<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TripComment extends Model
{
    use HasFactory;

    protected $table = 'trip_comments';
    protected $primaryKey = "id ";

    public function user()
    {
        return $this->belongsTo(UserList::class, 'user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y h:i A');
    }
}
