<?php

namespace App\Http\Controllers\Admin;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;
use App\Admin;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin = new Admin();
        $adminInfo = $admin->find(Auth::id());
        $url = $request->route()->getName();
        $site_name = $this->site_name->value ? $this->site_name->value : '后台';

        return view('web.index.index',['adminInfo'=>$adminInfo,'menuList'=>$this->menuList,'url'=>$url,'site_name'=>$site_name]);
    }



    // 获取初始化数据
    public function getSystemInit(){
        $homeInfo = [
            'title' => '控制台',
            'href'  => "main",
        ];
        $logoInfo = [
            'title' => '后台',
            'image' => 'admin/images/logo.png',
        ];
        $menuInfo = $this->getMenuList();
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];

        return response()->json($systemInit);
    }

    // 获取菜单列表
    private function getMenuList(){
        $menu = new Menu();

        $menuList = $menu->select(['id','pid','title','icon','href','target'])
            ->where('status', 1)
            ->orderBy('sort', 'desc')
            ->get()->toArray();

        $menuList = buildMenuChild(0, $menuList);

        return $menuList;
    }



}
