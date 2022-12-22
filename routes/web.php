<?php

use Illuminate\Support\Facades\Route;

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
    return redirect('director');
});

Route::get('director', 'App\Http\Controllers\DirectiveController@index')->name('directive');
Route::post('directive/register', 'App\Http\Controllers\DirectiveController@create')->name('directive.register')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('directive/new_directives', 'App\Http\Controllers\DirectiveController@getNewDirectives')->name('directive.new_directives');
Route::get('directive/complete/{id}', 'App\Http\Controllers\DirectiveController@complete')->name('directive.complete');

Route::get('/client', 'App\Http\Controllers\ClientController@index')->name('client');
Route::get('/client/state', 'App\Http\Controllers\ClientController@state')->name('client.state');
Route::post('/client/update/{id}', 'App\Http\Controllers\ClientController@update')->name('client.update')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
