<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::resource('users', 'App\Http\Controllers\UserController');
Route::prefix('auth')->group(function() {
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('/reset_password', 'App\Http\Controllers\AuthController@reset_password');
    Route::post('/reset_password/{token}', 'App\Http\Controllers\AuthController@confirmation_token');
});

Route::prefix('posts')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\PostsController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\PostsController@show');
    Route::post('/create', 'App\Http\Controllers\PostsController@store');
    Route::delete('/delete/{id}', 'App\Http\Controllers\PostsController@destroy');
    Route::patch('/update/{id}', 'App\Http\Controllers\PostsController@update');
    Route::get('/{id}/categories', 'App\Http\Controllers\CategoriesController@get_post_categories');
});

Route::prefix('categories')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\CategoriesController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\CategoriesController@show');
    Route::post('/create', 'App\Http\Controllers\CategoriesController@store');
    Route::delete('/delete/{id}', 'App\Http\Controllers\CategoriesController@destroy');
    Route::patch('/updae/{id}', 'App\Http\Controllers\CategoriesController@update');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
