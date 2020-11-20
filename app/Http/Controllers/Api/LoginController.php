<?php


namespace App\Http\Controllers\Api;

use App\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

include_once  app_path('/Http/Controllers/Api/wxBizDataCrypt.php');

class LoginController extends Controller
{
    //授权登录
    private $token;
    private $appId;
    private $appSecret;
    public function __construct()
    {
        $this->appId = env('WECHAT_APPID');
        $this->appSecret = env('WECHAT_SECRET');
    }

    /**
     * 登录
     * @param Request $request
     * @return array
     */
    public function login(Request $request){
        $post=$request->all();
        //微信登录 获取session_key
        $encryptedData = $post['encryptedData'];
        $iv = $post['iv'];
        $code = $post['code'];
        $url = 'https://api.weixin.qq.com/sns/jscode2session?';
        $url.= 'appid='.$this->appId.'&secret='.$this->appSecret.'&js_code='.$code.'&grant_type=authorization_code';
        $session = json_decode($this->get($url),true);
        if (isset($session['errcode'])) {
            return jsonReturn(-1,$session['errmsg']);
        }
        $sessionKey = $session['session_key'];
        $userifo = new WXBizDataCrypt($this->appId, $sessionKey);
        $errCode = $userifo->decryptData($encryptedData, $iv, $data );
        $data = json_decode($data,true);
      
        if ($errCode == 0) {
            // 自动注册用户
            $user_id = $this->register($session['openid'], $data['nickName'],$data['avatarUrl'],$data['gender'],$data['city'],$data['province'],$data['country']);
            // 生成token (session3rd)
            $this->token = $this->token($session['openid']);
            $session['member_id']=$user_id;
            // 记录缓存, 7天
            Cache::put($this->token,$session,86400*7);
            $data = ['token'=>$this->getToken(),'sessionKey'=>$sessionKey];
            return jsonReturn(0,'获取成功',$data);

        } else {
            return jsonReturn(-1,$errCode);
        }
    }
    /**
     * 获取手机号
     * @param Request $request
     * @return array
     */
    public function getPhone(Request $request){
        $post=$request->all();
        //微信登录 获取session_key
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        $memberInfo = Member::find(intval($member_id));
        if($memberInfo->phone){
            return jsonReturn(-1,'已授权手机号号，无需再授权');
        }
        
        $encryptedData = $post['encryptedData'];
        $iv = $post['iv'];
        if(isset($post['sessionKey']) && !empty($post['sessionKey'])){
            $sessionKey = $post['sessionKey'];
        }else{
            if(isset($post['code']) && !empty($post['code'])){
                $code = $post['code'];
                $url = 'https://api.weixin.qq.com/sns/jscode2session?';
                $url.= 'appid='.$this->appId.'&secret='.$this->appSecret.'&js_code='.$code.'&grant_type=authorization_code';
                
                $session = json_decode($this->get($url),true);
                
                if (isset($session['errcode'])) {
                    return jsonReturn(-1, $session['errmsg']);
                }
                $sessionKey = $session['session_key']; 
            }else{
                return jsonReturn(-1,'请求出错，缺失参数');
            }
           
        }
        
        $userifo = new WXBizDataCrypt($this->appId, $sessionKey);
        $errCode = $userifo->decryptData($encryptedData, $iv, $data );
        $data = json_decode($data,true);
       
        if (isset($data['phoneNumber'])) {
            //自动注册手机号：
            $this->registerPhone($member_id, $data['phoneNumber']);

            return jsonReturn(0,'获取成功');

        } else {
            return jsonReturn(-1,$errCode);
        }
    }
    
    /**
     * 生成用户认证的token
     * @param $openid
     * @return string
     */
    private function token($openid){
        return md5($openid . 'token_salt');
    }
    /**
     * 获取token
     * @return mixed
     */
    public function getToken(){
        return $this->token;
    }

    /**
     * 模拟GET请求 HTTPS的页面
     * @param string $url 请求地址
     * @return string $result
     */
    public function get($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    
    /**
     * 自动注册用户
     */
    private function register($openId, $nickName,$avatar,$sex,$city,$province,$country){
        $userInfo['openid'] = $openId;
        $userInfo['nickname'] = $nickName;
//        $userInfo['nickName'] = preg_replace('/[\xf0-\xf7].{3}/', '', $nickName);
        $userInfo['avatar'] = $avatar;
        $userInfo['sex'] = $sex;
        $userInfo['city'] = $city;
        $userInfo['province'] = $province;
        $userInfo['country'] = $country;
        
        $check=Member::where('openid',$openId)->first();
        
        if(is_null($check)){
            $userInfo['ctime']=time();
            $memberInfo = Member::create($userInfo);
            return $memberInfo->id;
        }else{
            $userInfo['utime']=time();
            Member::where('id',$check->id)->update($userInfo);
            return $check->id;
        }
    }
    
    /**
     * 添加用户手机号
     * @param $openId
     * @param $phone
     */
    private function registerPhone($memberId, $phone)
    {
        $check=Member::where('id',$memberId)->first();
        $userInfo['phone'] = $phone;
        if(!is_null($check)){
            $userInfo['utime']=time();
            Member::where('id',$check->id)->update($userInfo);
        }
    }
    /**
     * 支付宝授权登录
     */
    public function aliLogin(Request $request){
        //获取配置文件的ali参数
        $ali_config = Config("app.ALI_CONFIG");
        //应用的APPID
        $app_id = $ali_config['APP_ID'];

        //【成功授权】后的回调地址
        $my_url = "https://".$_SERVER['HTTP_HOST']."/api/aliLogin";
        $post = $request->all();
        //Step1：获取auth_code
        $auth_code = $post['code'];//存放auth_code
        if(!$auth_code){
            return jsonReturn(-1,'获取的code不存在');
        }
        
        if(empty($auth_code)){
            //state参数用于防止CSRF攻击，成功授权后回调时会原样带回
            $alipay_state =  md5(uniqid(rand(), TRUE));
            Cache::put('alipay_state',$alipay_state,300);
            //拼接请求授权的URL
            $url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".$app_id."&scope=auth_user&redirect_uri=".$my_url."&state="
                . $alipay_state;

            echo("<script> top.location.href='" . $url . "'</script>");
            return ;
            // return jsonReturn(0,'获取成功',$url);
            
        }
        
        //Step2: 使用auth_code换取apauth_token
        if($request->get('state') == Cache::get('alipay_state') || 1)
        {
//            vendor("Alipay.AopClient"); //引入sdk
            $aop = new \AopClient();
            $aop->gatewayUrl            = "https://openapi.alipay.com/gateway.do";
            $aop->appId                 = $app_id;
            $aop->rsaPrivateKey         = $ali_config['RSA_PRIVATE_KEY'];//应用私钥
            $aop->alipayrsaPublicKey    = $ali_config['ALIPAY_RSA_PBULIC_KEY'];//支付宝公钥
            $aop->apiVersion            = '1.0';
            $aop->signType              = 'RSA2';
            $aop->postCharset           = 'utf-8';
            $aop->format                = 'json';

            //根据返回的auth_code换取access_token
//            vendor("Alipay.AlipaySystemOauthTokenRequest");//调用sdk里面的AlipaySystemOauthTokenRequest类
            $request_token = new \AlipaySystemOauthTokenRequest();
            $request_token->setCode($auth_code);
            $request_token->setGrantType("authorization_code");
            $result = $aop->execute($request_token);
            // $access_token = $result->alipay_system_oauth_token_response->access_token;
        
            if(!empty($result->alipay_system_oauth_token_response->user_id)){
                $user_id = $result->alipay_system_oauth_token_response->user_id;
                $userInfo = $post['userInfo'];
               
                if(empty($userInfo)){
                    return jsonReturn(-1,'缺少参数');
                }
                $userInfo = json_decode($userInfo,true);
                $data = [
                    'province'  => $userInfo['response']['province'],
                    'sex'       => $userInfo['response']['gender']=='m'? 1 : 2,
                    'city'      => $userInfo['response']['city'],
                    'nickname'  => isset($userInfo['response']['nickName']) ? $userInfo['response']['nickName']: '',
                    'openid'    => $user_id,
                    'avatar'    => $userInfo['response']['avatar'],
                    'member_type'=>1,
                ];
                $check=Member::where('openid',$user_id)->first();
                //判断该用户是否存在
                if(!$check){
                    $data['ctime']=time();
                    $memberInfo = Member::create($data);
                    $member_id = $memberInfo->id;
    
                }else{
                    $data['utime']=time();
                    Member::where('id',$check->id)->update($data);
                    $member_id = $check->id;
    
                }
                $session = [
                    'openid' => $user_id,
                    'member_id' => $member_id,
                ];
                $this->token = $this->token($session['openid']);
                // 记录缓存, 7天
                Cache::put($this->token,$session,86400*7);
                $data = ['token'=>$this->getToken()];
                return jsonReturn(0,'获取成功',$data); 
            }else{
                 return jsonReturn(-1,'获取token失败'); 
            }
            
            
            //Step3: 用access_token获取用户信息
//            vendor("Alipay.AlipayUserInfoShareRequest");//调用sdk里面的AlipayUserInfoShareRequest类
            // $request = new \AlipayUserInfoShareRequest();
            // $result = $aop->execute ( $request, $access_token);
            // $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            // $resultCode = $result->$responseNode->code;
            // var_dump($resultCode,$result,$responseNode);
            // die;
            // if(!empty($resultCode) && $resultCode == 10000){
            //     $user_data = $result->$responseNode;
            
            // } else {
            //     return jsonReturn(-1,"操作异常，拒绝访问！");
            // }
            
        }
    }
    
    /**
     * 获取支付宝手机号
     */ 
    public function getAliPhone(Request $request)
    {
        $post = $request->all();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        $ali_config = Config('app.ALI_CONFIG');
        $encryptedData = trim($post['encryptedData']);
        //获取手机号解密
        $aesKey= $ali_config['encryptKey'];
        
        $result=openssl_decrypt(base64_decode($encryptedData), 'AES-128-CBC', base64_decode($aesKey),OPENSSL_RAW_DATA);
        $result = json_decode($result,true);
        if(isset($result['code']) && $result['code'] == 10000 ){
            //添加授权手机号到用户信息中
            $update = Member::where('id',$member_id)->update(['phone'=>$result['mobile']]); 
            if(!$update) return jsonReturn(-1,'添加授权手机号失败');
            return jsonReturn(0,'获取成功',$result); 
        }else{
            return jsonReturn(-1,'获取手机号授权失败'); 
        }
    }

    
    
}
