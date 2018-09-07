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
    Route::get('deleted', 'ItemController@deletedItems');
    Route::post('create', 'ItemController@create');
    Route::get('get/{id}', 'ItemController@get');
    Route::post('edit/name', 'ItemController@rename');
    Route::post('edit/ident', 'ItemController@changeIdentificationNumber');
    Route::post('edit/note', 'ItemController@changeNote');
    Route::post('image', 'ItemController@changeImage');
    Route::get('delete/{id}', 'ItemController@delete');
    Route::post('addchip', 'ItemController@addchip');
    // returns item with given chip code and its state
    Route::post('findcode', 'ItemController@findWithCode');
    // returns json item list up to 10 items with similar name as query
    Route::post('search', 'ItemController@search');
    // return item with withdrawal info and images
    Route::post('withdrawal', 'ItemController@itemWithdrawalInfo');
    // marks item withdrawal as returned if request has valid administrator card id
    Route::post('return/card', 'ItemController@returnItemWithCard');
    // created unconfirmed return item suspention
    Route::post('suspend/unconfirmedreturn', 'ItemController@suspendUnconfirmedReturn');
    Route::post('suspend/warrantedfix', 'ItemController@suspendWarrantedFix');
    Route::post('suspend/fix', 'ItemController@suspendUnwarrantedFix');
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
    // returns json list of all active objects
     Route::get('list', 'ObjectController@listObjects');
     // adds new object
     Route::post('add', 'ObjectController@add');
  });
  Route::prefix('reservation')->group(function(){
    //create new item reservation for object
    Route::post('create', 'ReservationController@create');
    // returns json list with all active reservations
    Route::get('list', 'ReservationController@list');
    // removes item from active reservation
    Route::post('removeitem', 'ReservationController@removeItemFromReservation');
    // deletes entine active reservation
    Route::get('delete/{id}', 'ReservationController@deleteReservation');
    // confirm reservation with card
    Route::post('confirm/card', 'ReservationController@confirmReservationWithCard');
    // create new item reservation for user
    Route::post('assign', 'ReservationController@createAssignmentReservation');
    // confirm reservation with user signature
    Route::post('confirm/sign', 'ReservationController@confirmReservationWithSignature');
  });
});
Route::get('sendCode/{code}', 'HomeController@sendCode');
