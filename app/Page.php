<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = "pages";
    protected $fillable = ['page_id','page_name','url','url_image'];
    public function niches()
    {
    	return $this->belongsToMany('App\Niche');
    }

    public function interests()
    {
    	return $this->belongsToMany('App\Interest');
    }
}
