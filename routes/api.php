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
    Route::get('/show/{id}/ids', 'App\Http\Controllers\PostsController@show_user_posts_ids');
    Route::post('/create', 'App\Http\Controllers\PostsController@store');
    Route::delete('/delete/{id}', 'App\Http\Controllers\PostsController@destroy');
    Route::patch('/update/{id}', 'App\Http\Controllers\PostsController@update');
    Route::get('/{id}/categories', 'App\Http\Controllers\CategoriesController@get_post_categories');
    Route::post('/{id}/like', 'App\Http\Controllers\LikesController@create_like_on_post');
    Route::get('/{id}/like', 'App\Http\Controllers\LikesController@get_likes_on_post');
    Route::get('/show/{id}/like', 'App\Http\Controllers\LikesController@show_user_likes');
    Route::delete('/{id}/like', 'App\Http\Controllers\LikesController@delete_like_on_post');
});

Route::prefix('categories')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\CategoriesController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\CategoriesController@show');
    Route::post('/create', 'App\Http\Controllers\CategoriesController@store');
    Route::delete('/delete/{id}', 'App\Http\Controllers\CategoriesController@destroy');
    Route::patch('/update/{id}', 'App\Http\Controllers\CategoriesController@update');
});

Route::prefix('comments')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\CommentsController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\CommentsController@show');
    Route::get('/show/{id}/post', 'App\Http\Controllers\CommentsController@show_comments_to_post');
    Route::get('/show/{id}/post/authors', 'App\Http\Controllers\CommentsController@get_authors_ids_for_post');
    Route::post('/{id}/create', 'App\Http\Controllers\CommentsController@store');
    Route::delete('/delete/{id}', 'App\Http\Controllers\CommentsController@destroy');
    Route::patch('/update/{id}', 'App\Http\Controllers\CommentsController@update');
    Route::post('/{id}/like', 'App\Http\Controllers\LikesController@create_like_on_comment');
    Route::get('/{id}/like', 'App\Http\Controllers\LikesController@get_likes_on_comment');
    Route::delete('/{id}/like', 'App\Http\Controllers\LikesController@delete_like_on_comment');
    Route::get('/show/{id}/like', 'App\Http\Controllers\LikesController@show_user_likes_comment');
});

Route::prefix('users')->group(function() {
    Route::get('/show_all', 'App\Http\Controllers\UserController@index');
    Route::get('/show/{id}', 'App\Http\Controllers\UserController@show');
    Route::post('/create', 'App\Http\Controllers\UserController@store');
    Route::delete('/delete/{id}', 'App\Http\Controllers\UserController@destroy');
    Route::patch('/update/{id}', 'App\Http\Controllers\UserController@update');
    Route::post('/upload_avatar', 'App\Http\Controllers\UserController@upload_avatar');
    Route::get('/show_avatar/{id}', 'App\Http\Controllers\UserController@download_user_avatar');
});

Route::prefix('favorites')->group(function() {
    Route::get('/show/{id}', 'App\Http\Controllers\FavoritesController@show_user_favorites');
    Route::get('/show/{id}/ids', 'App\Http\Controllers\FavoritesController@get_user_fav_ids');
    Route::post('/add/{id}', 'App\Http\Controllers\FavoritesController@add_to_favorites');
    Route::delete('/delete/{id}', 'App\Http\Controllers\FavoritesController@delete_from_favorites');
    Route::get('/show_posts', 'App\Http\Controllers\FavoritesController@show_only_favorites');
});
Route::prefix('subscriptions')->group(function() {
    Route::get('/show/{id}', 'App\Http\Controllers\SubscriptionsController@show_user_subscriptions');
    Route::get('/show/{id}/ids', 'App\Http\Controllers\SubscriptionsController@get_user_subs_ids');
    Route::post('/add/{id}', 'App\Http\Controllers\SubscriptionsController@add_to_subscriptions');
    Route::delete('/delete/{id}', 'App\Http\Controllers\SubscriptionsController@delete_from_subscriptions');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
