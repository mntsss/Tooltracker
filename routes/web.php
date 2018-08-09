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
// Autentikacija
$this->get('/login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('/login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
/*
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');*/

Route::get('/', 'HomeController@home')->name('home');

Route::prefix('appointment')->group(function(){
  Route::get('/in', 'HomeController@appointmentIn')->name('appointment.in');
  Route::post('/vehicles/models', 'OrderController@fillModels')->name('vehicle.models');
});

Route::prefix('user')->group(function(){
  Route::get('/password', 'UserController@changePassword')->name('user.settings.password');
});

Route::prefix('/group')->group(function(){
  Route::post('/create', 'ItemGroupController@createItemGroup')->name('itemgroup.create');
  Route::get('/view/{ItemGroupID}', 'ItemController@items')->name('itemgroup.view');
});
