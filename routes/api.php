<?php

use App\Http\Controllers\GeneralController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\VolunteerController;
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

Route::post("user/login", "UserController@login");
Route::post("user/find-token", "UserController@findByToken");

Route::post("user/register-volunteer", "UserController@registerVolunteer");
Route::post("user/update-volunteer", "UserController@updateVolunteer");
Route::post("user/register-institution", "UserController@registerInstitution");
Route::post("user/update-institution", "UserController@updateInstitution");
Route::post("user/update-password", "UserController@updatePassword");
Route::post("interest/insert", "InterestController@insert");
Route::post("user/upload-image", "UserController@uploadImage");

Route::post("institution/find-by-locale", "InstitutionController@getInstitutionsByLocale");
Route::post("volunteer/find-pending-solicitations", "VolunteerController@getVolunteersBySolicitationPending");
Route::post("volunteer/find-approved-solicitations", "VolunteerController@getVolunteersBySolicitationApproved");
Route::post("solicitation/status-solicitation", "SolicitationController@updateStatusSolicitation");
Route::post("institution/search-institutions", "InstitutionController@searchInstitutions");

Route::post("work/create", "WorkController@create");
Route::patch("work/update/{work}", "WorkController@update");
Route::get("work/find-all", "WorkController@findAll");
Route::get("work/find/{work}", "WorkController@findById");
Route::delete("work/delete/{work}", "WorkController@destroy");
Route::post("work/find-by-institution", "WorkController@findByInstitutionId");

Route::post("solicitation/find-by-user-and-institutuon", "SolicitationController@findSolicitationPendingByUserAndInstitution");
Route::post("solicitation/find-by-volunteer", "SolicitationController@findByVolunteer");

Route::post("calendar/find-by-volunteer", "CalendarController@getCalendarByVolunteerId");
