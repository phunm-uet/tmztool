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

Route::group(['prefix' => "product"],function(){
	Route::get('/login',"ProductController@index");
	Route::post('/login', "ProductController@postLogin");
	Route::get('hd', 'ProductController@hd');	
});



Auth::routes();

/**
 * Route cho marketing
 */
Route::group(['prefix' => 'marketing','namespace' => "Marketing"], function() {

    Route::group(['middleware' => 'tokenfb'], function() {
        
    });

    
    Route::get("/import_fb",['as' => 'import_fb',"uses" => "AutoAdsController@import_fb"]);
    Route::get("/ads",['as' => 'ads',"uses" => "AutoAdsController@ads","middleware" => "tokenfb"]);
    Route::get("/config",['as' => 'config',"uses" => "ConfigController@index"]);
    Route::post("/config",['as' => 'postConfig',"uses" => "ConfigController@setConfig"]);

    Route::get('/product',["as" => "import_fb","uses" => "ProductController@getProduct"]);
    Route::get('api/pages',"ProductController@apiGetPages");
    Route::get('api/product_category','ProductController@apiCategory');
    Route::post('api/submitLinks','ProductController@submitLinks');
    Route::post('api/createcollection','ProductController@creatCollection');
    Route::get('api/getcollection', 'ProductController@getCollection');
    Route::get('/{name?}',"IndexController@index");

});
Route::get('/home', 'HomeController@index');

// Section for api
Route::group(['prefix' => 'api','namespace' => "Api"], function() {
	// API for marketing department
   Route::group(['prefix' => 'marketing'], function() {
      Route::get('index', "AdsDropShipController@index");
      Route::post("image_link","AdsDropShipController@getImageLink");
      Route::post("submitAds","AdsDropShipController@submitAds");
   });
});