<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $table = 'interests';
    protected $fillable = ['name','num_audience','targeting','niche_id'];

    public function Niche()
    {
    	return $this->belongsTo('App\Niche',"niche_id");
    }
}
