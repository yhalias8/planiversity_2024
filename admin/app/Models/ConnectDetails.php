<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectDetails extends Model
{
    use HasFactory;

    protected $table = 'connect_details';
    protected $primaryKey = "id";
}
