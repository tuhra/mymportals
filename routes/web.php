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

Route::group(['namespace' => 'Frontend', 'middleware' => 'signature'], function() {
	Route::get('/', 'WebController@index');
	Route::get('/singleHE', 'WebController@singleHE');
	Route::get('msisdn', 'WebController@msisdn');
	Route::post('msisdn', 'WebController@postMsisdn');
	Route::get('otp', 'WebController@otp');
	Route::post('otp', 'WebController@postOtp');
	Route::get('resentotp', 'WebController@resentOtp');
	Route::get('success', 'WebController@success');
	Route::get('error', 'WebController@error');
	Route::get('continue', 'WebController@continue');
});

Route::get('invalid', 'Frontend\WebController@invalid');
Route::get('services', 'Frontend\WebController@invalidService');

Route::get('smartkid/callback', 'MaCallbackController@callback');
Route::get('mmsport/callback', 'MaCallbackController@callback');
Route::get('smartkid/checkstatus', 'MaCallbackController@notify');
Route::get('mmsport/checkstatus', 'MaCallbackController@notify');
Route::get('checkTran', 'MaCallbackController@checkStatus');
