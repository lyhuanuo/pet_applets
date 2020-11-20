<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\App;
class PetLost extends Authenticatable
{
    use Notifiable;
    public $table = 'lost_info';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键
    protected $fillable = [
        'id','lost_img'
    ];
    protected $hidden = [

    ];

    public function member()
    {
        return $this->hasOne('App\Member','id','member_id');
    }

    public function Pet()
    {
        return $this->hasOne('App\Pet','id','pet_id');
    }
}
