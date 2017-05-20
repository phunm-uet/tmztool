<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Marketing\FacebookController;

class ManagePagesController extends Controller
{
	/**
	 *	View quan ly pages
	 * @return [view] quan ly pages
	 */
    public function index(){
    	return view("marketing.pages");
    }

    public function detail(Request $request)
    {
        $accessToken = $request->session()->get('access_token');
        $id = $request->id;
        $fb = new FacebookController();
        $fb->setToken($accessToken);
        $pageInfo = $fb->getNode("$id");
        $name = $pageInfo['name'];
    	return view("marketing.page-detail")->with(["id" => $request->id,"name" => $name]);
    }
}
