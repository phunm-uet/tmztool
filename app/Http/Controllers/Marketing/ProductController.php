<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    	$access_token = Session::get('accessTokenProduct');
		$res = $this->client->request('GET', 'me/accounts',["query" => "access_token=$access_token&fields=name,id,picture{url}"]);
		$res = json_decode($res->getBody(),true);
		$data = $res['data'];
		return response()->json($data);
    }

    public function apiCategory(Request $request){
    	$access_token = Session::get('accessTokenProduct');
    	$id = $request->id;
		$res = $this->client->request('GET', "$id/product_catalogs",["query" => "access_token=$access_token"]);
		$res = json_decode($res->getBody(),true);
		$data = $res['data'];
		return response()->json($data);		
    }

    public function getCollection(Request $request)
    {
        $access_token = Session::get('accessTokenProduct');;
        $store_id = $request->store_id;
        $res = $this->client->request('GET', "$store_id/commerce_store_collections",["query" => "access_token=$access_token"]);

        $res = json_decode($res->getBody(),true);
        $data = $res['data'];
        return response()->json($data);
    }

    public function creatCollection(Request $request){
        $access_token = Session::get('accessTokenProduct');;
        $name = $request->name;
        $store_id = $request->store_id;
        $parrams = [
            "name" => $name,
            "visibility" => "PUBLISHED",
            "filter" => "{\"product_item_id\":{\"is_any\":[]}}",
            "access_token" => $access_token
        ];
        $res = $this->client->request('POST',"$store_id/commerce_store_collections",
                ["form_params" => $parrams]);
    }

    public function getTrippleLink($goutteClient,$link){
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
            ];

            return $form_data;
    }

    public function getFanprint($goutteClient,$link){
        $crawler = $goutteClient->request('GET', $link);
        $link_img = $crawler->filterXpath('//meta[@property="og:image"]')->attr('content');
        $title = $crawler->filterXpath('//meta[@property="og:title"]')->attr('content');
        $price = $crawler->filterXpath('//meta[@property="og:price:amount"]')->attr('content');
        $description = $crawler->filterXpath('//meta[@property="og:description"]')->attr('content');
        $price = floatval($price) * 100;

        $form_data = [
                "retailer_id" => "product_".time(),
                "brand" => "Platform",
                "category" => "T-shirt",
                "checkout_url" => $link,
                "currency" => "USD",
                "description" => $description,
                "image_url" => $link_img,
                "name" => $title,
                "price" => $price,
                "url" => $link,
            ];

            return $form_data;             
    }

    public function getSunfrog($goutteClient,$link){
        $crawler = $goutteClient->request('GET', $link);
        $link_img = $crawler->filterXpath('//meta[@property="og:image"]')->attr('content');
        $title = $crawler->filterXpath('//meta[@property="og:title"]')->attr('content');
        $price = $crawler->filter('#shirtTypes option:selected')->text();
        $price = substr($price,-5);
        $description = $crawler->filterXpath('//meta[@property="og:description"]')->attr('content');
        $price = floatval($price) * 100;
        $form_data = [
                "retailer_id" => "product_".time(),
                "brand" => "Platform",
                "category" => "T-shirt",
                "checkout_url" => $link,
                "currency" => "USD",
                "description" => $description,
                "image_url" => $link_img,
                "name" => $title,
                "price" => $price,
                "url" => $link,
            ];

            return $form_data;             
    }

     public function getGL($goutteClient,$link){
        $crawler = $goutteClient->request('GET', $link);
        $link_img = $crawler->filterXpath('//meta[@property="og:image"]')->attr('content');
        $title = $crawler->filterXpath('//meta[@property="og:title"]')->attr('content');
        $price = $crawler->filterXpath('//meta[@property="og:price:amount"]')->attr('content');
        $description = $crawler->filterXpath('//meta[@property="og:description"]')->attr('content');
        $price = floatval($price) * 100;
        $form_data = [
                "retailer_id" => "product_".time(),
                "brand" => "Platform",
                "category" => "T-shirt",
                "checkout_url" => $link,
                "currency" => "USD",
                "description" => $description,
                "image_url" => $link_img,
                "name" => $title,
                "price" => $price,
                "url" => $link,
            ];
            return $form_data;             
    }    

    public function submitLinks(Request $request){
    	$access_token = Session::get('accessTokenProduct');
        $col_id = $request->store["id"];
    	$filterOld = $request->store["filter"];
        $filterOld = json_decode($filterOld,true);
    	$links = $request->links;
    	$idCategory = $request->id_category;
    	$products = array();
    	$goutteClient = new GoutteClient();
        $header = [
        "User-Agent" => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36",
        "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Encoding" => "gzip, deflate, sdch"
        ];
        $guzzleClient = new Client(["headers" => $header]);

        $goutteClient->setClient($guzzleClient);

    	foreach($links as $link){
            if(strpos($link, "https://triplewiser.com/") > -1){
                $form_data = $this->getTrippleLink($goutteClient,$link);
            }
            if(strpos($link, "https://www.fanprint.com/") > -1){
                $form_data = $this->getFanprint($goutteClient,$link);
            }
            if(strpos($link, "https://www.sunfrog.com/") > -1){
                $form_data = $this->getSunfrog($goutteClient,$link);
            }
            if(strpos($link, "http://teemazing.co/") > -1){
                $form_data = $this->getGL($goutteClient,$link);
            }           
            if(isset($form_data)){
                // Create Product_Group First 
                $product_group_params = [
                   "retailer_id" => "product_".time(),
                    "access_token" => $access_token
                ];
                $res1 = $this->client->request('POST',"$idCategory/product_groups",
                    ["form_params" => $product_group_params]);
                $res1 = json_decode($res1->getBody(),true);
                $product_group_id = $res1['id'];
                $form_data['access_token'] = $access_token;

                $res = $this->client->request('POST',"$product_group_id/products",
                    ["form_params" => $form_data]);
                $res = json_decode($res->getBody(),true);
                array_push($products,["link" => $link,"id" => $res["id"],"name" => $form_data['name']]);
               
                array_push($filterOld["product_item_id"]['is_any'],$res['id']);                 
            }

    	}

        $filterOld = json_encode($filterOld);
        $res = $this->client->request('POST',"$col_id",
                ["form_params" => ["filter" => $filterOld,"access_token" => $access_token]], ["query" => "access_token=$access_token"]);
    	return response()->json($products);
    }

    public function hd(){
        return view("hd");
    }
}
