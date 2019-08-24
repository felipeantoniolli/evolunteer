<?php

use App\Http\Controllers\GeneralController;
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

Route::post("user/create", "UserController@create");
Route::patch("user/update/{user}", "UserController@update");
Route::get('user/find-all', "UserController@findAll");
Route::get('user/find/{user}', "UserController@findById");
Route::delete('user/delete/{user}', "UserController@destroy");

Route::post("volunteer/create", "VolunteerController@create");
Route::patch("volunteer/update/{volunteer}", "VolunteerController@update");
Route::get("volunteer/find-all", "VolunteerController@findAll");
Route::get("volunteer/find/{volunteer}", "VolunteerController@findById");
Route::delete("volunteer/delete/{volunteer}", "VolunteerController@destroy");

Route::post("institution/create", "InstitutionController@create");
Route::patch("institution/update/{institution}", "InstitutionController@update");
Route::get("institution/find-all", "InstitutionController@findAll");
Route::get("institution/find/{institution}", "InstitutionController@findById");
Route::delete("institution/delete/{institution}", "InstitutionController@destroy");

Route::post("rating/create", "RatingController@create");
Route::patch("rating/update/{rating}", "RatingController@update");
Route::get("rating/find-all", "RatingController@findAll");
Route::get("rating/find/{rating}", "RatingController@findById");
Route::delete("rating/delete/{rating}", "RatingController@destroy");

Route::post("work/create", "WorkController@create");
Route::patch("work/update/{work}", "WorkController@update");
Route::get("work/find-all", "WorkController@findAll");
Route::get("work/find/{work}", "WorkController@findById");
Route::delete("work/delete/{work}", "WorkController@destroy");

Route::post("solicitation/create", "SolicitationController@create");
Route::patch("solicitation/update/{solicitation}", "SolicitationController@update");
Route::get("solicitation/find-all", "SolicitationController@findAll");
Route::get("solicitation/find/{solicitation}", "SolicitationController@findById");
Route::delete("solicitation/delete/{solicitation}", "SolicitationController@destroy");
