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

// All routes for idea
Route::group(['prefix' => 'idea','middleware' => ['idea','auth']], function () {
    Route::get('/', 'IdeaController@index');
    Route::get('/form', 'IdeaController@getForm');
    Route::post('/form', ['as'=>'ideaUpload', 'uses'=>'IdeaController@ideaUpload']);
    Route::get('/pending', function(){
      return view('ideas/pending');
    });
    Route::post('/pending', ['as'=>'editIdeaPending', 'uses'=>'IdeaController@editIdeaPending']);
});



Auth::routes();

/**
 * Route cho marketing
 */
Route::group(['prefix' => 'marketing','namespace' => "Marketing","middleware" => ['auth','marketing']], function() {

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
    Route::get('/{name?}',['as' => 'marketing-home','uses' => "IndexController@index"]);

});


// Route for admin
Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware' => ['auth','admin']],function(){
    Route::get('/', ["as" => "admin-home",'uses' => 'IndexController@index']);
    Route::get("interest",['as' => 'interest-home','uses' => "InterestController@_list"]);
    Route::any("interest/{action_name?}","InterestController@index");
    Route::get('niche',['as' => 'niche-home','uses' => 'NicheController@_list']);
    Route::any('niche/{action_name?}',"NicheController@index");
    Route::get('page',['as' => 'page-home','uses' => 'PageController@_list']);
    Route::any('page/{action_name?}',['uses' => 'PageController@index']);
    Route::any('niche/{action_name?}',"NicheController@index");    
    Route::any('/config',['as' => 'admin-config','uses' => 'ConfigController@index']);
    Route::post('/config/get-token','ConfigController@getToken');
    Route::post('/config/delete-user','ConfigController@delete');
});

Route::get('/home', 'HomeController@index');

// Section for api
Route::group(['prefix' => 'api','namespace' => "Api", 'middleware' => 'auth'], function() {
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
      Route::get('country',"AdsDropShipController@getCountry");
      Route::post('upload',"AdsDropShipController@uploadImage");
      Route::get('ads-running',"ManagePagesController@getCampaignRuning");
      Route::get('get-campaign-info',"ManagePagesController@getCampaignInfo");
      Route::post('pause-ads',"ManagePagesController@pauseAds");
   });
});