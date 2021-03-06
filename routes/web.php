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

Route::get('test', function () {
	$encode="[MCV]znmOs6M+/hzoEw8IyTDkFQ==";
	// $decode=exec("java JavaDecryptCaller ".$encode);
	$decode = exec('cd /var/www/decrypt && java JavaDecryptCaller '.$encode);
	echo $decode;	
});

Route::group(['namespace' => 'Frontend', 'middleware' => 'signature'], function() {
	Route::get('/', 'WebController@index');
});

Route::group(['namespace' => 'Frontend', 'middleware' => 'cors'], function() {
	Route::get('/singleHE', 'WebController@singleHE');
	Route::get('msisdn', 'WebController@msisdn');
	Route::post('msisdn', 'WebController@postMsisdn');
	Route::get('otp', 'WebController@otp');
	Route::post('otp', 'WebController@postOtp');
	Route::get('resentotp', 'WebController@resentOtp');
	Route::get('success', 'WebController@success');
	Route::get('error', 'WebController@error');
	Route::get('continue', 'WebController@continue');
	Route::get('invalid', 'WebController@invalid');
	Route::get('services', 'WebController@invalidService');
	Route::get('unsubscribe', 'WebController@unsubscribe');
});


Route::get('import', 'ImportController@import');
Route::post('import', 'ImportController@postImport');


// Smart Kid Callback and Redirect URL
Route::get('smartkid/callback', 'MaCallbackController@callback');
Route::get('smartkid/checkstatus', 'MaCallbackController@notify');

// Myanmar Sport Callback and Redirect URL
Route::get('mmsport/callback', 'MaCallbackController@callback');
Route::get('mmsport/checkstatus', 'MaCallbackController@notify');

// Guess It Subscription Callback and Redirect URL
Route::get('guessitsub/callback', 'MaCallbackController@callback');
Route::get('guessitsub/checkstatus', 'MaCallbackController@notify');

// Guess It Event Callback and Redirect URL
Route::get('guessit/callback', 'MaCallbackController@callback');
Route::get('guessit/checkstatus', 'MaCallbackController@notify');

Route::get('checkTran', 'MaCallbackController@checkStatus');











