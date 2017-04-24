<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Niche;
use App\Interest;
use Session;

class InterestController extends Controller
{
    public function index(Request $request)
    {
    	$action = $request->action_name;
    	if(method_exists($this,$action)){
    		return $this->$action($request);
    	} else {
    		return $this->list($request);
    	}
    }

    public function list(Request $request)
    {
    	$niches = Niche::all();
    	$interests = Interest::all();
    	return view('admin.interest.interest')->with(['interests' => $interests,'niches' => $niches]);
    }

    public function add(Request $request)
    {
    	if($request->isMethod('post')){
	    	$interest = new Interest([
	    			'name' => $request->name_interest,
	    			'num_audience' => $request->num_audience,
	    			'niche_id' => $request->niche,
	    			'targeting' =>$request->targeting
	    		]);
	    	if($interest->save()){
	    		$request->session()->flash('success', '1');
	    	} else {
	    		$request->session()->flash('fail', '1');
	    	}
	    	return redirect()->back();		
    	} else {
    		return redirect()->back();
    	}
    }

    public function edit(Request $request)
    {

    }

    public function delete(Request $request)
    {
    	$id = $request->id;
    	$interest = Interest::find($id);
    	$interest->delete();
    }
}
