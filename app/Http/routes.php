<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
Route::get('/account', 'WelcomeController@index');

Route::get('dashboard', 'Dashboard\DashboardController@index');
Route::get('dashboard/index', 'Dashboard\DashboardController@index');
Route::get('home', 'Dashboard\DashboardController@index');
Route::get('faq', 'Dashboard\DashboardController@faq');

Route::get('profile/password', 'Profile\ProfileController@password');
Route::post('profile/changePassword','Profile\ProfileController@changePassword');
Route::get('profile/confirm/{confirmationCode}', ['as' => 'profile/confirm', 'uses' => 'Profile\ProfileController@confirm']);
Route::get('account/confirm-email/id/{confirmationCode}', ['as' => 'profile/confirm', 'uses' => 'Profile\ProfileController@confirm']);  // Make backwards compatible with old confirmation link

// This is created as a resource for future use when the fleet admin is implemented and there is a need to create new user profiles with access control
Route::resource('profile', 'Profile\ProfileController');

// New routes to handle encryption
Route::get('cryptosystem', 'Profile\ProfileController@initCryptosystem');
Route::post('generate', 'Profile\ProfileController@generateKeys');
Route::get('decrypt', 'Profile\ProfileController@decrypt');

Route::get('flights', ['as'   => 'flights', 'uses' => 'Flights\FlightController@index']);
Route::get('flights/event/{exceedance}', ['as'   => 'flights/event', 'uses' => 'Flights\FlightController@index']);  // Technically this is equivalent to the above route, however its a nice custom URL for the exceedances
Route::get('flights/trend', ['as'   => 'flights/trend', 'uses' => 'Flights\FlightController@trend']);
Route::get('flights/chart/{flight}', ['as' => 'flights/chart', 'uses' => 'Flights\FlightController@chart']);
Route::get('flights/replay/{flight}', ['as' => 'flights/replay', 'uses' => 'Flights\FlightController@replay']);
Route::get('flights/load/{flight}', ['as' => 'flights/load', 'uses' => 'Flights\FlightController@loadReplay']);
Route::get('flights/download/{flight}/{format}/{exceedance?}/{duration?}', ['as' => 'flights/download', 'uses' => 'Flights\FlightController@download'], function ($exceedance = null) {
    return $exceedance;  // $exceedance is an optional parameter that can be null... I don't think this logic is working in the controller
});
Route::get('flights/archive/{flight}', ['as' => 'flights/archive', 'uses' => 'Flights\FlightController@archive']);
Route::resource('flights', 'Flights\FlightController', ['only' => ['edit', 'create', 'update']]);

Route::get('import', 'Import\ImportController@index');
Route::get('import/status', 'Import\ImportController@status');
Route::get('import/upload', 'Import\ImportController@upload');
Route::resource('import', 'Import\ImportController', ['only' => ['store', 'create']]);

Route::get('approach', 'StabilizedApproach\StabilizedApproachController@index');
Route::get('approach/index/', 'StabilizedApproach\StabilizedApproachController@index');
Route::get('approach/analysis/', 'StabilizedApproach\StabilizedApproachController@analysis');
Route::get('approach/chart/', 'StabilizedApproach\StabilizedApproachController@chart');
Route::get('approach/airports/', 'StabilizedApproach\StabilizedApproachController@airports');
Route::get('approach/runways/', 'StabilizedApproach\StabilizedApproachController@runways');

Route::get('approach/selfdefined/', 'SelfDefinedApproach\SelfDefinedApproachController@index');
Route::get('approach/selfdefined/chart', 'SelfDefinedApproach\SelfDefinedApproachController@chart');
Route::get('approach/selfdefined/flights', 'SelfDefinedApproach\SelfDefinedApproachController@flights');

Route::get('dbx', 'DropboxAuthController@index');
Route::post('dbx', 'DropboxAuthController@store');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('test','TestController@testView');
Route::get('turnToFinal','TurnToFinalController@start');

Route::get('turnToFinal/runQuery','TurnToFinalController@runQuery');
Route::get('turnToFinal/viewFlights','TurnToFinalController@viewFlights');
