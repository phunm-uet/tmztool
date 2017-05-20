<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Marketing\FacebookController;
use Exception;
use Session;

class ConfigController extends Controller
{
    public function index(Request $request){
    	return view("marketing.config");
    }

    public function setConfig(Request $request){
    	$errors = array();
    	$fb = new FacebookController();    	
    	if($request->accessToken != ""){
    		$accessToken = $request->accessToken;
    		$fb->setToken($accessToken);
    		try{
    			$data = $fb->getEdge("me/adaccounts");
                session()->put('access_token', $accessToken);
    		} catch(Exception $e){
    			$errors['accessToken'] = 1;
    		}
    		
    	}
        
    	if(count($errors) == 0){
    		return redirect("/marketing");
    	}
    	return redirect()->back()->withErrors($errors);
    }
}
