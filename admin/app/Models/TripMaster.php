<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripMaster extends Model
{
    use HasFactory;

    protected $table = 'trips';
    protected $primaryKey = "id_trip ";
}
