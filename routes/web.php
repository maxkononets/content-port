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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('facebook.login');

Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderFacebookCallback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/secure', 'ProfileController@secure')->name('secure');

    Route::get('/newpost', 'PostController@newPost')->name('new.post');

    Route::get('/mygroups', 'GroupController@myGroups')->name('my.group');

    Route::get('/search/content', 'CategoryController@searchContent')->name('search.content');
    Route::post('/category', 'CategoryController@store')->name('store.category');
    Route::get('/category/{category}', 'CategoryController@destroy')->name('category.destroy');
});