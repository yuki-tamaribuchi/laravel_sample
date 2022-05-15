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
    return view('welcome');
})->name('welcome');


Route::get('hello', 'App\Http\Controllers\HelloController@getHello')->name('hello');


Route::match(array('GET', 'POST') ,'/accounts/signup', 'App\Http\Controllers\AccountsController@signup')->name('signup');
Route::match(array('GET', 'POST'), '/accounts/login', 'App\Http\Controllers\AccountsController@login')->name('login');
Route::get('/accounts/logout', 'App\Http\Controllers\AccountsController@logout')->name('logout');
Route::get('/accounts/detail/{id}', 'App\Http\Controllers\AccountsController@detail')->name('account_detail');
Route::match(array('GET', 'POST'), '/accounts/update/{id}', 'App\Http\Controllers\AccountsController@update')->name('account_update');
Route::match(array('GET', 'POST'), '/accounts/delete/{id}', 'App\Http\Controllers\AccountsController@delete')->name('account_delete');