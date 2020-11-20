<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Conf;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected $menuList;
    protected $confList;
    protected $site_name;

    public function __construct()
    {
        $this->menuList =$this->_getMenuList();
        $this->confList =$this->_getConfList();
    }

    /**
     * 获取菜单信息
     * @return array
     */
    protected function _getMenuList()
    {
        $menu = new Menu();
        $menuList = $menu->select(['id','pid','title','icon','href','target'])
            ->where('status', 1)
            ->orderBy('sort', 'desc')
            ->get()->toArray();

        $menuList = buildMenuChild(0, $menuList);

        return $menuList;
    }

    /**
     * 获取配置信息
     * @return array
     */
    protected function _getConfList()
    {
        $conf = new Conf();
        $confList = $conf->orderBy('sort', 'desc')->get(['key','name'])->toArray();

        $this->site_name = $conf->where('key','site_name')->first(['value']);
        return $confList;
    }


}
