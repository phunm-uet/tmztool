<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Idea;
use Carbon\Carbon;
use App\Department;
use App\Niche;
use App\UserManager;
use App\Source;
use App\Field;
use Response;
use App\Http\Requests\IdeaUploadRequest;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
	/**
     * Show the idea dashboard.
     */
    public function index() 
    {
    	// Lay ngay dau tien cua thang nay
    	$now = Carbon::now();
    	$first_of_month = $now->startOfMonth()->toDateTimeString();
        $last_of_month = $now->endOfMonth()->toDateTimeString();
    	if(Auth::user()) {
    		$staff_id = Auth::user()->id;
    		$ideas = Idea::where('staff_id', $staff_id)->get();
	    	// Dem so idea approved, approved trong thang nay, pending, denied
	    	$ideas_approved = Idea::where('staff_id', $staff_id)->where('result_approve','1')->count();
	    	$ideas_approved_in_month = Idea::where('staff_id', $staff_id)
	    									->where('result_approve','1')
	    									->where('date_approve','>=',$first_of_month)
                                            ->where('date_approve','<=',$last_of_month)
	    									->count();
	    	$ideas_pending = Idea::where('staff_id', $staff_id)->where('result_approve','0')->count();	
	    	$ideas_denied = Idea::where('staff_id', $staff_id)->where('result_approve','-1')->count();		
	    	return view('ideas.idea')->with([
                    "ideas" => $ideas,
                    "ideas_approved" =>$ideas_approved,
                    "ideas_approved_in_month" => $ideas_approved_in_month,
                    "ideas_pending" => $ideas_pending,
                    "ideas_denied" => $ideas_denied
                ]);
        	} else {
        		return redirect('/');
        	}
    }

    // Return view idea form with niches
    public function getForm()
    {
    	$niches = Niche::all();

        $sources = Source::all();
        $fields = Field::all();
    	return view('ideas.form')->with([
            'niches'=>$niches,
            'sources'=>$sources,
            'fields'=>$fields
            ]);
    }

    // Post idea form and save to DB
    public function ideaUpload(Request $request)
    {
        $idea = new Idea($request->all());
        $idea->staff_id = Auth::user()->id;
        if($request->niche_id_2 != "") {
            $idea->niche_id_2 = $request->niche_id_2;
        } 
        if($request->source_id_2 != "") {
            $idea->source_id_2 = $request->source_id_2;
        } 
        if($request->source_id_3 != "") {
            $idea->source_id_3 = $request->source_id_3;
        } 
        
    	$idea->save();
    	$niches = Niche::all();
        $sources = Source::all();
        $fields = Field::all();
    	return view('ideas.form')->with([
            'status'=>'Success',
            'niches'=>$niches,
            'sources'=>$sources,
            'fields'=>$fields
            ]);
    }
    // Api get all Idea Pending
    public function apiGetIdeaPending(Request $request){
        $staff_id = Auth::user()->id;
        $ideas = Idea::with('niche1','niche2','source1','source2','source3')->where('staff_id', $staff_id)->where('result_approve', '0')->get();
        $niches = Niche::all();
        $sources = Source::all();
        return response()->json([
            "ideas" => $ideas,
            "niches" => $niches,
            "sources" => $sources
            ]);                
    }
    // Api for delete Idea used in api.php route file
    public function deleteIdea(Request $request)
    {
        $id = $request->id;
        $idea = Idea::find($id);
        if($idea->delete()){
            return response()->json(['success' => true],200);
        }
    }
    // Api for update Idea used in api.php route file
    public function updateIdea(Request $request)
    {
        $id = $request->data['id'];
        $idea = Idea::find($id);
        $idea->update($request->data);
    } 

    
}