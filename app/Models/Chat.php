<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['message_id', 'session_id', 'user_id', 'type', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    public function message(){
        return $this->belongsTo(Message::class);
    }
}
