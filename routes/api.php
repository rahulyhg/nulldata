<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('workers', 'Api\WorkerController@index');

// endpoints for workers
Route::post('worker', 'Api\WorkerController@store'); // Create
Route::get('worker/{id}', 'Api\WorkerController@show'); // Read
Route::post('worker/{id}', 'Api\WorkerController@update'); // Update
Route::delete('worker/{id}', 'Api\WorkerController@destroy'); // Delete

// endpoints skills for workers
Route::post('worker/{id}/skill', 'Api\SkillController@store'); // Create
Route::delete('worker/skill/{id}', 'Api\SkillController@destroy'); // Delete
