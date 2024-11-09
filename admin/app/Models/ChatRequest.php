<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    use HasFactory;

    protected $table = 'chat_requests';
    protected $primaryKey = "id";

    public function migrations()
    {
        return $this->belongsTo(MigrationMaster::class, 'migration_id');
    }

    public function payments()
    {
        return $this->belongsTo(ChatPaymentOffer::class, 'payment_offer_id');
    }
}
