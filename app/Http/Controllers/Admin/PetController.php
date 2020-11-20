<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/12
 * Time: 10:10
 */

namespace App\Http\Controllers\Admin;

use App\Codes;
use App\Member;
use App\Pet;
use App\MemberLog;
use App\petLost;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetController extends BaseController
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
        return view('web.pet.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url,'site_name'=>$site_name]);
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
        $sex = intval($request->sex);
        $name = trim($request->name);
        $nickname = trim($request->nickname);
        $code_number = trim($request->code_number);

        $where = [];

        if ($sex != 100) {
            $where[] = ['sex', '=', $sex];
        }
        if($name){
            $where[] = ['name', 'like', '%'.$name.'%'];
        }
        if($nickname){
            $where[] = ['nickname', 'like', '%'.$nickname.'%'];
        }
        if($code_number){
            $where[] = ['code_number', 'like', $code_number.'%'];
        }
        $pet = new Pet();
        $count = $pet->where($where)->count();
        $list = $pet->where($where)->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();
        foreach ($list as $k => $v) {

            $list[$k]['relation'] = $v['relation'] > 0 ? '有关联' : '无关联';
            $list[$k]['sex'] = $v['sex'] == 1 ? 'GG' : 'MM';
            $list[$k]['ctime'] = $v['ctime'] > 0 ? date('Y-m-d H:i:s', $v['ctime']) : '无';
            $imgArr = explode(',', $v['img']);
            if (count($imgArr) > 0) {
//                $list[$k]['img'] =$imgArr[0];
                $list[$k]['img'] =$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].$imgArr[0];
//                $list[$k]['img'] =$_SERVER['REQUEST_SCHEME'].$_SERVER['HTTP_HOST']. $imgArr[0];
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
     * 添加宠物
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $pet = new Pet();
            $data = $request->post();
            $codeInfo = Codes::find(intval($data['code_id']));
            if ($codeInfo->status == 1) {
                return jsonReturn(-1, '该二维码已经被绑定了');
            }
            $time = time();

            //添加宠物信息
            if(intval($data['relation']) > 0 ){
                $pet = Pet::find(intval($data['relation']));
                $pet->relation = 1;
                if(!strstr($pet->code_number,$codeInfo->code_number)){
                    $pet->code_number = $pet->code_number.','.$codeInfo->code_number;
                }
            }else{
                //无关联添加宠物
                if(intval($data['member_id']) <= 0 ) return jsonReturn(-1, '请选择所属用户');
                if(!trim($data['name']))  return jsonReturn(-1, '请填写宠物名称');
                if(!trim($data['type']))  return jsonReturn(-1, '请填写宠物种类');
                $memberInfo = Member::find($data['member_id']);
                $pet->name = trim($data['name']);
                $pet->type = trim($data['type']);
                $pet->code_number = $codeInfo->code_number;
                $pet->member_id = intval($data['member_id']);
                $pet->nickname = $memberInfo->nickname;
                $pet->birthday = trim($data['birthday']);
                $pet->age = howOld(trim($data['birthday']));
                $pet->phone = trim($data['phone']);
                $pet->wx = trim($data['wx']);
                $pet->remark = trim($data['remark']);
                $imgs = '';
                $pet->ctime = $time;
                $pet->sex = intval($data['sex']);
                $pet->relation = 0;
                $files = $request->file('images');
                if ($files) {
                    foreach ($files as $file) {
                        $imgs .= ',' . $this->imgUpload($file);
                    }
                }
                $pet->img = trim($imgs, ',');
            }
            DB::beginTransaction();
            try {
                $add = $pet->save();
                if (!$add) {
                    throw new \Exception("添加宠物失败");
                }
                //添加中间表数据
                $addMiddle = DB::table('info_codes')->insert(['code_id'=>intval($data['code_id']),'pet_id'=>$pet->id,'ctime'=>$time]);
                if(!$addMiddle){
                    throw new \Exception("宠物和二维码关联失败");
                }

                $update = Codes::where('id', '=', intval($data['code_id']))->update(['status' => 1, 'binding_time' => time(),'member_id'=>$pet->member_id]);
                if (!$update) {
                    throw new \Exception("绑定二维码失败");
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return jsonReturn(-1, $e->getMessage());
            }

            return jsonReturn(0, '添加宠物成功');

        }

        $codeList = Codes::where('status', '=', 0)->select(['id', 'code_number', 'code'])->get()->toArray();
        $memberList = Member::where('status', '=', 1)->select(['id', 'nickname'])->get()->toArray();
        $petList = Pet::get(['id','name','code_number'])->toArray();

        return view('web.pet.add', ['codeList' => $codeList, 'memberList' => $memberList,'petList'=>$petList]);
    }

    /**
     * Show the form for editing the specified resource.
     *修改宠物信息
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $pet = new Pet();
        $petInfo = $pet->find($id);

        if ($request->isMethod('post')) {
            $data = $request->post();
            $delImgArr = [];
            $oldImgArr = explode(',', $petInfo->img);
            $imgs = '';
            if (isset($data['oldimg'])) {
                foreach ($oldImgArr as $v) {
                    if (!in_array($v, $data['oldimg'])) {
                        $delImgArr[] = $v;
                    }
                }
                $imgs = join(',', $data['oldimg']);
            }

            $files = $request->file('images');
            if ($files) {
                foreach ($files as $file) {
                    $imgs .= ',' . $this->imgUpload($file);
                }
            }

            $petInfo->img = trim($imgs, ',');
            $petInfo->name = trim($data['name']);
            $petInfo->type = trim($data['type']);
            $petInfo->phone = trim($data['phone']);
            $petInfo->sex = intval($data['sex']);
            $petInfo->relation = intval($data['relation']);
            $petInfo->birthday = trim($data['birthday']);
            $petInfo->age = howOld(trim($data['birthday']));
            $petInfo->phone = trim($data['phone']);
            $petInfo->remark = trim($data['remark']);
            $member_id = 0;
            if(intval($data['member_id']) != $petInfo->member_id){
                $petInfo->member_id = intval($data['member_id']);
                $member_id = $petInfo->member_id;
            }

            DB::beginTransaction();
            try {
                $save = $petInfo->save();
                if (!$save) {
                    throw new \Exception("修改宠物信息失败");
                }
                if($member_id){
                    $update = Codes::where('id', '=', $petInfo->code_id)->update(['status' => 1, 'binding_time' => time(),'member_id'=>$member_id]);
                    if (!$update) {
                        throw new \Exception("更换所属会员失败");
                    }
                }
                foreach ($delImgArr as $v) {
                    @unlink('.' . $v);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return jsonReturn(-1, $e->getMessage());
            }

            return jsonReturn(0, '修改宠物信息成功');

        }
        $codeList = Codes::where('status', '=', 0)->select(['id', 'code_number', 'code'])->get()->toArray();
        $memberList = Member::where('status', '=', 1)->select(['id', 'nickname'])->get()->toArray();
        $petInfo['imgArr'] = array();
        if($petInfo['img']){
            $petInfo['imgArr'] = explode(',', $petInfo['img']);
        }


        return view('web.pet.edit', ['petInfo' => $petInfo, 'codeList' => $codeList, 'memberList' => $memberList]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function del($id)
    {
        $petInfo = Pet::find($id);

        DB::beginTransaction();
        try {
            
            $del = Pet::destroy($id);
            if (!$del) throw new \Exception("删除宠物信息失败");
            $code_number_arr = explode(',',$petInfo->code_number);
            $codeList = Codes::whereIn('code_number',$code_number_arr)->get(['id','code_number'])->toArray();
            $codeIdArr = array_column($codeList,'id');
            
           if(DB::table('info_codes')->whereIn('code_id',$codeIdArr)->first(['id'])){
                $delInfoCodes = DB::table('info_codes')->whereIn('code_id',$codeIdArr)->delete();
                if(!$delInfoCodes){
                    throw new \Exception("删除宠物与二维码关联失败");
                }
            }
            if(!$delInfoCodes){
                throw new \Exception("删除宠物与二维码关联失败");
            }

            if (MemberLog::whereIn('code_id',$codeIdArr)->first(['id'])) {
                $logdel = MemberLog::whereIn('code_id', $codeIdArr)->delete();
                if (!$logdel) {
                    throw new \Exception("删除该二维码扫码记录失败");
                }
            }

            $update = Codes::whereIn('id',$codeIdArr)->update(['status' => 0, 'binding_time' => 0,'member_id'=>0]);
            if (!$update) throw new \Exception("重置二维码绑定状态失败");
            
            
            if(PetLost::where('pet_id',$id)->first(['id'])){
                $delPetLost = PetLost::where('pet_id',$id)->delete();
                if(!$delPetLost) throw new \Exception("删除发布的宠物丢失信息失败");
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return jsonReturn(-1, $e->getMessage());

        }

        $delImgArr = explode(',', $petInfo['img']);
        foreach ($delImgArr as $v) {
            @unlink('.' . $v);
        }

        return jsonReturn(0, '删除宠物信息成功');
    }

    /**
     * 上传头像
     * @param Request $request
     * @return string
     */
    public function imgUpload($file = null)
    {
        $fileType = 'images';

        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();    //客户端文件名称..
            $tmpName = $file->getFileName();   //缓存在tmp文件夹中的文件名例如php8933.tmp 这种类型的.
            $realPath = $file->getRealPath();     //这个表示的是缓存在tmp文件夹下的文件的绝对路径
            $entension = $file->getClientOriginalExtension();   //上传文件的后缀.
            $mimeTye = $file->getMimeType();    //也就是该资源的媒体类型
            $newName = md5(date('ymdhis') . $clientName) . "." . $entension;    //定义上传文件的新名称
            $path = 'uploads/pet';
            $tree = $path . '/' . $fileType;
            if (file_exists($fileType)) {
                mkdir($tree, 0777);
            }
            $path = $file->move($tree, $newName);    //把缓存文件移动到制定文件夹
            return '/' . $path->getPathname();
        }
        return '';


    }

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request)
    {
        set_time_limit(0);
        //搜索
        $sex = intval($request->sex);
        $name = trim($request->name);
        $nickname = trim($request->nickname);
        $code_number = trim($request->code_number);

        $where = [];

        if ($sex != 100) {
            $where[] = ['sex', '=', $sex];
        }
        if($name){
            $where[] = ['name', 'like', '%'.$name.'%'];
        }
        if($nickname){
            $where[] = ['nickname', 'like', '%'.$nickname.'%'];
        }
        if($code_number){
            $where[] = ['code_number', 'like', $code_number.'%'];
        }

        //获取要导出的数据
        $list = Pet::where($where)->orderBy('id', 'asc')->get()->toArray();
        foreach($list as $k => $v){
            $imgArr =[];
            $imgstr = '';
            if($v['img']){
                $imgArr = explode(',',$v['img']);
                $imgstr = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $imgArr[0];
            }
            $list[$k]['img'] = $imgstr;
            $list[$k]['relation'] = $v['relation'] == 1 ? '已关联' : '未关联';
            $list[$k]['sex'] = $v['sex'] == 1 ? 'GG' : 'MM';
            $list[$k]['ctime'] = $v['ctime'] > 0 ? ' '.date('Y-m-d H:i:s', $v['ctime']) : '无';
            $list[$k]['phone'] = '`'.$v['phone'];
            $list[$k]['birthday'] = ' '.$v['birthday'];

        }


        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=宠物全部信息列表导出.xls");

        $strexport="ID\t宠物名称\t宠物类型\t宠物图片\t宠物生日\t宠物性别\t手机号\t微信\t二维码编号\t所属会员\t备注\t添加时间\r";
        foreach ($list as $row){
            $strexport.=$row['id']."\t";
            $strexport.=$row['name']."\t";
            $strexport.=$row['type']."\t";
            $strexport.=$row['img']."\t";
            $strexport.=$row['birthday']."\t";
            $strexport.=$row['sex']."\t";
            $strexport.=$row['phone']."\t";
            $strexport.=$row['wx']."\t";
            $strexport.=$row['code_number']."\t";
            $strexport.=$row['nickname']."\t";
            $strexport.=$row['remark']."\t";
            $strexport.=$row['ctime'] ."\r";
        }
        $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
        exit($strexport);

    }


}
