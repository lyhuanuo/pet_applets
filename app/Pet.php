<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pet extends Authenticatable
{
    use Notifiable;
    public $table = 'info';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [

    ];
    protected $hidden = [

    ];

    public function member()
    {
        return $this->hasOne('App\Member','member_id','id');
    }

    public function codes()
    {
        return $this->hasOne('App\Codes','code_id','id');
    }
    
    public function petInfo()
    {
        return $this->hasOne('App\petInfo','pet_id','id');
    }
}
