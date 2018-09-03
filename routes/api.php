<?php

use Illuminate\Http\Request;

//auth routes
Route::prefix('auth')->group(function(){
  Route::post('login', 'AuthController@login');
  Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('user', 'AuthController@user');
  });
  Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('refresh', 'AuthController@refresh');
  });
});

Route::group(['middleware' => 'jwt.auth'], function(){
  //item groups routes
  Route::prefix('group')->group(function(){
    Route::get('list', 'ItemGroupController@groups');
    Route::post('create', 'ItemGroupController@create');
    Route::post('rename', 'ItemGroupController@rename');
    Route::post('image', 'ItemGroupController@changeImage');
    Route::get('delete/{id}', 'ItemGroupController@delete');
    Route::get('get/{id}', 'ItemGroupController@get');

  });
  Route::prefix('item')->group(function(){
    Route::get('list/{groupID}', 'ItemController@items');
    Route::post('create', 'ItemController@create');
    Route::get('get/{id}', 'ItemController@get');
    Route::post('rename', 'ItemController@rename');
    Route::post('image', 'ItemController@changeImage');
    Route::get('delete/{id}', 'ItemController@delete');
    Route::post('addchip', 'ItemController@addchip');
    Route::post('findcode', 'ItemController@findWithCode');
    // returns json item list up to 10 items with similar name as query
    Route::post('search', 'ItemController@search');
  });
  Route::prefix('user')->group(function(){
      // returns active users list in json
     Route::get('list', 'UserController@listUsers');
     // returns deleted users list in json
     Route::get('deleted', 'UserController@deletedUsers');
     // creates user
     Route::post('create', 'UserController@create');
     // edits user
     Route::post('edit', 'UserController@edit');
     // deletes user with given id
     Route::get('delete/{id}', 'UserController@delete');
     // adds new rfid card for the user, old one is deleted from user info
     Route::post('addcard', 'UserController@addcard');
     // restores deleted user
     Route::post('restore', 'UserController@restoreuser');
  });
  Route::prefix('object')->group(function(){
     Route::get('list', 'ObjectController@listObjects');
     Route::post('add', 'ObjectController@add');
  });
  Route::prefix('reservation')->group(function(){
    Route::post('create', 'ReservationController@create');
    Route::get('list', 'ReservationController@list');
    Route::post('removeitem', 'ReservationController@removeItemFromReservation');
    Route::get('delete/{id}', 'ReservationController@deleteReservation');
    Route::post('confirm/card', 'ReservationController@confirmReservationWithCard');
  });
});
Route::get('sendCode/{code}', 'HomeController@sendCode');
