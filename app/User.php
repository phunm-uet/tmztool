<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function manager()
    {
        return $this->hasOne('App\UserManager');
    }

    public function ideas()
    {
        return $this->hasMany('App\Idea',"staff_id");
    }

    public function idea_design()
    {
        return $this->hasMany('App\IdeaDesign',"staff_design_id");
    }

}
