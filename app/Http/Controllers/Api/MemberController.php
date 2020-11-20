<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Member;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Conf;
use Illuminate\Support\Facades\DB;
use Qcloud\Sms\SmsSingleSender;

class MemberController extends Controller
{
    /**
     * 获取会员信息
     * @param Request $request
     * @return string
     */
    public function getMemberInfo(Request $request)
    {
        $data = $request->all();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
      
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $memberInfo = Member::find(intval($cache['member_id']));
        if(!$memberInfo) return jsonReturn(-1,'会员不存在');
        return jsonReturn(0,'获取用户信息成功',$memberInfo);
    }

    /**
     *
     * @param Request $request
     * @return string
     */
    public function saveMemberInfo(Request $request)
    {
        $post= $request->post();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $sms_code = trim($post['sms']);
        $sendsms = DB::table('sendsms')->where(['phone'=>trim($post['phone']),'status'=>0])->orderBy('id ','DESC')->first();
        if(!$sendsms){
            return jsonReturn(-1,'验证码不存在或者已使用');
        }
        if($sendsms->code != $sms_code){
            return jsonReturn(-1,'验证码输入错误');
        }
        if($sendsms->expire_time < time()){
            return jsonReturn(-1,'验证码已过期，请重新发送');
        }

        
        $memberInfo = Member::find(intval($cache['member_id']));
        $delImg = '';

        $memberInfo->nickname = trim($post['nickname']);
        if($file = $request->file('avatar')){
            $delImg = $memberInfo->avatar;
            $memberInfo->avatar = $this->imgUpload($file);
        }
        $memberInfo->sex = trim($post['sex']);
        $memberInfo->phone = trim($post['phone']);
        $memberInfo->realname = trim($post['realname']);

        $save = $memberInfo->save();
        if($save) {
            DB::where('id',$sendsms->id)->update(['status'=>1]);
            @unlink('.'.$delImg);
            return jsonReturn(0,'修改资料成功');
        }

        return jsonReturn(-1,'修改资料失败');

    }
    /**
     * 更换手机号
     */ 
    public function changePhone(Request $request)
    {
        $post= $request->post();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $sms_code = trim($post['sms']);
        $sendsms = DB::table('sendsms')->where(['phone'=>trim($post['phone']),'status'=>0])->orderBy('id','DESC')->first();
        if(!$sendsms){
            return jsonReturn(-1,'验证码不存在或者已使用');
        }
        if($sendsms->code != $sms_code){
            return jsonReturn(-1,'验证码输入错误');
        }
        if($sendsms->expire_time < time()){
            return jsonReturn(-1,'验证码已过期，请重新发送');
        }
        
        $memberInfo = Member::find(intval($cache['member_id']));
        $memberInfo->phone = trim($post['phone']);
        $save = $memberInfo->save();
        if($save) {
            DB::table('sendsms')->where('id',$sendsms->id)->update(['status'=>1]);
            return jsonReturn(0,'更换手机号成功');
        }

        return jsonReturn(-1,'更换手机号失败');
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
            $path = 'uploads';
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
     * 发送手机验证码
     * @param Request $request
     * @return string
     */
    public function sendSms(Request $request)
    {
        $data = $request->post();
        $phone = trim($data['phone']);

        if(!preg_match('/^1\d{10}$/',$phone)){
            return jsonReturn(-1,'手机号格式错误');
        }

        // 短信应用SDK AppID
        $conf = Conf::all(['id','key','name','value'])->toArray();
        $appid = $appkey = $templateId = $smsSign = '';
        foreach($conf as $v){
            switch ($v['key']){
                case 'SMSAPPID':
                    $appid = $v['value'];
                    break;
                case 'SMSAPPKEY':
                    $appkey = $v['value'];
                    break;
                case 'SMSTEMPLATEID':
                    $templateId = $v['value'];// NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请
                    break;
                case 'SMSSIGN':
                    $smsSign = $v['value']; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`
                    break;
            }

        }
       
        // 指定模板ID单发短信
        try {
            
            if(empty($appid) || empty($appkey) || empty($templateId) || empty($smsSign)){
                throw new \Exception('腾讯短信息配置信息不全');
            }
            $ssender = new SmsSingleSender($appid, $appkey);

            $code = random_int(1000,9999);
            $params = [$code];//验证码

            $result = $ssender->sendWithParam("86", $phone, $templateId,
                $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
            $rsp = json_decode($result,true);
            if($rsp['result'] !== 0){
                throw new \Exception($rsp['errmsg']);
            }
            $time  = time();
            //记录发送短信
            $saveData = [
                'phone'=>$phone,
                'code'=>$code,
                'status'=>0,
                'ctime'=>$time,
                'expire_time'=>$time + 300
            ];

            DB::table('sendsms')->insert($saveData);

        } catch(\Exception $e) {

            return jsonReturn(-1,$e->getMessage());
        }
        return jsonReturn(0,'发送成功');

    }


}
