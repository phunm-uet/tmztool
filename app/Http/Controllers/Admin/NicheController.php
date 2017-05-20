<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Niche;
class NicheController extends Controller
{
    public function index(Request $request)
    {
    	$action = $request->action_name;
    	if(method_exists($this,$action)){
    		return $this->$action($request);
    	} else {
    		return redirect()->route('niche-home');
    	}    	
    }

    public function _list(Request $request)
    {
    	$niches = Niche::all();
    	return view('admin.niche.list')->with(['niches' => $niches]);
    }

    public function edit(Request $request)
    {
    	if($request->isMethod('post')){
    		if(isset($request->id) && $request->id){
	    		$niche = Niche::find($request->id);
	    		$niche->name = $request->niche_name;			
    		} else {
    			$niche = new Niche();
    			$niche->name = $request->niche_name;
    		}

			if($niche->save())
			{
				$request->session()->flash('success', 1);
			} else {
				$request->session()->flash('fail', 1);
			}  
    		return redirect()->back();

    	} else {
    		return redirect()->route('niche-home');
    	}
    	
    }


    public function delete(Request $request)
    {
    	$id = $request->id;
    	$niche = Niche::find($id);
    	$niche->delete();
    	return response()->json(['success' => 1]);
    }
}
