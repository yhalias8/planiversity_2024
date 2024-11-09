<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTrip extends Model
{
    use HasFactory;

    protected $table = 'trips';
    protected $primaryKey = "id_trip";

    public function conversations()
    {
        return $this->belongsTo(ChatConversation::class, 'chat_id');
    }
}
