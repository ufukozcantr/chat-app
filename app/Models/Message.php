<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['content', 'session_id'];

    public function chats(){
        return $this->hasMany(Chat::class);
    }

    public function createForSend($sessionId){

        return $this->chats()->create([
            'session_id' => $sessionId,
            'type' => 0,
            'user_id' => auth()->user()->id
        ]);

    }

    public function createForReceive($sessionId, $toUser){

        return $this->chats()->create([
            'session_id' => $sessionId,
            'type' => 1,
            'user_id' => $toUser
        ]);

    }
}
