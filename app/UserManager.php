<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserManager extends Model
{
    protected $table = 'user_manager';
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }    
}
