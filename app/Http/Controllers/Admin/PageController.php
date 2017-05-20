<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use App\Niche;
use App\PageNiche;
use App\Http\Controllers\Marketing\FacebookController;
use GuzzleHttp\Client;
use Goutte\Client as GoutteClient;

class PageController extends Controller
{
    public function index(Request $request)
    {
    	$action = $request->action_name;
    	if(method_exists($this,$action)){
    		return $this->$action($request);
    	} else {
    		return redirect()->route('page-home');
    	}
    }

    public function _list(Request $request)
    {
    	$pages = Page::all();
    	$niches = Niche::all();
    	return view('admin.page.list')->with(['pages' => $pages,'niches' => $niches]);
    }

    public function edit(Request $request)
    {
        if($request->isMethod('post'))
        {
            $page_id = $request->editID;
            $niches = $request->selNiche;
            $deletedRows = PageNiche::where('page_id', $page_id)->delete();
            foreach($niches as $niche)
            {
                $test = PageNiche::where('page_id',$page_id)->where('niche_id',$niche)->get();
                if( count($test) == 0)
                {
                    $tmp = new PageNiche();
                    $tmp->page_id = $page_id;
                    $tmp->niche_id = $niche;
                    $tmp->save();
                }
            }
            return redirect()->back();
        }else{
            return redirect()->back();
        }

    }

    public function delete(Request $request)
    {
        if($request->isMethod('post'))
        {
            $page_id = $request->page_id;
            Page::find($page_id)->delete();
            PageNiche::where('page_id',$page_id)->delete();
            return redirect()->back();
        }
    }


    public function sync(Request $request)
    {
        $access_token = session('access_token');
        $fb = new FacebookController();
        $fb->setToken($access_token); 
        $options = ["fields" => "id,link,name,picture{url}","limit" => 200];
        $pages = $fb->getEdge("me/accounts",$options);
        foreach($pages as $page)
        {
            try {
                $id = $page['id'];
                $link = $page['link'];
                $link = str_replace("www","business",$link);
                $name = $page['name'];
                $url_image = $page['picture']['data']['url'];
                $page = new Page(['page_id' => $id,'url' => $link,'page_name' => $name,'url_image' => $url_image]);
                $page->save();
            } catch (\Exception $e) {
                
            }
        }
        return response()->json($pages);       
    }


    public function get_store(Request $request)
    {
        $id = $request->id;
        $url = $request->url;
        $str_cookie = session('cookie_fb');
        $client = new Client([
            'headers' => ["cookie" => $str_cookie]
        ]);
        $goutteClient = new GoutteClient();
        $goutteClient->setClient($client);
        $crawler = $goutteClient->request('GET',$url."publishing_tools/");
        $source_code = $crawler->html();
        preg_match('/store_id":"([0-9]{10,20})"/',$source_code,$matches);
        if(count($matches) == 2)
        {
            $page = Page::find($id);
            $page->store_id = $matches[1];
            $page->save();
            return response()->json(["status" => 1]);
        } else {
            return response()->json(["status" => 0]);
        }
        
    }

}
