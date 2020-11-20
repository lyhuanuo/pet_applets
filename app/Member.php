<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;
    public $table = 'member';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [
        'openid','nickname','avatar','sex','city','province','country','ctime','utime'
    ];
    protected $hidden = [
      
    ];
}
