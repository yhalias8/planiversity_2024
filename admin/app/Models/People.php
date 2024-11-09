<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = "id_employee";

    public function connection($userId, $where_not)
    {
        $list = People::join('users', 'employees.employee_id', '=', 'users.customer_number')
            ->select('users.id', 'users.name', 'users.customer_number', 'users.picture')
            ->where('employees.id_user', $userId)
            ->where('users.active', 1)
            ->whereNotIn('id', $where_not)
            ->orderBy('id', 'desc');

        return $list;
    }    
}
