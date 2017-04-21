<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $table = "designs";

    public function idea_design()
    {
    	return $this->belongsTo('App\IdeaDesign','idea_design_id');
    }
}
