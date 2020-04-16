@extends('layouts.app')

@section('content')
    <private-chat :authUser="{{auth()->user()}}"></private-chat>
@endsection
