<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/12
 * Time: 10:10
 */

namespace App\Http\Controllers\Admin;

use App\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use App\Codes;
use App\Pet;
use App\MemberLog;
use App\PetLost;
use Illuminate\Support\Facades\DB;

class MemberController extends BaseController
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
        return view('web.member.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url,'site_name'=>$site_name]);
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
        $member_type = intval($request->member_type);
        $sex = intval($request->sex);
        $nickname = trim($request->nickname);
        $openid = trim($request->openid);
        $where = [];
        if ($status != 100) {
            $where[] = ['status', '=', $status];
        }

        if($sex != 100){
            $where[] = ['sex','=', $sex];
        }
        if($member_type != 100){
            $where[] = ['member_type','=', $member_type];
        }
        if ($nickname) {
            $where[] = ['nickname', 'like', '%' . $nickname . '%'];
        }
        if ($openid) {
            $where[] = ['openid', 'like', '%' . $openid . '%'];
        }

        $member = new Member();
        $count = $member->where($where)->count();
        $list = $member->where($where)->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();
        foreach ($list as $k => $v) {
            // if($v['avatar']){
            //     $list[$k]['avatar'] =  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $v['avatar'];
            // }
            $list[$k]['status'] = $v['status'] == 1 ? '正常' : '禁用';
            $list[$k]['member_type'] = $v['member_type'] == 1 ? '支付宝用户' : '微信用户';
            $list[$k]['sex'] = $v['sex'] == 0 ? '保密' : ($v['sex'] == 1 ? '男' : '女');
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
     * 添加会员
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $member = new Member();
            $data = $request->post();
            $member->openid = trim($data['openid']);
            $member->nickname = trim($data['nickname']);
            $member->realname = trim($data['realname']);
            $member->city = trim($data['city']);
            $member->province = trim($data['province']);
            $member->country = trim($data['country']);
            $member->wx = trim($data['wx']);
            $member->avatar = trim($data['avatar']);
            $member->phone = trim($data['phone']);
            $member->member_type = intval($data['member_type']);
            if (isset($data['status'])) {
                $member->status = intval($data['status']);
            } else {
                $member->status = 0;
            }
            $member->sex = intval($data['sex']);
            $member->remark = trim($data['remark']);
            $member->ctime = time();

            $save = $member->save();
            if ($save) {
                return jsonReturn(0, '添加会员成功');
            }
            return jsonReturn(-1, '添加会员失败');
        }

        return view('web.member.add');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $member = new Member();
        $memberInfo = $member->find($id);

        if ($request->isMethod('post')) {
            $data = $request->post();
            $memberInfo->nickname = trim($data['nickname']);
            $memberInfo->realname = trim($data['realname']);
            $memberInfo->city = trim($data['city']);
            $memberInfo->province = trim($data['province']);
            $memberInfo->country = trim($data['country']);
            $memberInfo->wx = trim($data['wx']);
            $member->member_type = intval($data['member_type']);
            $delImg = '';
            if (!empty(trim($data['avatar'])) && trim($data['avatar']) != $memberInfo->avatar) {
                $delImg = $memberInfo->avatar;
                $memberInfo->avatar = trim($data['avatar']);
            }
            $memberInfo->phone = trim($data['phone']);
            if (isset($data['status'])) {
                $memberInfo->status = intval($data['status']);
            } else {
                $memberInfo->status = 0;
            }
            $memberInfo->sex = intval($data['sex']);
            $memberInfo->remark = trim($data['remark']);

            $save = $memberInfo->save();
            if ($save) {
                @unlink('.' . $delImg);
                return jsonReturn(0, '修改会员成功');
            }
            return jsonReturn(-1, '修改会员失败');
        }

        return view('web.member.edit', ['memberInfo' => $memberInfo]);
    }

    /**
     * 删除会员
     * @param $id
     * @return string
     */
    public function del($id)
    {
        $memberInfo = Member::find($id);
        
        //删除用户所有信息
        DB::beginTransaction();
        $delImg = $memberInfo->avatar;
        try{
            //删除用户
            $del = Member::destroy($id);
            if(!$del){
                throw new \Exception("删除会员失败");
            }
            //删除用户绑定的二维码
            $codeList = Codes::where('member_id',$memberInfo->id)->get(['id','code_number','member_id'])->toArray();
            if(!empty($codeList)){
                $codeIdArr = array_column($codeList,'id');
                //修改二维码绑定的信息
                $codeEdit = Codes::whereIn('id',$codeIdArr)->update(['status' => 0, 'binding_time' => 0,'member_id'=>0]);
                if(!$codeEdit) throw new \Exception("重置该用户绑定的二维码失败");
                //删除有关二维码的扫码信息
                if(MemberLog::whereIn('code_id',$codeIdArr)->first()){
                    $delMemberLog = MemberLog::whereIn('code_id',$codeIdArr)->delete();
                    if(!$delMemberLog) throw new \Exception("删除二维码扫码记录信息");
                }
                //获取用户绑定的宠物
                $petList = Pet::where('member_id',$memberInfo->id)->get()->toArray();
                if(!empty($petList)){
                    $petIdArr = array_column($petList,'id'); 
                    //删除宠物
                    $delPet = Pet::whereIn('id',$petIdArr)->delete();
                    
                    if(!$delPet) throw new \Exception("删除用户绑定的宠物信息失败");
                    if(DB::table('info_codes')->whereIn('id',$petIdArr)->first(['id'])){
                        $delPetCode = DB::table('info_codes')->whereIn('id',$petIdArr)->delete();
                        if(!$delPetCode) throw new \Exception("删除宠物绑定二维码关联信息失败"); 
                    }
                   
                }
                if(PetLost::where('member_id',$memberInfo->id)->first()){
                    $delPetLost = PetLost::where('member_id',$memberInfo->id)->delete();
                    if(!$delPetLost) throw new \Exception("删除用户发布宠物丢失信息失败");
                }
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return jsonReturn(-1, $e->getMessage());
        }
        @unlink('.' . $delImg);
        return jsonReturn(0, '删除会员成功');
    }

    /**
     * 导出会员
     * @param Request $request
     */
    public function export(Request $request)
    {
        set_time_limit(0);
        //搜索
        $sex = intval($request->sex);
        $status = trim($request->status);
        $nickname = trim($request->nickname);
        $openid = trim($request->openid);

        $where = [];

        if ($sex != 100) {
            $where[] = ['sex', '=', $sex];
        }
        if($status != 100){
            $where[] = ['status', 'like', '%'.$status.'%'];
        }
        if($nickname){
            $where[] = ['nickname', 'like', '%'.$nickname.'%'];
        }
        if($openid){
            $where[] = ['openid', 'like', $openid.'%'];
        }

        //获取要导出的数据
        $list = Member::where($where)->orderBy('id', 'asc')->get()->toArray();
        foreach($list as $k => $v){
            // if($v['avatar']){
            //     $list[$k]['avatar'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $v['avatar'];
            // }
            $list[$k]['ctime'] = $v['ctime'] > 0 ? ' '.date('Y-m-d H:i:s', $v['ctime']) : '无';
            $list[$k]['phone'] =  '`'.$v['phone'];
            $list[$k]['status'] = $v['status'] == 1 ? '正常' : '禁用';
            $list[$k]['member_type'] = $v['member_type'] == 1 ? '支付宝用户' : '微信用户';
            $list[$k]['sex'] = $v['sex'] == 0 ? '保密' : ($v['sex'] == 1 ? '男' : '女');

        }

        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=会员全部信息列表导出.xls");

//        $strexport="ID\topenid\t会员昵称\t性别\t头像\t手机号\t微信\t城市\t省份\t国家\t状态\t添加时间\r";
        $strexport="ID\topenid\t会员昵称\t性别\t头像\t手机号\t微信\t状态\t 用户类型\t添加时间\r";
        foreach ($list as $row){
            $strexport.=$row['id']."\t";
            $strexport.=$row['openid']."\t";
            $strexport.=$row['nickname']."\t";
            $strexport.=$row['sex']."\t";
            $strexport.=$row['avatar']."\t";
            $strexport.=$row['phone']."\t";
            $strexport.=$row['wx']."\t";
            $strexport.=$row['status']."\t";
            $strexport.=$row['member_type']."\t";
            $strexport.=$row['ctime'] ."\r";
        }
        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
        exit($strexport);

    }

}