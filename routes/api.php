<?php

use Illuminate\Http\Request;

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@store');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', 'LoginController@logout');

    Route::get('daily_scrum/{id}', 'dailyScrumController@index');
    Route::post('daily_scrum', 'dailyScrumController@store');
    Route::delete('daily_scrum/{id}', 'dailyScrumController@delete');
});
