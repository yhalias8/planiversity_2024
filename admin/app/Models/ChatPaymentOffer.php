<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatPaymentOffer extends Model
{
    use HasFactory;

    protected $table = 'chat_payment_offer';
    protected $primaryKey = "id";

    public function conversations()
    {
        return $this->belongsTo(ChatConversation::class, 'chat_id');
    }

    public function sender()
    {
        return $this->belongsTo(UserList::class, 'sender_id');
    }
}
