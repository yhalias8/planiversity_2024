<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleMaster extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = "id_employee";
}
