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
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Search\All_index;

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

// Handles the Laravel Scout Extended search
Route::get('search', function (Request $request) {
    $results = All_index::search($request->search)->get();
    
    $search_query = ((string)($request->search));
    $articles = array();
    $profiles_users = array();

    foreach($results as $result){
        switch (get_class($result)) {
            case 'App\Article':
                array_push($articles, $result);
                break;
            case 'App\Profile':
            // Unless break is found it will go on executing the code, it's an alternative for an 'OR' condition.
            case 'App\User':
                array_push($profiles_users, $result);
                break;
        }
    }

    // Converting the two associative arrays back into Laravel collections...
    // ...For the purpose of using pagination which doesn't work on arrays.
    $articles = collect($articles)->map(function ($article) {
        return (object) $article;
    });
    // $page = 1;
    // $perPage = 5;
    // $articles = new LengthAwarePaginator(
    //     $articles->forPage($page, $perPage),
    //     $articles->count(),
    //     $perPage,
    //     $page,
    //     ['path' => url()->current()]
    // );

    $profiles_users = collect($profiles_users)->map(function ($profile_user) {
        return (object) $profile_user;
    });
    //$profiles_users->paginate(5);

    return view("search.search", compact("articles", "profiles_users", "search_query"));
});