<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Niche extends Model
{
    protected $table = 'niches';
    protected $fillable =['id','name'];

    public function Idea()
    {
    	return $this->hasMany('App\Idea');
    }

    public function Interests()
    {
    	return $this->hasMany('App\Interest');
    }

    public function pages()
    {
        return $this->belongsToMany('App\Page');
    }
}
