<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/7
 * Time: 10:52
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Admin;
use App\Codes;
use App\Member;
use App\Pet;
use App\PetLost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PetLostController extends BaseController
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
        return view('web.pet_lost.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url,'site_name'=>$site_name]);
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
        $status = intval($request->status);
        $name = trim($request->name);
        $nickname = trim($request->nickname);

        $where = $petWhere = $memberWhere  = [];
        $petLost = new PetLost();
        if ($status != 100) {
            $where[] = ['status', '=', $status];
        }
        $list =  $petLost->where($where);
        if($name){
            $list = $list->whereHas('pet', function($q) use ($name){
                 return $q->where('name', 'like',  '%'.$name.'%');
            });
        }
        if($nickname){
            $list = $list->whereHas('pet', function($q) use ($nickname){
                return $q->where('nickname', 'like',  '%'.$nickname.'%');
            });
        }

        $count = $list->count();
        $list = $list->with(['pet'=>function ($query) {
            return $query->select(['id','name','sex','img']);
        },'member'=>function($query){
            return $query->select(['id','nickname']);
        }])->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();


        foreach ($list as $k => $v) {
            $list[$k]['name'] = $v['pet']['name'];
            $list[$k]['sex'] = $v['pet']['sex'] == 1 ? 'GG' : 'MM';
            $list[$k]['nickname'] = $v['member']['nickname'];
            $list[$k]['ctime'] = $v['ctime'] > 0 ? date('Y-m-d H:i:s', $v['ctime']) : '无';
            $list[$k]['status'] = $v['status']  == 0 ? '丢失中' : '已找回';
            $imgArr = explode(',', $v['pet']['img']);
            if (count($imgArr) > 0) {
                $list[$k]['img'] =$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].$imgArr[0];
            }
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
     * 丢失宠物添加
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->post();
            $petLost = new PetLost();
            //添加宠物信息
            if(intval($data['pet_id']) <= 0 ) return jsonReturn(-1, '请选择丢失宠物');
            $petInfo = Pet::find($data['pet_id']);
            $petLost->pet_id = intval($petInfo->id);
            $petLost->member_id = intval($petInfo->member_id);

            $petLost->address = trim($data['address']);
            $petLost->lost_time = trim($data['lost_time']);
            $petLost->amount = floatval($data['amount']);
            $petLost->phone = trim($data['phone']);
            $petLost->wx = trim($data['wx']);
            $petLost->ctime = time();
            if(isset($data['status'])){
                $petLost->status = intval($data['status']);
            }
            try {
                $add = $petLost->save();
                if (!$add) {
                    throw new \Exception("添加丢失宠物失败");
                }
            } catch (\Exception $e) {
                return jsonReturn(-1, $e->getMessage());
            }

            return jsonReturn(0, '添加丢失宠物成功');

        }
        $petList = Pet::get(['id','name'])->toArray();

        return view('web.pet_lost.add', ['petList'=>$petList]);
    }

    /**
     * Show the form for editing the specified resource.
     *修改宠物信息
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $petLost = new PetLost();
        $petLostInfo = $petLost->find($id);
        if ($request->isMethod('post')) {
            $data = $request->post();

            $petLostInfo->address = trim($data['address']);
            $petLostInfo->lost_time = trim($data['lost_time']);
            $petLostInfo->amount = floatval($data['amount']);
            $petLostInfo->phone = trim($data['phone']);
            $petLostInfo->wx = trim($data['wx']);
            if (isset($data['status'])) {
                $petLostInfo->status = intval($data['status']);
            } else {
                $petLostInfo->status = 0;
            }

            try {
                $save = $petLostInfo->save();
                if (!$save) {
                    throw new \Exception("修改宠物丢失信息失败");
                }

            } catch (\Exception $e) {

                return jsonReturn(-1, $e->getMessage());
            }

            return jsonReturn(0, '修改宠物丢失信息成功');

        }
        $petInfo = Pet::where('id',$petLostInfo->pet_id)->first(['id','name']);


        return view('web.pet_lost.edit', ['petLostInfo' => $petLostInfo,'petInfo'=>$petInfo ]);
    }
    /**
     * 删除宠物丢失信息
     * @param $id
     * @return string
     */
    public function del($id)
    {

        $del = PetLost::destroy($id);
        if ($del) {
            return jsonReturn(0, '删除宠物丢失信息成功');
        }
        return jsonReturn(-1, '删除宠物丢失信息失败');

    }

}