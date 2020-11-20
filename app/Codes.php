<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Codes extends Authenticatable
{
    use Notifiable;
    public $table = 'codes';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [
        'status','binding_time','member_id'
    ];
    protected $hidden = [

    ];
}
