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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');


Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider')->name('facebook.login');

Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderFacebookCallback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/secure', 'ProfileController@secure')->name('secure');

    Route::get('/newpost', 'PostController@newPost')->name('new.post');
    Route::get('/schedule/group/{group}', 'PostController@showScheduledPostsGroup')->name('schedule.post');
    Route::get('/post/delete/{post}', 'PostController@destroyPost')->name('post.destroy');
    Route::post('/posts/update/{post}', 'PostController@update')->name('post.update');
    Route::put('/posts/edit/{schedulePost}', 'PostController@editPost')->name('post.edit');
    Route::post('/shedule/post', 'PostController@storeSchedulePost')->name('post.store');

    Route::get('/attachment/delete/{instance}', 'AttachmentController@destroy')->name('attachment.destroy');

    Route::get('/mygroups', 'GroupController@myGroups')->name('my.group');
    Route::post('/group/store', 'GroupController@storeGroup')->name('store.group');
    Route::get('/group/delete/{group}/{category}', 'GroupController@destroyGroup')->name('group.destroy');
    Route::get('/group/disable/{group}', 'GroupController@disableGroup')->name('group.disable');
    Route::get('/group/refresh', 'GroupController@refreshGroup')->name('group.list.refresh');

    Route::get('/search/content', 'CategoryController@searchContent')->name('search.content');
    Route::post('/category', 'CategoryController@storeCategory')->name('store.category');
    Route::get('/category/delete/{category}', 'CategoryController@destroy')->name('category.destroy');
    Route::get('/category/{category}', 'CategoryController@showCategory')->name('category.show');
    Route::get('/category/custom/{category}', 'CategoryController@showCustomCategory')->name('custom.category.show');
});