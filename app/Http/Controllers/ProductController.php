<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;
use Session;
class ProductController extends Controller
{
	const BASE_FB_URL = "https://graph.facebook.com/v2.8/";
	var $client;
	public function __construct()
	{
		$this->client = new Client([
			'base_uri'=> self::BASE_FB_URL,
			'timeout' => '5.0',
			'http_errors' => false
			]);		
	}
    public function index()
    {
    	return view("login");
    }

    public function postLogin(Request $request)
    {
    	$access_token = $request->access_token;
		$res = $this->client->request('GET', 'me',["query" => "access_token=$access_token"]);
		$status = $res->getStatusCode();
		if($status == 200){
			$request->session()->put('access_token', $access_token);
			return redirect('/product');
		} else {
			return redirect()->back()->with('error',"invilad token");
		}
    }

    public function getProduct(Request $request)
    {
    	return view('product');
    	
    }

    public function apiGetPages(Request $request)
    {
    	$access_token = Session::get('access_token');
		$res = $this->client->request('GET', 'me/accounts',["query" => "access_token=$access_token&fields=name,id,picture{url}"]);
		$res = json_decode($res->getBody(),true);
		$data = $res['data'];
		return response()->json($data);
    }

    public function apiCategory(Request $request){
    	$access_token = Session::get('access_token');
    	$id = $request->id;
		$res = $this->client->request('GET', "$id/product_catalogs",["query" => "access_token=$access_token"]);
		$res = json_decode($res->getBody(),true);
		// dd($res);
		$data = $res['data'];
		return response()->json($data);		
    }

    public function getCollection(Request $request)
    {
        $access_token = Session::get('access_token');
        $store_id = $request->store_id;
        $res = $this->client->request('GET', "$store_id/commerce_store_collections",["query" => "access_token=$access_token"]);

        $res = json_decode($res->getBody(),true);
        $data = $res['data'];
        return response()->json($data);
    }

    public function creatCollection(Request $request){
        $access_token = Session::get('access_token');
        $name = $request->name;
        $store_id = $request->store_id;
        $res = $this->client->request('POST',"$store_id/commerce_store_collections",
                ["form_params" => ["name" => $name,"visibility" => "PUBLISHED","access_token" => $access_token]]); 
          
    }

    public function submitLinks(Request $request){
    	$access_token = Session::get('access_token');
        $col_id = $request->store["id"];
    	$filterOld = $request->store["filter"];
        $filterOld = json_decode($filterOld,true);
    	$links = $request->links;
    	$idCategory = $request->id_category;
    	$products = array();
    	$goutteClient = new GoutteClient();
    	foreach($links as $link){
    		$crawler = $goutteClient->request('GET', $link);
    		$link_img = "https:".$crawler->filter('.featured-image img')->eq(0)->extract('src')[0];
    		
    		$title = $crawler->filter("title")->text();
    		$price = $crawler->filter("#price-field")->text();
    		$description = $crawler->filter("#product-tabs-content")->text();
    		$price = substr($price,1);
    		$price = floatval($price) * 100;
    		$form_data = [
    				"retailer_id" => "product_".time(),
    				"brand" => "Shopify",
    				"category" => "T-shirt",
    				"checkout_url" => $link,
    				"currency" => "USD",
    				"description" => $description,
    				"image_url" => $link_img,
    				"name" => $title,
    				"price" => $price,
    				"url" => $link,
    				"access_token" => $access_token
    			];
    		$res = $this->client->request('POST',"$idCategory/products",
    			["form_params" => $form_data], ["query" => "access_token=$access_token"]);

			$res = json_decode($res->getBody(),true);
			array_push($products,["link" => $link,"id" => $res["id"],"name" => $title]);
           
            array_push($filterOld["product_item_id"]['is_any'],$res['id']);
    	}
        $filterOld = json_encode($filterOld);
        $res = $this->client->request('POST',"$col_id",
                ["form_params" => ["filter" => $filterOld,"access_token" => $access_token]], ["query" => "access_token=$access_token"]);
    	return response()->json($products);
    }
}
