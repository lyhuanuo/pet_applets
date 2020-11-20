<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/12
 * Time: 10:10
 */

namespace App\Http\Controllers\Admin;

use App\Member;
use App\MemberLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberLogController extends BaseController
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
        return view('web.member_log.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url,'site_name'=>$site_name]);
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
        $nickname = trim($request->nickname);
        $code_number = trim($request->code_number);
        $openid = trim($request->openid);
        $where = [];
        if ($nickname) {
            $where[] = ['nickname', 'like', '%'.$nickname.'%'];
        }
        if ($openid) {
            $where[] = ['openid', 'like', '%'.$openid.'%'];
        }
        if($code_number){
            $where[] = ['code_number','like', $code_number .'%'];
        }
        $memberLog = new MemberLog();

        $count = $memberLog->where($where)->count();


        $list = $memberLog->where($where)->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();



        foreach ($list as $k => $v) {
            
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
    /**
     * 删除扫码记录
     * @param $id
     * @return string
     */
    public function del($id)
    {
        $del = MemberLog::destroy($id);
        if ($del) {
            return jsonReturn(0, '删除扫码记录成功');
        } else {
            return jsonReturn(-1, '删除扫码记录失败');
        }
    }

}
