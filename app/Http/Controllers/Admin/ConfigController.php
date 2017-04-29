<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
    	if($request->isMethod('post'))
    	{
            if($request->session()->has('access_token'))
            {
                return redirect("/admin");
            }
    		$str_cookie = $request->cookie;
    		$arr_cookie = explode("; ", $str_cookie);
            $client = new Client([
                'headers' => ["cookie" => $str_cookie]
            ]);
            $goutteClient = new GoutteClient();
            $goutteClient->setClient($client);
            $crawler = $goutteClient->request('GET','https://mbasic.facebook.com/pages');
            // dd($crawler->html());
            $first_url = $crawler->filter('.bu.f>a')->eq(0)->extract('href')[0];
            $crawler = $goutteClient->request('GET','https://www.facebook.com'.$first_url.'publishing_tools');
            $source_code = $crawler->html();
            preg_match('/InstantArticlesConfig(.+?)accessToken:"(.+?)",defaultBackgroundColor/',$source_code,$matches);
            $access_token = $matches[2];
            $request->session()->put('access_token', $access_token);
    	} else {
    		return view('admin.config.index');
    	}
    }
}
