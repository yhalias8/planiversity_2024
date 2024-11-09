<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigrationMaster extends Model
{
    use HasFactory;

    protected $table = 'migration_master';
    protected $primaryKey = "id";

    public function trips()
    {
        return $this->belongsTo(MasterTrip::class, 'trip_id');
    }

    public function peoples()
    {
        return $this->belongsTo(PeopleMaster::class, 'sender_id');
    }

    public function planners()
    {
        return $this->belongsTo(UserList::class, 'planner_user_id');
    }

    public function modifiers()
    {
        return $this->belongsTo(UserList::class, 'modifier_user_id');
    }
}
