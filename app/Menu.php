<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Menu extends Model
{
    //
    use Notifiable;
    public $table='system_menu';
    public $timestamps = false;
    protected $primaryKey = 'id'; //主键



}
