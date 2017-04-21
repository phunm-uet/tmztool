<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $table = 'interests';

    public function Niche()
    {
    	return $this->belongsTo('App\Niche',"niche_id");
    }
}
