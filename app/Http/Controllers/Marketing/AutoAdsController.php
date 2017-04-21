<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PageManager;
use App\TypeProduct;
use App\Niche;
class AutoAdsController extends Controller
{
    public function ads(){
    	return view("marketing.ads");
    }
}
