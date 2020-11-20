<?php


namespace App\Http\Controllers\Api;


use App\Article;
use App\Http\Controllers\Controller;
use App\Codes;
use App\Member;
use App\MemberLog;
use App\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    /**
     * 扫码进入页面
     * @param Request $request
     * @return false|string
     */
    public function getCodeInfo(Request $request)
    {
        
        $data = $request->all();
        $code_number = $data['code_number'];
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'] ;

        $codeInfo = Codes::where('code_number',$code_number)->first();
        if(is_null($codeInfo)){
            return jsonReturn(-1,'二维码信息不存在，访问错误');
        }
        //当前用户信息
        $memberInfo = Member::find(intval($member_id));
        if(empty($memberInfo)){
            return jsonReturn(-1,'用户不存在或已被删除');
        }
        //获取关联用户的id
        $memberIdArr = [$member_id];
        if($memberInfo->phone){
            $relationList = Member::where('phone',$memberInfo->phone)->get(['id','phone'])->toArray(); 
            $memberIdArr = array_column($relationList,'id');
        }
        
        $petList = Pet::whereIn('member_id',$memberIdArr)->orderBy('id', 'DESC')->get(['id','name','sex','code_number','member_id','img'])->toArray();
        foreach($petList as $k => $v){
            if($v['img']){
                $imgArr = explode(',',$v['img']);
                $petList[$k]['img'] = $imgArr[0];  
            }
            $petList[$k]['sex'] = $v['sex'] == 1 ? 'GG' : 'MM' ;
            
        }
        if($codeInfo->status == 0){
            $result = [
                'is_bind'=>0, //未绑定
                'code_number'=>$code_number,
                'pet_list'=>$petList,
            ];
            return jsonReturn(0,'扫码成功，进入绑定页面',$result);
        }
  
        $petInfo = DB::table('info')->leftjoin('info_codes','info_codes.pet_id','=','info.id')->where('info_codes.code_id',$codeInfo->id)->first(['info.id','info.img','info.remark','info.phone','info.wx','info.member_id','info.img']);
        
        
        if(!$petInfo){
            return jsonReturn(-1,'宠物不存在，访问错误');
        }
        
        $info = [
            'id'=>$petInfo->id,
            'img'=>explode(',',$petInfo->img),
            'remark'=>$petInfo->remark,
            'phone'=>$petInfo->phone,
            'wx'=>$petInfo->wx,
        ];
        $result = [
            'is_bind'=>1, //已绑定
            'code_number'=>$code_number,
            'pet_info'=>$info,
            'is_owner'=>0,
            'pet_list'=>array(),
        ];
        if(in_array($petInfo->member_id,$memberIdArr)){    //用户自己扫码    进入宠物预览页
            $result['is_owner'] = 1;
        }
        return jsonReturn(0,'扫码成功，进入预览页面',$result);
        
    }


    /**
     * 保存会员扫码 地址日志
     * @param Request $request
     */
    public function saveLog(Request $request)
    {
        $post = $request->all();
        $latitude = $post['latitude']; //纬度
        $longitude = $post['longitude']; //经度
        
        $address = $this->geographic($latitude,$longitude);
        $token = $_SERVER['HTTP_TOKEN'];
        $code_number = $post['code_number'];
        $cacheDate = Cache::get($token);        //本地缓存
        if(!$cacheDate){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cacheDate['member_id']; 
        $code_number_Arr = Codes::where('member_id',$member_id)->get(['id','code_number'])->toArray();
        $code_number_Arr = array_column($code_number_Arr,'code_number');
        if(!in_array($code_number,$code_number_Arr)){
            $codeInfo = Codes::where('code_number',$code_number)->first();
            // $memberInfo = Member::find($member_id);
            if(!is_null($codeInfo)){
                $memberLog = new MemberLog();
                $memberLog->address = $address;
                // $memberLog->openid = $memberInfo->openid;
                // $memberLog->nickname = $memberInfo->nickname;
                $memberLog->latitude = $latitude;
                $memberLog->longitude = $longitude;
                $memberLog->code_id = $codeInfo->id;
                $memberLog->code_number = $code_number;
                $memberLog->ctime = time();
                $add = $memberLog->save();
                if($add){
                    return jsonReturn(0,'添加扫码记录成功');
                }
                return jsonReturn(-1,'添加扫码记录失败');
            }
        }else{
           return jsonReturn(-1,'自己扫码无需记录'); 
        }

    }

    /**
     *  获取授权地址日志记录
     * @param Request $request
     */
    public function getLogList(Request $request)
    {
        $data = $request->all();
        $token = $_SERVER['HTTP_TOKEN'];
        $cache = Cache::get($token);
        $pageNum = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit : 15;
        $page=$pageNum-1;
        if ($page != 0) {
            $page = $limit * $page;
            $limit=$limit*$pageNum;
        }
    
        if(!$cache){
            return jsonReturn(-2,'请重新授权');
        }
        $member_id = $cache['member_id'];
        
        //查询相关联的用户id
       
        $memberInfo = Member::find(intval($member_id));
        $memberIdArr = [$member_id];
        if($memberInfo->phone){
            $relationList = Member::where('phone',$memberInfo->phone)->get(['id','phone'])->toArray(); 
            $memberIdArr = array_column($relationList,'id');
        }
        $codeList = Codes::whereIn('member_id',$memberIdArr)->get(['id','code_number'])->toArray();
        
        
        $codeIdArr = array_column($codeList,'id');
        $logList = MemberLog::whereIn('code_id',$codeIdArr)->offset($page)->limit($limit)->orderBy('id','DESC')->get(['id','address','ctime','code_id','latitude','longitude','code_number'])->toArray();
        foreach($logList as $k => $v){
            $petInfo = Pet::whereRaw("FIND_IN_SET('{$v['code_number']}',`code_number`)")->first(['id','name']);
            $logList[$k]['name'] =  $petInfo->name;
            $logList[$k]['ctime'] = date('Y-m-d H:i',$v['ctime']);

        }

        return jsonReturn(0,'获取扫码日志成功',$logList);

    }

    /**
     * 获取操作指南文章
     * @param Request $request
     */
    public function getManual(Request $request)
    {
        $articleList = Article::where('type',1)->get(['id','title','content','type'])->toArray();
        if($articleList) return jsonReturn(0,'获取操作指南文章成功',$articleList);
        return jsonReturn(-1,'暂无操作指南文章');
    }

    /**
     * 获取用户协议
     * @param Request $request
     */
    public function getAgreement(Request $request)
    {
        $agreement = Article::where('type',2)->first(['id','title','content','type']);
        if($agreement) return jsonReturn(0,'获取用户协议成功',$agreement);
        return jsonReturn(-1,'暂无用户协议');
    }

    /**
     * 腾讯经纬度获取地址
     * @param $lat
     * @param $lng
     */
    public function geographic($lat,$lng)
    {
        $key = 'OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77';
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/?location='.$lat.','.$lng.'&key='.$key.'&get_poi=1';
        $result= json_decode(https_request($url),true) ;
       
        return $result['result']['address'];

    }

    /**
     * 百度经纬度获取地址
     * @param $longitude
     * @param $latitude
     * @return mixed
     */
    public function getCity($longitude, $latitude){
        //调取百度接口,其中ak为百度帐号key,注意location纬度在前，经度在后
        $api = "http://api.map.baidu.com/geocoder/v2/?ak=7hOstgqRCXdyrwwSKQiKsKUGa4GBF3Br&location=".$latitude.",".$longitude."&output=json&pois=1";
        $content = file_get_contents($api);
        $arr = json_decode($content,true);
        return $arr;
    }


    /**
     * [getRealyAddress 获取具体位置]
     * @author sunlq 2018-02-28
     * @param  [type] $lat [纬度]
     * @param  [type] $lng [经度]
     * @return [type]      [description]
     */
    public function getRealyAddress($lat,$lng)
    {
        $address = '';
        if($lat && $lng)
        {
            $arr = $this->changeToBaidu($lat,$lng);
            $url = 'http://api.map.baidu.com/geocoder/v2/?callback=&location='.$arr['y'].','.$arr['x'].'.&output=json&pois=1&ak=ZmliRk1lEOigtcPGcDeGSimRNkVdX64H';
            $content = file_get_contents($url);
            $place = json_decode($content,true);
            $address = $place['result']['formatted_address'];
        }

        return $address;
    }
    /**
     * [changeToBaidu 转换为百度经纬度]
     * @author sunlq 2018-05-28
     * @param  [type] $lat [description]
     * @param  [type] $lng [description]
     * @return [type]      [description]
     */
    public function changeToBaidu($lat,$lng)
    {
        $apiurl = 'http://api.map.baidu.com/geoconv/v1/?coords='.$lng.','.$lat.'&from=1&to=5&ak=ZmliRk1lEOigtcPGcDeGSimRNkVdX64H';
        $file = file_get_contents($apiurl);

        $arrpoint = json_decode($file, true);

        return $arrpoint['result'][0];
    }

}
