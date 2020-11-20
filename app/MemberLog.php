<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MemberLog extends Authenticatable
{
    use Notifiable;
    public $table = 'member_log';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [

    ];
    protected $hidden = [

    ];
}
