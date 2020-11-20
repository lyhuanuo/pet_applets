<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //
    use Notifiable;
    public $table='admin';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [
        'username', 'password',
    ];
    protected $hidden = [
        'password',
    ];



}
