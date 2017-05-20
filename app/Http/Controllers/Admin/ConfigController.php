<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\UserSystem;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;
class ConfigController extends Controller
{
    public function index(Request $request)
    {
    	if($request->isMethod('post'))
    	{
    		$str_cookie = $request->cookie;
            preg_match("/c_user=([0-9]{9,20});/", $str_cookie, $matches);
            if(isset($matches[1]))
            {
                $user_id = $matches[1];
            } else {
                $request->session()->flash('error', "Cookie vừa nhập không đúng");
                return redirect()->back(); 
            }
            
            $client = new Client([
                'headers' => ["cookie" => $str_cookie]
            ]);
            $goutteClient = new GoutteClient();
            $goutteClient->setClient($client);
            $crawler = $goutteClient->request('GET','https://mbasic.facebook.com/pages');
            $first_url = $crawler->filter('.bw.f>a')->eq(0)->extract('href')[0];
            $first_url = substr($first_url, 0,-8);
            $crawler = $goutteClient->request('GET','https://www.facebook.com'.$first_url.'publishing_tools');
            $source_code = $crawler->html();
            preg_match('/InstantArticlesConfig(.+?)accessToken:"(.+?)",defaultBackgroundColor/',$source_code,$matches);
            if(isset($matches[2])){
                $access_token = $matches[2];
                $request->session()->put('access_token', $access_token);
                $request->session()->put('cookie_fb', $str_cookie);
                $userSystem = new UserSystem;
                $userSystem->user_id = $user_id;
                $userSystem->cookie = $str_cookie;
                $userSystem->save();
                return redirect()->back();                
            } else {
                $request->session()->flash('error', "Cookie vừa nhập không đúng");
                return redirect()->back(); 
            }

    	} else {
            $userSystems = UserSystem::all();
    		return view('admin.config.index')->with(['userSystems' => $userSystems]);
    	}
    }


    public function getToken(Request $request)
    {
        $userId = $request->user_id;
        $userSystem = UserSystem::where('user_id',$userId)->first();
        $cookie = $userSystem->cookie;
        $client = new Client([
            'headers' => ["cookie" => $cookie]
        ]);
        $goutteClient = new GoutteClient();
        $goutteClient->setClient($client);
        $crawler = $goutteClient->request('GET','https://mbasic.facebook.com/pages');
        $first_url = $crawler->filter('.bw.f>a')->eq(0)->extract('href')[0];
        $first_url = substr($first_url, 0,-8);
        $crawler = $goutteClient->request('GET','https://www.facebook.com'.$first_url.'publishing_tools');
        $source_code = $crawler->html();
        preg_match('/InstantArticlesConfig(.+?)accessToken:"(.+?)",defaultBackgroundColor/',$source_code,$matches);
        if(isset($matches[2])){
            $access_token = $matches[2];
            $request->session()->put('access_token', $access_token);
            return response()->json(['success' => 1]);
        } else {
            return response()->json(['success' => 0]);
        }        
    }

    public function delete(Request $request)
    {
        $userId = $request->user_id;
        $userSystem = UserSystem::where('user_id',$userId)->first();
        $userSystem->delete();
        return response()->json(['success' => 1]);
    }
}
