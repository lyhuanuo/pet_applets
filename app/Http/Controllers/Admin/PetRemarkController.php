<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/12
 * Time: 10:10
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\PetRemark;

class PetRemarkController extends BaseController
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
        return view('web.pet_remark.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url,'site_name'=>$site_name]);
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
       
        $petRemark = new PetRemark();
        $count = $petRemark->count();
        $list = $petRemark->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();
        foreach ($list as $k => $v) {
            $list[$k]['status'] = $v['status'] == 1?'正常':'禁用';
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
     * 添加宠物
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $petRemark = new PetRemark();
            $data = $request->post();
            $count = $petRemark->where('title','=',trim($data['title']))->count();
            if($count > 0){
                return jsonReturn(-1, '该标题模板已存在，无需再添加');
            }
            $petRemark->title = trim($data['title']);
            if(isset($data['status'])){
                $petRemark->status = intval($data['status']);
            }else{
                $petRemark->status = 0;
            }
            $petRemark->remark = trim($data['remark']);
            $petRemark->ctime = time();
            $add = $petRemark->save();
            if ($add) {
                return jsonReturn(0, '添加返家寄语模板成功');
            }
            return jsonReturn(-1, '添加返家寄语模板失败');

        }

        return view('web.pet_remark.add');
    }

    /**
     * Show the form for editing the specified resource.
     *修改宠物信息
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $petRemark = new PetRemark();
        $templateInfo = $petRemark->find($id);

        if ($request->isMethod('post')) {
            $data = $request->post();
            $count = $petRemark->where('title','=',trim($data['title']))->where('id','!=',$id)->count();
            if($count > 0) return jsonReturn(-1, '标题已存在，修改失败');

            $templateInfo->title = trim($data['title']);
            
            if(isset($data['status'])){
                $templateInfo->status = intval($data['status']);
            }else{
                $templateInfo->status = 0;
            }
            $templateInfo->remark = trim($data['remark']);
            $save = $templateInfo->save();
            if ($save) {
                return jsonReturn(0, '修改返家寄语模板成功');
            }
            return jsonReturn(-1, '修改返家寄语模板失败');

        }

        return view('web.pet_remark.edit', ['templateInfo' => $templateInfo]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function del($id)
    {
    
        $del = PetRemark::destroy($id);
        if ($del) {
            return jsonReturn(0, '删除返家寄语模板成功');
        } else {
            return jsonReturn(-1, '删除返家寄语模板失败');
        }
    }




}
