<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('chat');
    }

    public function users(){
        return UserResource::collection(User::where('id', '!=', auth()->user()->id)->get());
    }

    public function getWebhooks(){
        // environmental variable must be set
        $app_secret = 'a2b15f23df0d08f82a8f';

        $app_key = $_SERVER['HTTP_X_PUSHER_KEY'];
        $webhook_signature = $_SERVER ['HTTP_X_PUSHER_SIGNATURE'];

        $body = file_get_contents('php://input');

        $expected_signature = hash_hmac( 'sha256', $body, $app_secret, false );

        if($webhook_signature == $expected_signature) {
            // decode as associative array
            $payload = json_decode( $body, true );
            foreach($payload['events'] as &$event) {
                $data[] = $event;
            }
            dd($data);
            header("Status: 200 OK");
        }
        else {
            header("Status: 401 Not authenticated");
        }
    }

    public function postWebhooks(){
        // environmental variable must be set
        $app_secret = 'a2b15f23df0d08f82a8f';

        $app_key = $_SERVER['HTTP_X_PUSHER_KEY'];
        $webhook_signature = $_SERVER ['HTTP_X_PUSHER_SIGNATURE'];

        $body = file_get_contents('php://input');

        $expected_signature = hash_hmac( 'sha256', $body, $app_secret, false );

        if($webhook_signature == $expected_signature) {
            // decode as associative array
            $payload = json_decode( $body, true );
            foreach($payload['events'] as &$event) {
                $data[] = $event;
            }
            dd($data);
            header("Status: 200 OK");
        }
        else {
            header("Status: 401 Not authenticated");
        }
    }
}
