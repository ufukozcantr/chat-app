<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('chat', 'ChatController@chat');
Route::get('private-chat', 'ChatController@privateChat');
Route::post('send', 'ChatController@send');
Route::post('send/{session}', 'ChatController@sendMessage');
Route::get('session/{session}/chats', 'ChatController@chats');
Route::post('session/{session}/read', 'ChatController@read');
Route::post('session/{session}/clear', 'ChatController@clear');
Route::post('session/{session}/block', 'BlockController@block');
Route::post('session/{session}/unblock', 'BlockController@unblock');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users', 'HomeController@users');
Route::post('/session/create', 'SessionController@create');

Route::get('/webhooks', 'HomeController@getWebhooks');
Route::post('/webhooks', 'HomeController@postWebhooks');
