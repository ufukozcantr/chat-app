<?php

namespace App\Http\Resources;

use App\Models\Session;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'online' => false,
            'session' => $this->sessionDetail($this->id),
        ];
    }

    private function sessionDetail($id){
        return new SessionResource(
            Session::whereIn('user1_id', [auth()->user()->id, $id])->whereIn('user2_id', [auth()->user()->id, $id])->first()
        );
    }
}
