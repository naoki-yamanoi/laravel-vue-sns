<?php

use Illuminate\Support\Facades\Auth;
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

// 一覧
Route::get('/', 'ArticleController@index')->name('articles.index');

// 認証
Auth::routes();

// google認証
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}');
});

// 記事クラッド
Route::resource('/articles', 'ArticleController')->except(['index', 'show'])->middleware('auth');
Route::resource('/articles', 'ArticleController')->only(['show']);

// 記事関係
Route::prefix('articles')->name('articles.')->group(function ()
{
    // いいね
    Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
    // いいね解除
    Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});

// タグ検索結果表示
Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

// ユーザー関係
Route::prefix('users')->name('users.')->group(function ()
{
    // マイページ
    Route::get('/{name}', 'UserController@show')->name('show');
    // マイページのいいねタブ
    Route::get('/{name}/likes', 'UserController@likes')->name('likes');
    // フォローユーザー表示
    Route::get('/{name}/followings', 'UserController@followings')->name('followings');
    // フォロワー表示
    Route::get('/{name}/followers', 'UserController@followers')->name('followers');
    // ログインユーザーのみ
    Route::middleware('auth')->group(function ()
    {
        // フォロー
        Route::put('/{name}/follow', 'UserController@follow')->name('follow');
        // フォロー解除
        Route::delete('/{name}/follow', 'UserController@unfollow')->name('unfollow');
    });
});
