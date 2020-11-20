<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/14
 * Time: 11:32
 */

namespace App\Http\Controllers\Api;


use App\Codes;
use App\Conf;
use App\Http\Controllers\Controller;
use App\Member;
use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Qcloud\Sms\SmsSingleSender;
use App\PetLost;
use App\PetRemark;
use App\MemberLog;

class PetController extends Controller
{
    /**
     * 获取宠物列表
     * @param Request $request
     * @return string
     */
    public function getPetList(Request $request)
    {
        $data = $request->all();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        $memberInfo = Member::find(intval($member_id));
        if(empty($memberInfo)){
            return jsonReturn(-1,'用户不存在或已被删除');
        }
        
        $memberIdArr = [$member_id];
        if($memberInfo->phone){
            $relationList = Member::where('phone',$memberInfo->phone)->get(['id','phone'])->toArray(); 
            $memberIdArr = array_column($relationList,'id');
        }
      
        $petList = Pet::whereIn('member_id',$memberIdArr)->orderBy('id', 'DESC')->get(['id', 'name', 'type', 'img', 'birthday', 'sex'])->toArray();
        if (!$petList) {
            return jsonReturn(-1, '获取宠物列表失败');
        }
        foreach ($petList as $k => $v) {
            $petList[$k]['img'] = explode(',', $v['img']);
            $petList[$k]['sex'] = $v['sex'] == 1?'GG':'MM';
        }

        return jsonReturn(0, '获取宠物列表成功', $petList);

    }

    /**
     * 获取单个宠物信息
     * @param Request $request
     */
    public function getPetInfo(Request $request)
    {
        $data = $request->all();
        if(isset($data['id'])){
            $token = $_SERVER['HTTP_TOKEN'];
            $cache = Cache::get($token);
            if(!$cache){
                return jsonReturn(-2,'请重新授权');
            }
            $member_id = $cache['member_id'];
            $memberInfo = Member::find(intval($member_id));
            $memberIdArr = [$member_id];
            if($memberInfo->phone){
                $relationList = Member::where('phone',$memberInfo->phone)->get(['id','phone'])->toArray(); 
                $memberIdArr = array_column($relationList,'id');
            }
            
            $petInfo = Pet::where('id',intval($data['id']))->whereIn('member_id',$memberIdArr)->get(['id', 'name', 'type', 'img', 'birthday', 'sex', 'phone', 'wx', 'remark', 'relation'])->toArray();
            if (!$petInfo) {
                return jsonReturn(-1, '获取宠物信息失败');
            }
            $petInfo[0]['sex'] = $petInfo[0]['sex'] == 1? 'GG':'MM';
            if($petInfo[0]['img']){
                $petInfo[0]['img'] = explode(',', $petInfo[0]['img']); 
                    
            }else{
                $petInfo[0]['img'] = array();
            }
    
            $petInfo[0]['code_list'] =  DB::table('codes')->leftjoin('info_codes','info_codes.code_id','=','codes.id')->where('info_codes.pet_id',$petInfo[0]['id'])->get(['codes.id','codes.code_number','codes.code'])->toArray();
        }else{
            if(isset($data['code_number'])){
              
                $petInfo = Pet::whereRaw("FIND_IN_SET('{$data['code_number']}',`code_number`)")->get(['id', 'name', 'type', 'img', 'birthday', 'sex', 'phone', 'wx', 'remark', 'relation'])->toArray();
                if (!$petInfo) {
                    return jsonReturn(-1, '获取宠物信息失败');
                }
                 $petInfo[0]['sex'] = $petInfo[0]['sex'] == 1 ? 'GG':'MM';
                if($petInfo[0]['img']){
                    $petInfo[0]['img'] = explode(',', $petInfo[0]['img']); 
                    
                }else{
                    $petInfo[0]['img'] = array();
                }
               
                $petInfo[0]['code_list'] =  DB::table('codes')->leftjoin('info_codes','info_codes.code_id','=','codes.id')->where('info_codes.pet_id',$petInfo[0]['id'])->orderBy('codes.id', 'DESC')->get(['codes.id','codes.code_number','codes.code'])->toArray();
            }else{
                return jsonReturn(-1, '请求错误，参数缺失');
            }
        }
        

        return jsonReturn(0, '获取宠物信息成功', $petInfo[0]);
    }

    /**
     * 添加宠物
     * @param Request $request
     */
    public function addPet(Request $request)
    {
        $post = $request->post();

        //验证短信

        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }

        $code_number = $post['code_number'];
        $member_id = $cache['member_id'];
        $memberInfo = Member::find($member_id);
        $codeInfo = Codes::where('code_number', $code_number)->first(['id', 'code_number', 'status', 'binding_time']);
        if ($codeInfo->status == 1) {
            return jsonReturn(-1, '该二维码已绑定宠物了');
        }
        $pet = new Pet();
        DB::beginTransaction();
        try {
            if(isset($post['relation']) && $post['relation'] > 0){
                $pet = Pet::find(intval($post['relation']));

                if($pet){ //
                    $pet->id = $pet->id;
                    $pet->code_number =  $pet->code_number.','.$codeInfo->code_number;
                    $pet->relation = 1;
                }else{
                    return jsonReturn(-1, '选择关联的宠物不存在');
                }
            }else{
                $pet->name = trim($post['name']);
                $pet->type = trim($post['type']);
                $pet->birthday = trim($post['birthday']);
                $pet->sex = intval($post['sex']);
                $pet->phone = trim($post['phone']);
                $pet->wx = trim($post['wx']);
                $pet->remark = trim($post['remark']);
                $pet->code_number = $codeInfo->code_number;
                $pet->member_id = $memberInfo->id;
                $pet->nickname = $memberInfo->nickname;
                $pet->relation = 0;
                $pet->ctime = time();
                if($post['images']){
                    $pet->img = join(',',$post['images']);
                }

            }
            $save = $pet->save();
            if (!$save) {
                throw new \Exception("添加宠物信息失败");
            }

            $update = Codes::where('id', $codeInfo->id)->update(['status' => 1, 'binding_time' => time(),'member_id'=>$member_id]);
            //添加中间表数据
            $addMiddle = DB::table('info_codes')->insert(['code_id'=>intval($codeInfo['id']),'pet_id'=>$pet->id,'ctime'=>time()]);
            if (!$update || !$addMiddle) {
                throw new \Exception("宠物和二维码关联失败");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return jsonReturn(-1, $e->getMessage());
        }


        return jsonReturn(0, '绑订添加宠物信息成功');

    }

    /**
     * 修改宠物信息
     * @param Request $request
     * @return string
     */
    public function editPetInfo(Request $request)
    {
        
        $post = $request->post();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];

        $petInfo = Pet::find(intval($post['id']));
        if(!$petInfo) return jsonReturn(-1,'宠物不存在或者已被删除');
        $petInfo->name = trim($post['name']);
        $petInfo->type = trim($post['type']);
        $petInfo->birthday = trim($post['birthday']);
        $petInfo->sex = intval($post['sex']);
        $petInfo->phone = trim($post['phone']);
        $petInfo->wx = trim($post['wx']);
        $petInfo->remark = trim($post['remark']);
        $delImgArr = [];
        $oldImgArr = explode(',', $petInfo->img);
        foreach($post['images'] as $k => $v){
            $arr = parse_url($v);
            $post['images'][$k] = $arr['path'];
        }
        foreach($oldImgArr as $key => $val){
            if(!in_array($val,$post['images'])){
                $delImgArr[] = $val;
            }
        }
        $imgs = trim(join(',',$post['images']),',');

        $petInfo->img = $imgs;
        $update = $petInfo->save();
        if($update){

            foreach ($delImgArr as $v) {
                @unlink('.' . $v);
            }
            return jsonReturn(0,'修改成功');
        }
        return jsonReturn(-1,'修改失败');

    }

    /**
     * 上传图片
     * @param Request $request
     * @return string
     */
    public function imgUpload(Request $request)
    {
        $fileType = 'images';
        $file = $request->file('file');
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
           return jsonReturn(0,'上传成功','/' . $path->getPathname());
        }
        return jsonReturn(-1,'上传失败');


    }

    
    /**
     * 宠物丢失添加
     * @param Request $request
     *
     */
    public function lostAdd(Request $request)
    {
        $post = $request->post();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];

        // $member_id = $post['member_id'];
        $petLost = new PetLost();
        $petLost->pet_id = intval($post['pet_id']);
        $petLost->member_id = $member_id;
        if(is_integer($post['lost_time'])){
            $petLost->lost_time = $post['lost_time'];
        }else{
            $petLost->lost_time = strtotime($post['lost_time']);
        }
        $petLost->address = trim($post['address']);
        $petLost->phone = trim($post['phone']);
        $petLost->wx = trim($post['wx']);
        $petLost->amount = floatval($post['amount']);
        $petLost->ctime = time();
        $petLost->status = 0;
        $result = PetLost::where(['pet_id'=>$post['pet_id'],'status'=>0])->first();
        if($result){
            return jsonReturn(-1,'已发布过,无需再次发布');
        }
        DB::beginTransaction();
        try {
            
            $save = $petLost->save();
            
            if (!$save) {
                throw new \Exception("添加宠物丢失信息失败");
            }
            
            $create_url = 'https://' . $_SERVER['SERVER_NAME'].'/lost?path=pages/publish/published&pet_id='.intval($petLost->id);
            $filename = 'lost_pet'.intval($post['pet_id']).'.png';
            $dir = public_path('/uploads/lost_img' );
            if(!is_dir($dir)){
                @mkdir($dir,0777,true);
            }
            $retFile ='/uploads/lost_img/'.$filename;
            \QrCode::format('png')->size(300)->encoding('UTF-8')->generate($create_url,public_path($retFile));
            $update = PetLost::where('id',$petLost->id)->update(['lost_img'=>$retFile]);
            if(!$update){
                throw new \Exception("生成丢失二维码失败");
            }
            
             DB::commit();
        } catch (\Exception $e) {
             DB::rollBack();
            return jsonReturn(-1, $e->getMessage());
        }

        return jsonReturn(0, '添加宠物丢失信息成功');


    }
    /**
     * 宠物丢失修改
     * @param Request $request
     *
     */
    public function lostEdit(Request $request)
    {
        if(!$request->isMethod('post')){
            return jsonReturn(-1,'错误请求');
        }
        $post = $request->post();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        // $member_id = $post['member_id'];
        $petLostInfo = PetLost::find(intval($post['id']));
        if(is_integer($post['lost_time'])){
            $petLostInfo->lost_time = $post['lost_time'];
        }else{
            $petLostInfo->lost_time = strtotime($post['lost_time']);
        }
        $petLostInfo->address = trim($post['address']);
        $petLostInfo->phone = trim($post['phone']);
        $petLostInfo->wx = trim($post['wx']);
        $petLostInfo->amount = floatval($post['amount']);
        $petLostInfo->status = intval($post['status']);

        try {
            $save = $petLostInfo->save();
            if (!$save) {
                throw new \Exception("添加宠物丢失信息失败");
            }
        } catch (\Exception $e) {
            return jsonReturn(-1, $e->getMessage());
        }

        return jsonReturn(0, '修改宠物丢失信息成功');

    }
    //修改状态
    public function statusEdit(Request $request)
    {
        if(!$request->isMethod('post')){
            return jsonReturn(-1,'错误请求');
        }
        $post = $request->post();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        $petLostInfo = PetLost::find(intval($post['id']));
        $petLostInfo->status = intval($post['status']);
        try {
            $save = $petLostInfo->save();
            if (!$save) {
                throw new \Exception("添加宠物丢失状态失败");
            }
        } catch (\Exception $e) {
            return jsonReturn(-1, $e->getMessage());
        }

        return jsonReturn(0, '修改宠物丢失状态成功');
         
    }

    /**
     * 获取宠物丢失信息
     * @return string
     *
     */
    public function getPetLostInfo(Request $request)
    {
        $data = $request->all();
        // $token = $_SERVER['HTTP_TOKEN'];
        // $cache = Cache::get($token);
        // if(!$cache){
        //     return jsonReturn(-2,'请重新授权');
        // }
        // $member_id = $cache['member_id'];
        // $member_id = $data['member_id'];

        $petLostInfo = PetLost::with(['pet'=>function($query){
            return $query->select(['id','name','img','remark']);
        }])->where(['id' => intval($data['id'])])->first(['id', 'pet_id', 'member_id', 'address', 'phone', 'wx', 'amount', 'status','lost_time','lost_img']);

        if (!$petLostInfo) {
            return jsonReturn(-1, '获取宠物丢失信息失败');
        }
        $petLostInfo->pet['img'] = explode(',', $petLostInfo->pet['img']);
        $petLostInfo->lost_time = date('Y-m-d H:i:s',$petLostInfo->lost_time);

        return jsonReturn(0, '获取宠物丢失信息成功', $petLostInfo);
    }

    /**
     * 获取丢失宠物列表信息
     * @param Request $request
     */
    public function lostPetList(Request $request)
    {
        $data = $request->all();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        $memberInfo = Member::find(intval($member_id));
        if(empty($memberInfo)){
            return jsonReturn(-1,'用户不存在或已被删除');
        }
        $memberIdArr = [$member_id];
        if($memberInfo->phone){
            $relationList = Member::where('phone',$memberInfo->phone)->get(['id','phone'])->toArray(); 
            $memberIdArr = array_column($relationList,'id');
        }
   
        $petLostList = PetLost::with(['pet'=>function($query){
            return $query->select(['id','name','img','remark']);
        }])->whereIn('member_id',$memberIdArr)->orderBy('id', 'DESC')->get(['id', 'pet_id', 'member_id', 'address', 'phone', 'wx', 'amount', 'status','lost_time'])->toArray();

        if (!$petLostList) {
            return jsonReturn(-1, '获取宠物丢失列表失败');
        }
        foreach($petLostList as $k => $v){
            $petLostList[$k]['pet']['img'] = explode(',', $v['pet']['img']);
            $petLostList[$k]['lost_time'] = date('Y-m-d H:i:s',$v['lost_time']);
        }

        return jsonReturn(0, '获取宠物丢失列表成功', $petLostList);

    }
    
    //删除宠物信息
    public function delPet(Request $request)
    {
        $data = $request->all();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        $memberInfo = Member::find(intval($member_id));
        if(empty($memberInfo)){
            return jsonReturn(-1,'用户不存在或已被删除');
        }
        $petInfo = Pet::find(intval($data['id']));

        DB::beginTransaction();
        try {
            
            $del = Pet::destroy($petInfo->id);
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
            
            if (MemberLog::whereIn('code_id',$codeIdArr)->first(['id'])) {
                $logdel = MemberLog::whereIn('code_id', $codeIdArr)->delete();
                if (!$logdel) {
                    throw new \Exception("删除该二维码扫码记录失败");
                }
            }

            $update = Codes::whereIn('id',$codeIdArr)->update(['status' => 0, 'binding_time' => 0,'member_id'=>0]);
            if (!$update) throw new \Exception("重置二维码绑定状态失败");
            
            
            if(PetLost::where('pet_id',$petInfo->id)->first(['id'])){
                $delPetLost = PetLost::where('pet_id',$petInfo->id)->delete();
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
    
    //获取模板信息
    public function getMessageList()
    {
        $messageList = PetRemark::where('status',1)->get(['id','title','remark'])->toArray();
        
        return json_encode(['code'=>0,'msg'=>'获取成功','data'=>$messageList]);
        
    }


}