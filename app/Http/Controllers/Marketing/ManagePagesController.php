<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    	return view("marketing.page-detail")->with(["id" => $request->id]);
    }
}
