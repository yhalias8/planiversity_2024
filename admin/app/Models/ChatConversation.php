<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $table = 'chat_conversations';
    protected $appends = ['need_action'];
    protected $primaryKey = "id";

    public function sender()
    {
        return $this->belongsTo(UserList::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(UserList::class, 'recipient_id');
    }
    
        public function getNeedActionAttribute()
    {

        $reuslt = ChatMessage::where(
            [
                'chat_id' => $this->id,
                'sender_id' => $this->recipient_id,
                'is_seen' => 0,
            ]
        )->value('id');

        if (!empty($reuslt)) {
            return true;
        }

        return false;

       
    }
}
