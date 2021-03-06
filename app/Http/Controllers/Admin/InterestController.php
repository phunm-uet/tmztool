<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Niche;
use App\Interest;
use App\Page;
use Session;
use DB;
class InterestController extends Controller
{
    public function index(Request $request)
    {
    	$action = $request->action_name;
    	if(method_exists($this,$action)){
    		return $this->$action($request);
    	} else {
    		return redirect()->route('interest-home');
    	}
    }
    /**
     * Function show list Interest in db
     * @return [view]   view list interest
     */
    
    public function _list(Request $request)
    {
    	$niches = Niche::all();
    	$interests = Interest::all();
        $pages = Page::all();
    	return view('admin.interest.interest')->with(['interests' => $interests,'niches' => $niches,'pages' => $pages]);
    }

    /**
     * function them interest
     * @param Request $request [description]
     */
    
    public function add(Request $request)
    {
    	if($request->isMethod('post')){
            $pages = $request->pages;
	    	$interest = new Interest([
	    			'name' => $request->name_interest,
	    			'num_audience' => $request->num_audience,
	    			'niche_id' => $request->niche,
	    			'targeting' =>$request->targeting
	    		]);


	    	if($interest->save()){
                $interest_id = $interest->id;
                foreach($pages as $page)
                {
                    Interest::getInterestPage()->insert(['page_id' => $page,'interest_id' => $interest_id]);
                }
	    		$request->session()->flash('success', '1');
	    	} else {
	    		$request->session()->flash('fail', '1');
	    	}
	    	return redirect()->back();		
    	} else {
    		return redirect()->back();
    	}
    }

    /**
     * Function edit interest
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	$id = $request->id;
    	$interest = Interest::find($id);
    	$niches = Niche::all();
        $pages = Page::all();
    	return view("admin.interest.edit")->with(['interest' => $interest,'niches' => $niches,'pages' => $pages]);
    }

    /**
     * Update Interest
     * @return [json]
     */
    public function update(Request $request)
    {
    	$id = $request->id;
    	$interest = Interest::find($id);
        $interest->name = $request->name;
        $interest->num_audience = $request->num_audience;
        $interest->niche_id = $request->niche_id;
        $interest->targeting = $request->targeting;
        Interest::getInterestPage()->where('interest_id',$id)->delete();
        $pages = $request->pages;
        foreach($pages as $page)
        {
            Interest::getInterestPage()->insert(['page_id' => $page,'interest_id' => $id]);
        }
    	if($interest->save())
    	{
    		$request->session()->flash("success",1);
    	} else {
    		$request->session()->flash("fail",1);
    	}
    	
    	return redirect()->back();
    }

    public function delete(Request $request)
    {
    	$id = $request->id;

    	$interest = Interest::find($id);
    	$interest->delete();
    }
}
