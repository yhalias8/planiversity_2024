<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserList extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $hidden = [
        'otp',
        'otp_at',
        'is_reset_pass',
        'password',
    ];
}
