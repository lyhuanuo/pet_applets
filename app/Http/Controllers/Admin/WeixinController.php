<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;

class WeixinController extends BaseController
{
    private $url;
    private $appid;
    private $appSecret;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->url = 'https://api.weixin.qq.com/cgi-bin/token';//请求url
        $this->appid = env('WECHAT_APPID');
        $this->appSecret =  env('WECHAT_SECRET');
    }
    
    /**
     * 获取accent_token
     * @return mixed
     */
    public function getAccessToken()
    {
    
//        $r = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->appSecret");
//        $data = json_decode($r,true);
//        return $data;
        $url ="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->appSecret";
        $result = https_request($url);
        return json_decode($result,true);
        
    }
    
   
    
    public function api_notice_increment($url, $data)
    {
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            //curl_close( $ch )
            return $ch;
        } else {
            //curl_close( $ch )
            return $tmpInfo;
        }
        curl_close($ch);
    }
}
