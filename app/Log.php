<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Log extends Authenticatable
{
    use Notifiable;
    public $table = 'admin_logs';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [

    ];
    protected $hidden = [

    ];
}
