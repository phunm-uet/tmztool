<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    protected $table = 'ideas';
    protected $fillable = [
    	'name','description','audience','link','reason','deploy_description','niche_id','field_id','source_id'
    ];
    public function niche1()
    {
    	return $this->belongsTo('App\Niche','niche_id');
    }

    public function niche2()
    {
        return $this->belongsTo('App\Niche','niche_id_2');
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'staff_id');
    }

    public function source1()
    {
        return $this->belongsTo('App\Source', 'source_id');
    }

    public function source2()
    {
        return $this->belongsTo('App\Source', 'source_id_2');
    }

    public function source3()
    {
        return $this->belongsTo('App\Source', 'source_id_3');
    }
}
