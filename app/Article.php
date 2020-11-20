<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Article extends Authenticatable
{
    //
    use Notifiable;
    public $table='article';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [

    ];
    protected $hidden = [

    ];



}
