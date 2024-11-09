<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectMaster extends Model
{
    use HasFactory;

    protected $table = 'connect_master';
    protected $primaryKey = "id";
}
