<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChatMessage extends Model
{
    use HasFactory;

    //protected $appends = ['senders'];

    protected $table = 'chat_messages';
    protected $primaryKey = "id";

    // public function conversations()
    // {
    //     return $this->belongsTo(ChatConversation::class, 'chat_id');
    // }

    public function senders()
    {
        return $this->belongsTo(UserList::class, 'sender_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y h:i A');
    }

    // public function getSendersAttribute()
    // {
    //     dd($this);
    // }

    public function requests()
    {
        return $this->belongsTo(ChatRequest::class, 'request_id');
    }
}
