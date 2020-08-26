<?php

use Illuminate\Support\Facades\Route;



Route::get('/{id?}', 'NewsController@index')->name('news.index');
Route::get('/news/{id}', 'NewsController@show')->name('news.show');

