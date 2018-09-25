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
//Auth::routes();


Route::get('/', 'HomeController@home')->name('home');

Route::get('/groups', function(){
    return redirect('/');
})->name('home');
Route::get('/login', function(){
    return redirect('/');
})->name('login');
Route::get('/group/{id?}', function(){
    return redirect('/');
})->name('home');
Route::get('/item', function(){
    return redirect('/');
})->name('home');
Route::get('/item/deleted', function(){
    return redirect('/');
})->name('home');
Route::get('/item/suspended', function(){
    return redirect('/');
})->name('home');
Route::get('/users', function(){
    return redirect('/');
})->name('home');
Route::get('/users/deleted', function(){
    return redirect('/');
})->name('home');
Route::get('/objects/closed', function(){
    return redirect('/');
})->name('home');
Route::get('/objects', function(){
    return redirect('/');
})->name('home');
Route::get('/reservation/active', function(){
    return redirect('/');
})->name('home');
Route::get('/reservation/cart', function(){
    return redirect('/');
})->name('home');
Route::get('/reservation/closed', function(){
    return redirect('/');
})->name('home');
Route::get('/reservation/assign', function(){
    return redirect('/');
})->name('home');
Route::get('/item/return', function(){
    return redirect('/');
})->name('home');
Route::get('/rented', function(){
    return redirect('/');
})->name('home');
Route::get('/rented/item', function(){
    return redirect('/');
})->name('home');
Route::get('/rented/withdrawals', function(){
    return redirect('/');
})->name('home');

// Route::get('/{catchall?}', function () {
//     return response()->view('layouts/main');
// })->where('catchall', '(.*)');
