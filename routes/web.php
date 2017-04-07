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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',"ProductController@index");
Route::post('/login', "ProductController@postLogin");

Route::get('/product',"ProductController@getProduct");
Route::get('api/pages',"ProductController@apiGetPages");
Route::get('api/product_category','ProductController@apiCategory');
Route::post('api/submitLinks','ProductController@submitLinks');