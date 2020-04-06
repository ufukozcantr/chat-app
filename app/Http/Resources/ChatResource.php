<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'message' => $this->message['content'],
            'id' => $this->id,
            'type' => $this->type,
            'read_at' => $this->read_at_timing($this),
            'send_at' => $this->created_at->diffForHumans()
        ];
    }

    private function read_at_timing($_this){

        $readAt = $_this->type == 0 ? $_this->read_at : null;
        return $readAt ? $readAt->diffForHumans() : null;
    }
}
