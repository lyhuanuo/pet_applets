<?php

namespace App\Http\Controllers\Admin;

use App\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Menu;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Auth;

class LogController extends BaseController
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
        return view('web.log.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url,'site_name'=>$site_name]);
    }

    /**
     * 获取列表信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {

        //分页
        $limit = $request->limit;
        $page = $request->page;
        $offset = ($page - 1) * $limit;
        // 排序
        $key = $request->key ? $request->key : 'id';
        $order = $request->order ? $request->order : 'desc';
        //搜索

        $username = $request->username;
        $where = [];
        if ($username) {
            $where[] = ['username', 'like', '%' . $username . '%'];
        }

        $log = new Log();
        $count = $log->where($where)->count();
        $list = $log->where($where)->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();

        foreach ($list as $k => $v) {
            $list[$k]['ctime'] = $v['ctime'] > 0 ? date('Y-m-d H:i:s', $v['ctime']) : '无';
        }

        $data = [
            'code' => 200,
            'msg' => '获取成功',
            'count' => $count,
            'data' => $list,
        ];
        return json_encode($data);
    }

    public function del($id)
    {
        $del = Log::destroy($id);
        if ($del) {
            return jsonReturn(0, '删除管理员操作记录成功');
        } else {
            return jsonReturn(-1, '删除管理员操作记录失败');
        }
    }


}
