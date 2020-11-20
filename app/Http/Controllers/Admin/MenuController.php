<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Menu;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Auth;

class MenuController extends BaseController
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
        return view('web.menu.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url,'site_name'=>$site_name]);
    }

    /**
     * 获取列表信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        //分页
//        $limit = $request->limit;
//        $page = $request->page;
//        $offset = ($page-1 )*$limit;
        // 排序
        $key = $request->key ? $request->key : 'sort';
        $order = $request->order ? $request->order : 'desc';
        //搜索
        $title = trim($request->title);
        $status = intval($request->status);
        $where = [];
        if ($status != 100) {
            $where[] = ['status', '=', $status];
        }
        if ($title) {
            $where[] = ['title', 'like', '%' . $title . '%'];
        }
        $menu = new Menu();
        $count=$menu->where($where)->count();
        $list = $menu->where($where)->orderBy($key, $order)->get()->toArray();


        $list = tree($list);
        foreach ($list as $k => $v) {
            $list[$k]['title'] = $v['html'] . $v['title'];
            if ($v['target'] == '_self') {
                $list[$k]['target'] = '当前页面打开';
            } elseif ($v['target'] == '_black') {
                $list[$k]['target'] = '打开新的窗口';
            }
            $list[$k]['status'] = $v['status'] == 1 ? '正常' : '禁用';
            $list[$k]['ctime'] = $v['ctime'] > 0 ? date('Y-m-d H:i:s', $v['ctime']) : '无';
        }
        $data = [
            'code' => 0,
            'msg' => '获取成功',
            'count' => $count,
            'data' => $list,
        ];

        return response()->json($data);
    }


    public function add(Request $request)
    {
        $menu = new Menu();
        if ($request->isMethod('post')) {
            $data = $request->post();
            $parentInfo = $menu->find(intval($data['pid']));
            $menu->title = trim($data['title']);
            $menu->pid = intval($data['pid']);
            $menu->href = trim($data['href']);
            $menu->target = trim($data['target']);
            $menu->sort = trim($data['sort']);
            $menu->status = trim($data['status']);
            $menu->remark = trim($data['remark']);
            if ($parentInfo) {
                $menu->level = $parentInfo['level'] + 1;
            } else {
                $menu->level = 1;
            }
            $menu->ctime = time();

            $add = $menu->save();
            if ($add) {
                return jsonReturn(0, '添加菜单成功');
            }
            return jsonReturn(-1, '添加菜单失败');
        }
        //获取所有菜单
        $menuList = $menu->select(['id', 'pid', 'title', 'level'])
            ->where('status', 1)
            ->orderBy('sort', 'desc')
            ->get()->toArray();

        $menuList = tree($menuList);

        return view('web.menu.add', ['menuList' => $menuList]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $menu = new Menu();
        $menuInfo = $menu->find($id);

        if ($request->isMethod('post')) {

            $data = $request->post();
            $menuInfo->title = trim($data['title']);
            $menuInfo->id = intval($data['id']);
            $menuInfo->href = trim($data['href']);
            $menuInfo->target = trim($data['target']);
            $menuInfo->sort = trim($data['sort']);
            $menuInfo->status = trim($data['status']);
            $menuInfo->remark = trim($data['remark']);
            $menuInfo->utime = time();

            $update = $menuInfo->save();

            if ($update) {
                return jsonReturn(0, '修改菜单信息成功');
            }
            return jsonReturn(-1, '修改菜单信息失败');

        }
        //获取所有菜单
        $menuList = $menu->select(['id', 'pid', 'title', 'level'])
            ->where('status', 1)
            ->orderBy('sort', 'desc')
            ->get()->toArray();

        $menuList = tree($menuList);

        return view('web.menu.edit', ['menuInfo' => $menuInfo, 'menuList' => $menuList]);


    }

    public function del($id)
    {
        $sonMenu = Menu::where('pid', $id)->first();
        if ($sonMenu) {
            return jsonReturn(-1, '不能删除该菜单，底下还有子菜单');
        }
        $del = Menu::destroy($id);
        if ($del) {
            return jsonReturn(0, '删除菜单成功');
        } else {
            return jsonReturn(-1, '删除菜单失败');
        }

    }


}
