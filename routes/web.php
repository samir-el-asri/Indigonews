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

Route::get('/', "ArticlesController@index");

Auth::routes();

Route::get('/home', 'ArticlesController@index')->name('home');

Route::resource('articles', 'ArticlesController');
Route::resource('profiles', 'ProfilesController');
Route::resource('comments', 'CommentsController');
Route::resource('conversations', 'ConversationsController');
Route::resource('messages', 'MessagesController');

// Complete Profile Registration Page
Route::get('profiles/{profile}/complete_registration', function (App\Profile $profile) {
    return view('profiles.complete_registration', compact("profile"));
});

// Pulls up category-specific articles only
Route::get('categories/{category}', function (App\Category $category) {
    $category = App\Category::find($category->id);
    $articles = $category->articles;
    return view("articles.index", compact("articles"));
});

// Handles the deletion (excluding) of conversations
Route::post('exclude/{conversation}', 'ExcludesController@store');

// Handles the following/unfollowing of profiles by users
Route::post('follow/{profile}', 'FollowsController@store');

// Handles the liking/disliking of articles by profiles
Route::post('like/{article}', 'LikesController@store');