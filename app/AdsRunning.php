<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsRunning extends Model
{
	public $timestamps = false;
    protected $table = "ads_running";
    protected $fillable = ['campaign_id','campaign_name','post_id','page_id','image_url'];
}
