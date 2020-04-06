<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['user1_id', 'user2_id', 'block'];

    public function chats(){
        return $this->hasManyThrough(Chat::class, Message::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function deleteChats() {
        $this->chats()->where('user_id', auth()->user()->id)->delete();
    }

    public function deleteMessages() {
        $this->messages()->delete();
    }

    public function block() {
        $this->block = true;
        $this->blocked_by = auth()->user()->id;
        $this->save();
    }

    public function unblock() {
        $this->block = false;
        $this->blocked_by = null;
        $this->save();
    }
}
