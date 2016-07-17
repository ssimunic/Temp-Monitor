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

Route::get('home', 'HomeController@index');

Route::get('getstarted', 'HomeController@getstarted');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('sensor', array(
    'as' => 'sensor',
    'uses' => 'SensorController@index',
));

Route::resource('sensor', 'SensorController');

Route::get('sensor/delete/{unique_id}', 'SensorController@delete');

Route::get('sensor/api/{unique_id}', 'SensorController@apiShow');

Route::get('sensoredit', 'SensorController@edit');

Route::get('monitor/{unique_id}', 'SensorController@monitor');

Route::get('update', 'DataController@update');

Route::get('data/{unique_id}/{type}', 'DataController@data');

Route::get('api/sensor/{unique_id}', 'ApiController@sensorData');

Route::get('api/data/push', 'DataController@update');

Route::get('map/basic', 'MapController@mapBasic');

Route::get('map/custom', 'MapController@mapCustom');

Route::resource('map', 'MapController');

Route::get('map', array(
    'as' => 'map',
    'uses' => 'MapController@mapCustom',
));

Route::get('map/view/{id}', 'MapController@show');

Route::get('map/add/{id}/{unique_id}', 'MapController@addSensorToMap');

Route::get('mapentry/edit/{id}/{location}', 'MapController@editMapEntry');

Route::get('map/delete/{id}', 'MapController@delete');

Route::get('profile', 'UserController@profile');
Route::post('profile', 'UserController@profileSave');

