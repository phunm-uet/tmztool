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

    Route::get("/import_fb",['as' => 'import_fb',"uses" => "AutoAdsController@import_fb"]);
    Route::get("/ads",['as' => 'ads',"uses" => "AutoAdsController@ads","middleware" => "tokenfb"]);
    Route::get("/config",['as' => 'config',"uses" => "ConfigController@index"]);
    Route::post("/config",['as' => 'postConfig',"uses" => "ConfigController@setConfig"]);

    // route for product
    Route::get('/product',["as" => "import_fb","uses" => "ProductController@getProduct","middleware" => "tokenfb"]);
    Route::get('api/pages',"ProductController@apiGetPages");
    Route::get('api/product_category','ProductController@apiCategory');
    Route::post('api/submitLinks','ProductController@submitLinks');
    Route::post('api/createcollection','ProductController@creatCollection');
    Route::get('api/getcollection', 'ProductController@getCollection');

    // Manage Page
    Route::get("pages",["as" => "pages","uses" => "ManagePagesController@index","middleware" => "tokenfb"]);
    Route::get("page/{id}",['as' => 'page-detail','uses' => 'ManagePagesController@detail',"middleware" => "tokenfb"]);
    Route::get('/{name?}',"IndexController@index");

});


// Route for admin
Route::group(['prefix' => 'admin','namespace' => 'Admin'],function(){
    Route::get('/', 'IndexController@index');
    Route::get("interest",['as' => 'interest-home','uses' => "InterestController@list"]);
    Route::any("interest/{action_name?}","InterestController@index");
    Route::get('niche',['as' => 'niche-home','uses' => 'NicheController@list']);
    Route::any('niche/{action_name?}',"NicheController@index");
    Route::get('page',['as' => 'page-home','uses' => 'PageController@list']);
    Route::any('page/{action_name?}',['uses' => 'PageController@index']);
    Route::any('niche/{action_name?}',"NicheController@index");    
    Route::any('/config',"ConfigController@index");
});

Route::get('/home', 'HomeController@index');

// Section for api
Route::group(['prefix' => 'api','namespace' => "Api"], function() {
	// API for marketing department
   Route::group(['prefix' => 'marketing'], function() {
      Route::get('index', "AdsDropShipController@index");
      Route::post("image_link","AdsDropShipController@getImageLink");
      Route::post("submitAds","AdsDropShipController@submitAds");
      Route::get('pages','ManagePagesController@getPages');
      Route::get("getdatas","ManagePagesController@getDatas");
      Route::get('get-top-posts','ManagePagesController@topPosts');
      Route::get('thong-so-ads','ManagePagesController@getDataForSetAds');
      Route::post("sbAds",'ManagePagesController@submitAds');
   });
});