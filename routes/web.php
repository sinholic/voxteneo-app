<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::group(["middleware" => "api-auth", "namespace" => "Admin"], function () {
    Route::get('/', 'DashboardController@index')->name('index');
    Route::group([
        "prefix" => "application"
    ], function () {
            // Organizer
            Route::resource('organizers', \OrganizerController::class);
            // Sport
            Route::resource('sports', \SportController::class);
            // User
            Route::resource('users', \UserController::class);
    });
});
