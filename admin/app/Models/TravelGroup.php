<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelGroup extends Model
{
    use HasFactory;

    protected $table = 'travel_groups';
    protected $primaryKey = "id";
}
