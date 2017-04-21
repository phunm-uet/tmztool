<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;
use Exception;
class FacebookController extends Controller
{
    const BASE_FB_URL = "https://graph.facebook.com/v2.8/";
    var $client;
    var $accessToken;
    /**
     * [__construct Init FB API
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri'=> self::BASE_FB_URL,
            'timeout' => '5.0',
            'http_errors' => false
            ]);     
    }
    /**
     * Set AccessToken for Session
     * @param [String] $accessToken accessToken
     */
    public function setToken($accessToken){
    	$this->accessToken = $accessToken;
    }

    /**
     * get Data From Node in Graph API
     * @param  [string] $path string to get API data
     * @return [json]       data from Node Graph Facebook
     */
    public function getNode($path,$options=null){
        $options["access_token"] = $this->accessToken;
    	$res = $this->client->request('GET', $path,["query" => $options]);
    	$status = $res->getStatusCode();
    	if($status == 200){
    		$res = json_decode($res->getBody(),true);
    		return $res;
    	} else {
    		throw new Exception("Error");
    	}
    }

    /**
     * Get Data From Edge in Graph API
     * @param  [string] $path  String to get API data
     * @return [json]       data from API
     */
    public function getEdge($path,$options=null){
        $options["access_token"] = $this->accessToken;
    	$res = $this->client->request('GET', $path,["query" => $options]);
    	$status = $res->getStatusCode();
    	if($status == 200){
    		$res = json_decode($res->getBody(),true);
    		return $res["data"];
    	} else {
    		throw new Exception("Error");
    	}    	
    }
    /**
     * Post Method to API Facebook
     * @param  [String] $path      Path URI request
     * @param  [array] $form_data array send to server
     * @return [json]           result of post Request
     */
    public function postData($path,$form_data){
        // Add Access Token to form_data
        $form_data["access_token"] = $this->accessToken;
        $res = $this->client->request('POST',$path,
                ["form_params" => $form_data]);
        $status = $res->getStatusCode();
        
        if($status == 200){
           return json_decode($res->getBody(),true); 
        } else {
            return null;
        }
    }

    public function bathRequest($path,$form_data){
        $form_data["access_token"] = $this->accessToken;
        $res = $this->client->request('POST',$path,
                ["form_params" => $form_data]);

        $status = $res->getStatusCode();
        if($status == 200){
           return json_decode($res->getBody(),true); 
        } else {
            return null;
        }

    }
}
