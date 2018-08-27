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
  });
  Route::prefix('user')->group(function(){
     Route::get('list', 'UserController@listUsers');
     Route::get('deleted', 'UserController@deletedUsers');
     Route::post('create', 'UserController@create');
     Route::post('edit', 'UserController@edit');
     Route::get('delete/{id}', 'UserController@delete');
     Route::post('addcard', 'UserController@addcard');
     Route::post('restore', 'UserController@restoreuser');
  });
  Route::prefix('object')->group(function(){
     Route::get('list', 'ObjectController@listObjects');
     Route::post('add', 'ObjectController@add');
  });
});
Route::get('sendCode/{code}', 'HomeController@sendCode');
