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
    Route::post('create', 'ItemGroupController@create')->middleware('role');
    Route::post('rename', 'ItemGroupController@rename')->middleware('role');
    Route::post('image', 'ItemGroupController@changeImage')->middleware('role');
    Route::get('delete/{id}', 'ItemGroupController@delete')->middleware('role');
    Route::get('get/{id}', 'ItemGroupController@get');

  });
  Route::prefix('item')->group(function(){
    Route::get('list/{groupID}', 'ItemController@items');
    Route::get('suspended', 'ItemController@suspendedItems');
    Route::get('deleted', 'ItemController@deletedItems');
    // returns item history
    Route::get('history/{id}', 'ItemController@history');
    Route::post('create', 'ItemController@create')->middleware('role');
    Route::get('get/{id}', 'ItemController@get');
    Route::post('edit', 'ItemController@edit')->middleware('role');

    Route::post('image', 'ItemController@changeImage')->middleware('role');
    Route::post('delete', 'ItemController@delete')->middleware('role');
    Route::post('restore', 'ItemController@restore')->middleware('role');
    Route::post('addchip', 'ItemController@addchip')->middleware('role');
    // returns item with given chip code and its state
    Route::post('findcode', 'ItemController@findWithCode');
    // returns json item list up to 10 items with similar name as query
    Route::post('search', 'ItemController@search');
    // marks item withdrawal as returned if request has valid administrator card id
    Route::post('return/card', 'ItemWithdrawalController@return');
    // created unconfirmed return item suspention
    Route::prefix('suspend')->group(function(){
        Route::post('unconfirmedreturn', 'ItemSuspentionController@unconfirmedReturn')->middleware('role');
        Route::post('warrantedfix', 'ItemSuspentionController@warrantedFix')->middleware('role');
        Route::post('fix', 'ItemSuspentionController@unwarrantedFix')->middleware('role');
        // confirm suspended item for unconfirmed return
        Route::prefix('return')->group(function(){
            Route::post('unconfirmed', 'ItemSuspentionController@returnConfirmed')->middleware('role');
            Route::post('fixed', 'ItemSuspentionController@fixed')->middleware('role');
        });
    });
  });

  Route::prefix('suspention')->group(function(){
     Route::get('get/unconfirmedreturn', 'ItemSuspentionController@getWaitingConfirmationSuspentions');
     Route::get('get/fixing', 'ItemSuspentionController@getFixSuspentions');
  });

  Route::prefix('withdrawal')->group(function(){
    Route::get('get/{id}', 'ItemWithdrawalController@get');
  });

  Route::prefix('user')->group(function(){
     Route::get('me', 'UserController@me');
      // returns active users list in json
     Route::get('list', 'UserController@listUsers');
     // returns deleted users list in json
     Route::get('deleted', 'UserController@deletedUsers');
     // creates user
     Route::post('create', 'UserController@create')->middleware('role');
     // edits user
     Route::post('edit', 'UserController@edit')->middleware('role');
     // deletes user with given id
     Route::post('edit/password', 'UserController@changePasswordSubmit');
     Route::get('delete/{id}', 'UserController@delete')->middleware('role');
     // adds new rfid card for the user, old one is deleted from user info
     Route::post('addcard', 'UserController@addcard')->middleware('role');
     // restores deleted user
     Route::post('restore', 'UserController@restoreuser')->middleware('role');
     // get user withdrew items
     Route::get('withdrawals/{id}', 'UserController@getWithdrawals');
  });
  Route::prefix('object')->group(function(){
    // returns json list of all active objects
     Route::get('list', 'ObjectController@listObjects');
     // returns json list of closed objects
     Route::get('closed', 'ObjectController@closedObjects');
     // adds new object
     Route::post('add', 'ObjectController@add')->middleware('role');
     // assigns and removes foreman to / from the object
     Route::prefix('foreman')->group(function(){
        Route::get('object/{objectID}', 'ObjectController@getObjectForemen');
        Route::post('assign', 'ObjectController@assignForeman');
        Route::post('remove', 'ObjectController@removeForeman');
     });
  });
  Route::prefix('reservation')->group(function(){
    //create new item reservation for object
    Route::post('create', 'ReservationController@create')->middleware('role');
    // returns json list with all active reservations
    Route::get('list', 'ReservationController@list');
    // returns json list with closed reservations
    Route::get('closed', 'ReservationController@closed');
    // removes item from active reservation
    Route::post('removeitem', 'ReservationController@removeItemFromReservation')->middleware('role');
    // deletes entine active reservation
    Route::get('delete/{id}', 'ReservationController@deleteReservation')->middleware('role');
    // confirm reservation with card
    Route::post('confirm/card', 'ReservationController@confirmReservationWithCard')->middleware('role');
    // create new item reservation for user
    Route::post('assign', 'ReservationController@createAssignmentReservation')->middleware('role');
    // confirm reservation with user signature
    Route::post('confirm/sign', 'ReservationController@confirmReservationWithSignature')->middleware('role');
  });

  Route::prefix('history')->group(function(){
     Route::prefix('item')->group(function(){
       Route::get('all', "HistoryController@getItemsHistory");
         Route::get('suspentions/{id}', function(){ return 1;});
         Route::get('withdrawals/{id}', function(){ return 1;});
     });
     Route::get('user/{userID}', 'HistoryController@getUserHistory');
     Route::prefix('object')->group(function(){

     });

  });
  Route::prefix('rented')->group(function(){
    // get all rented items
    Route::get('get', 'RentedItemController@get');
    // get 5 longest rented items that are still Active
    Route::get('get/longest', 'RentedItemController@getLongestRented');
    // get rented item info by id
    Route::get('item/{id?}', 'RentedItemController@item');
    Route::post('create', 'RentedItemController@create')->middleware('role');
    Route::post('edit', 'RentedItemController@edit')->middleware('role');
    Route::post('assign', 'RentedItemController@assign')->middleware('role');
    Route::post('return', 'RentedItemController@return')->middleware('role');
  });
});
Route::get('sendCode/{key}/{userID}/{code}', 'HomeController@sendCode');
