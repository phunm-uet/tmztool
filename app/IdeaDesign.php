<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdeaDesign extends Model
{
    protected $table = "idea_design";

    public function designs()
    {
        return $this->hasMany('App\Design', 'id');
    }

    public function idea()
    {
        return $this->belongsTo('App\Idea', 'idea_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'staff_design_id');
    }
    public function idea_design_complete()
    {
        return $this->designs()->where('finish_design','!=',null)->count();
    }
}
