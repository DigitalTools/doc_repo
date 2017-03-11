<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/register_article', 'ArticlesController@register');
Route::post('/register_article', 'ArticlesController@store');

Route::get('/articles', 'ArticlesController@articles')->name('articles.index');

Route::get('/article/{id}', 'ArticlesController@show');
Route::delete('/article/{id}', 'ArticlesController@destroy')->name('article.destroy');
Route::get('/article/{id}/edit', 'ArticlesController@edit')->name('article.edit');;
Route::post('/article/{id}/edit', 'ArticlesController@update')->name('article.update');;

Route::get('/articles/by_author/{author_id}', 'ArticlesController@articlesByAuthor')->name('articles.byAuthor');

Route::get('/url', 'Source\ListController@input');
Route::post('/url', 'Source\ListController@parse');
