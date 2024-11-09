<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentList extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'id_payment';

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y h:i A');
    }

    public function user_select()
    {
        return $this->belongsTo(UserList::class);
    }


    public static function userProcess($user_id)
    {
        $user = UserList::find($user_id);
        return $user;
    }
}
