<?php

namespace App\Http\Controllers;

use App\Events\MessageReadEvent;
use App\Events\PrivateChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\ChatEvent;

class ChatController extends Controller
{

    /**
     * ChatController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $user = User::find(auth()->user()->id);
        event(new ChatEvent($request->message, $user));
    }

    public function privateChat()
    {
        return view('private-chat');
    }

    public function sendMessage(Request $request, Session $session) {

        $message = $session->messages()->create(['content' => $request->content]);

        $chat = $message->createForSend($session->id);
        $message->createForReceive($session->id, $request->toUser);

        broadcast(new PrivateChatEvent($message->content, $chat));

        return response()->json($chat->id, 200);
    }

    public function chats(Session $session){

        return ChatResource::collection($session->chats->where('user_id', auth()->user()->id));
    }

    public function read(Session $session) {

        $chats = $session->chats->where('read', null)->where('type', 0)->where('user_id', '!=', auth()->user()->id);
        foreach ($chats as $chat) {
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new MessageReadEvent(new ChatResource($chat), $chat->session_id));
        }

    }

    public function clear(Session $session) {

        $session->deleteChats();
        $session->chats->count() == 0 ? $session->deleteMessages() : "";
        return response()->json('cleared', 200);
    }
}
