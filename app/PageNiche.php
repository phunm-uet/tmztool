<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageNiche extends Model
{
    protected $table = "niche_page";
    protected $fillable = ['niche_id','page_id'];
    
}
