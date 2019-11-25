@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row" id="app">
        <div class="offset-4 col-4 offset-sm-1 col-sm-10">
            <ul class="list-group">
                <li class="list-group-item active">Chat Room</li>
                <div class="badge badge-pill badge-primary">@{{ typing }}</div>
            </ul>
            <ul class="list-group listgroup" v-chat-scroll>
                <div class="alert-warning" 
                    v-if="!chat.message.length"
                    style="margin: 20px; text-align: center;"
                >
                    No Message
                </div>
                <message
                    v-for="value,index in chat.message"
                    :key="value.index"
                    :color=chat.color[index]
                    :user=chat.user[index] 
                    :time=chat.time[index]
                    :current='{{json_encode(Auth::user()->name)}}'
                >
                @{{ value }}
                </message>
            </ul>
            <input type="text" class="form-control" placeholder="Enter message..." v-model="message" @keyup.enter="send">
        </div>
    </div>
</div>


@endsection
<!--
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            .listgroup{
                overflow-y: scroll;
                height: 400px;
                margin-top: 5px;
            }
        </style>
        
    </head>
    <body>
        <div class="container">
            <div class="row" id="app">
                <div class="offset-4 col-4 offset-sm-1 col-sm-10">
                    <ul class="list-group">
                        <li class="list-group-item active">Chat Room</li>
                        <div class="badge badge-pill badge-primary">@{{ typing }}</div>
                    </ul>
                    <ul class="list-group listgroup" v-chat-scroll>
                        <message
                            v-for="value,index in chat.message"
                            :key="value.index"
                            :color=chat.color[index]
                            :user=chat.user[index] 
                            :time=chat.time[index]
                            :current='{{json_encode(Auth::user()->name)}}'
                        >
                        @{{ value }}
                        </message>
                    </ul>
                    <input type="text" class="form-control" placeholder="Enter message..." v-model="message" @keyup.enter="send">
                </div>
            </div>
        </div>

    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
-->
