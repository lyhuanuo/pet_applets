<?php

namespace App\Http\Controllers\Admin;

use App\MemberLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Codes;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Admin\WeixinController;
use Illuminate\Support\Facades\DB;
use App\Pet;
use App\PetLost;

class CodesController extends BaseController
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
        //获取二位码生成的日期
        $dateList = (new Codes())->selectRaw("FROM_UNIXTIME(ctime,'%Y-%m-%d') as value,FROM_UNIXTIME(ctime,'%Y%m%d') as date")->groupBy('ctime')->orderBy('date','DESC')->get()->toArray();
        foreach($dateList as $k => $v){
            if(isset($out[$v['value']])){
               unset($dateList[$k]);
               
            }else{
               $out[$v['value']] = $v; 
            }
        }
        
        $downloadList = DB::table('download_log')->get('code_date')->toArray();
        $downloadList = array_column($downloadList,'code_date');
        array_unique($downloadList);
        return view('web.codes.list', ['adminInfo' => $adminInfo, 'menuList' => $this->menuList, 'url' => $url, 'site_name' => $site_name,'dateList'=>$dateList,'downloadList'=>$downloadList]);
    }

    /**
     * 获取管理员列表
     * @return mixed
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
        $code_number = trim($request->code_number);
        $where = [];
        if ($status != 100) {
            $where[] = ['status', '=', $status];
        }

        if ($code_number) {
            $where[] = ['code_number', 'like', '%' . $code_number . '%'];
        }
        $codes = new Codes();

        $count = $codes->where($where)->count();
        $list = $codes->where($where)->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();

        foreach ($list as $k => $v) {
            $list[$k]['status'] = $v['status'] == 1 ? '已使用' : '未使用';
            $list[$k]['ctime'] = $v['ctime'] > 0 ? date('Y-m-d H:i:s', $v['ctime']) : '无';
            if ($v['code']) {
                $list[$k]['code'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $v['code'];
            }
            if ($v['picture']) {
                $list[$k]['picture'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $v['picture'];
            }
            $list[$k]['binding_time'] = $v['binding_time'] > 0 ? date('Y-m-d H:i:s', $v['binding_time']) : '无';
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
     * 添加二维码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            set_time_limit(0);
            $num = intval($request->number);
            $text = trim($request->text);
            $insertArr = [];
            $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $letter = substr(str_shuffle($str), mt_rand(0, strlen($str) - 4), 3);
            $codesArr = array_column(Codes::all('code_number')->toArray(), 'code_number');
            $newCodesArr = $this->createCodeNumber($letter, $num, $codesArr); //获取新的二维码编号
            $time = time();
            $date = date('Ymd', $time);

            $base_url = 'https://' . $_SERVER['SERVER_NAME'].'/wxapp?path=pages/index/index&code_number=';
            if($num <= 0){
                 return jsonReturn(-1, '请输入添加的数量');
            }
            for ($i = 0; $i < $num; $i++) {
                $filename = $newCodesArr[$i].'.png';
                $dir = public_path('/uploads/qr/'.$date.'/code' );
                if(!is_dir($dir)){
                    @mkdir($dir,0777,true);
                }
                $retFile ='/uploads/qr/'.$date.'/code/'.$filename;
                $create_url = $base_url.$newCodesArr[$i];
                \QrCode::format('png')->size(300)->encoding('UTF-8')->generate($create_url,public_path($retFile));
                $picture = '';
                if($text){
                    $result = $this->getPicture(public_path($retFile),$text,$date,$newCodesArr[$i]);
                    $resultArr= json_decode($result,true);
                    if($resultArr['code'] == 0){
                        $picture = $resultArr['data'];
                    }
                }
                $insertArr[$i] = [
                    'code_number' => $newCodesArr[$i],
                    'ctime' => $time,
                    'status' => 0,
                    'binding_time' => 0,
                    'code' => $retFile,
                    'picture' => $picture,
                    'member_id' => 0
                ];
            }
            $add = Codes::insert($insertArr);
            if ($add > 0) {
                $this->getZipper();
                return jsonReturn(0, '添加二维码成功');
            } else {
                return jsonReturn(-1, '添加二维码失败');
            }
        }
        return view('web.codes.add');
    }


    /**
     * 修改二维码信息
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function edit(Request $request, $id)
    {
        $codes = new Codes();
        $codesInfo = $codes->find($id);

        if ($request->isMethod('post')) {
            $data = $request->post();
            if (isset($data['status'])) {
                $codesInfo->status = intval($data['status']);
                $codesInfo->binding_time = time();
            } else {
                $codesInfo->status = 0;
                $codesInfo->binding_time = 0;
                $codesInfo->member_id = 0;
            }

            $save = $codesInfo->save();
            if ($save) {
                return jsonReturn(0, '修改二维码成功');
            }
            return jsonReturn(-1, '修改二维码失败');
        }

        return view('web.codes.edit', ['codesInfo' => $codesInfo]);
    }

    /**
     * 删除二维码
     * @param $id
     * @return string
     */
    public function del($id)
    {
        $codesInfo = Codes::find($id);
        if ($codesInfo->status == 1) {
            return jsonReturn(-1, '删除失败，该二维码已绑定宠物，请清空再删除');
        }
        $del = Codes::destroy($id);
        if ($del) {
            @unlink('.' . $codesInfo->code);
            @unlink('.' . $codesInfo->picture);
            return jsonReturn(0, '删除二维码成功');
        }
        return jsonReturn(-1, '删除二维码失败');

    }

    /**
     * 清空二维码相关信息
     * @param Request $request
     * @return string
     */
    public function clear(Request $request)
    {
        if ($request->isMethod('post')) {
            $codeId = $request->post('id');
            $codeInfo = Codes::find($codeId);
            DB::beginTransaction();
            try {
                if($codeInfo->status == 0 && $codeInfo->binding_time == 0 && $codeInfo->member_id == 0){
                    throw new \Exception("无需清空二维码信息，未绑定其他信息");
                }
                $update = $codeInfo->update(['status' => 0, 'binding_time' => 0, 'member_id' => 0]);
                if (!$update) {
                    throw new \Exception("清空二维码信息失败");
                }
                //删除宠物和二维码关联
                $infoCodeInfo = DB::table('info_codes')->where('code_id',$codeId)->first(['id','code_id','pet_id']);
                $del = DB::table('info_codes')->where('code_id',$codeId)->delete();
                if(!$del){
                    throw new \Exception("清空宠物和二维码关联");
                }
                $petInfo = Pet::find($infoCodeInfo->pet_id);
                $count = DB::table('info_codes')->where('pet_id',$infoCodeInfo->pet_id)->count();
               
                if($petInfo){
                    if($count > 0){ //修改宠物信息
                        $petUpdateData = [];
                        $code_number_arr = explode(',',$petInfo->code_number);
                        foreach($code_number_arr as $k => $v){
                            if($v == $codeInfo->code_number){
                                unset($code_number_arr[$k]);
                            }
                        }
                        $petUpdateData['code_number'] = trim(join(',',$code_number_arr),',');
                        if($count  == 1){
                            $petUpdateData['relation'] = 0;
                        }
                        $petUpdate = Pet::where('id',$petInfo->id)->update($petUpdateData);
                        if(!$petUpdate){
                            throw new \Exception("修改关联宠物信息失败");
                        }

                    }else{
                        $petDel = Pet::where('id',$petInfo->id)->delete();
                        if(!$petDel){
                            throw new \Exception("删除绑定的宠物失败");
                        }
                        if(PetLost::where('pet_id',$petInfo->id)->first(['id'])){
                            //清除丢失记录
                            $petLostDel = PetLost::where('pet_id',$petInfo->id)->delete();
                            if(!$petLostDel){
                                throw new \Exception("删除关联宠物丢失信息失败");
                            }
                        }

                    }
                }

                if (MemberLog::where('code_id', '=', $codeId)->first()) {
                    $logdel = MemberLog::where('code_id', '=', $codeId)->delete();
                    if (!$logdel) {
                        throw new \Exception("删除该二维码扫码记录失败");
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();//事务回滚
                return jsonReturn(-1, $e->getMessage());
            }

            return jsonReturn(0, '成功清空二维码相关信息');

        }
        return jsonReturn(-1, '请求错误');
    }

    /**
     * 生成水印图片
     * @param string $file
     * @param string $code
     * @return array
     */
    public function getPicture($file = '', $text = '',$date = '',$filename='')
    {
        try {
            $info = getimagesize($file);
            $type = image_type_to_extension($info[2], false); // 获取图片扩展名
            $fun = "imagecreatefrom{$type}"; // 构建处理图片方法名-关键是这里
            $image = $fun($file); // 调用方法处理
            $font = public_path('/font/msyh.ttf'); // 字体文件
            $color = imagecolorallocate($image, 0, 0, 0); // 文字颜色

            $text = mb_convert_encoding($text, "html-entities", "utf-8");
            //获取文字宽度及高度
            $bounds = ImageTTFBBox(16, 0, $font, $text);
            $min_x = min(array($bounds[0], $bounds[2], $bounds[4], $bounds[6]));
            $max_x = max(array($bounds[0], $bounds[2], $bounds[4], $bounds[6]));
            $min_y = min(array($bounds[1], $bounds[3], $bounds[5], $bounds[7]));
            $max_y = max(array($bounds[1], $bounds[3], $bounds[5], $bounds[7]));
            $width = ($max_x - $min_x);
            $height = ($max_y - $min_y);

            $image_thumb = imagecreatetruecolor($info[0], $info[1] + $height);    //创建画布
            $background = imagecolorallocate($image_thumb, 255, 255, 255);
            imagefill($image_thumb, 0, 0, $background);            //填充背景颜色
            //把二维码嵌入画布中
            imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, $info[0], $info[1], $info[0], $info[1]);
            //添加文字
            imagettftext($image_thumb, 16, 0, ($info[0]-$width)/2, $info[1] , $color, $font, $text);
//            header("Content-Type:" . $info['mime']);
            $imageTypeFun = "image" . $type;
            $dir = public_path('/uploads/qr/'.$date.'/picture');
            if(!is_dir($dir)){
                @mkdir($dir,0777,true);
            }
            $src = $dir.'/'.$filename . '.' . $type;
            //保存
            $path = '/uploads/qr/'.$date .'/picture/'. $filename . '.' . $type;
            $imageTypeFun($image_thumb, $src);
//            imagedestroy($background);
//            ob_end_clean();
            return jsonReturn(0, '添加文字成功', $path);
        } catch (\Exception $e) {
            return jsonReturn(-1, $e->getMessage());
        }


    }


    //拼接图片,logo,文字
    private function mark_photo($background, $text, $logo, $filename)
    {
        $back = imagecreatefrompng($background);
        $color = imagecolorallocate($back, 0, 0, 0);
        $logo = imagecreatefrompng($logo);
        $logo_w = imagesx($logo);
        $logo_h = imagesy($logo);
        $font = public_path('/layui/font/iconfont.ttf'); // 字体文件
        //imagettftext只认utf8字体，所以用iconv转换
        imagettftext($back, 21, 0, 40, 337, $color, $font, $text);//调二维码中字体位置
        //执行合成调整位置
        imagecopyresampled($back, $logo, 139, 140, 0, 0, 65, 65, $logo_w, $logo_h);//调中间logo位置
        imagejpeg($back, $filename);
//        imagedestroy($back);
//        imagedestroy($logo);
    }


    /**
     * 随机生成8位字符串 数组
     * @param $letter
     * @param $num
     * @return array
     */
    public function createCodeNumber($letter = 'ABC', $num = 1, $codesArr = array())
    {

        $numGenerate = array();
        $max = 99999;
        for ($i = 0; $i < $num; $i++) {
            $str = $letter . str_pad(mt_rand(0, $max), 5, 0, STR_PAD_LEFT);
            while (in_array($str, $numGenerate) || in_array($str, $codesArr)) {
                $str = $letter . str_pad(mt_rand(0, $max), 5, 0, STR_PAD_LEFT);
            }
            $numGenerate[$i] = $str;
        }
        return $numGenerate;
    }

    /**
     *
     * @param Request $request
     */
    public function export(Request $request)
    {
        //搜索
        set_time_limit(0);

        $status = intval($request->status);
        $code_number = trim($request->code_number);

        $where = [];

        if ($status != 100) {
            $where[] = ['status', 'like', '%' . $status . '%'];
        }
        if ($code_number) {
            $where[] = ['code_number', 'like', $code_number . '%'];
        }

        //获取要导出的数据
        $list = Codes::where($where)->orderBy('id', 'asc')->get()->toArray();
        foreach ($list as $k => $v) {
            if ($v['code']) {
                $list[$k]['code'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $v['code'];
            }
            if ($v['picture']) {
                $list[$k]['picture'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $v['picture'];
            }
            $list[$k]['ctime'] = $v['ctime'] > 0 ? ' ' . date('Y-m-d H:i:s', $v['ctime']) : '无';
            $list[$k]['binding_time'] = $v['binding_time'] > 0 ? ' ' . date('Y-m-d H:i:s', $v['binding_time']) : '无';
            $list[$k]['status'] = $v['status'] == 1 ? '已使用' : '未使用';

        }

        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:filename=二维码全部信息列表导出.xls");

//        $strexport="ID\topenid\t会员昵称\t性别\t头像\t手机号\t微信\t城市\t省份\t国家\t状态\t添加时间\r";
        $strexport = "ID\t二维码编号\t二维码\t图片\t状态\t绑定时间           \t添加时间                    \r";
        foreach ($list as $row) {
            $strexport .= $row['id'] . "\t";
            $strexport .= $row['code_number'] . "\t";
            $strexport .= $row['code'] . "\t";
            $strexport .= $row['picture'] . "\t";
            $strexport .= $row['status'] . "\t";
            $strexport .= $row['binding_time'] . "\t";
            $strexport .= $row['ctime'] . "\r";
        }
        $strexport = iconv('UTF-8', "GB2312//IGNORE", $strexport);
        exit($strexport);

    }

    /**
     * 选择日期下载图片压缩包
     * @param Request $request
     * @return string
     */
    public function chooseDownload(Request $request)
    {
        if($request->isMethod('post')){
            $choose_date = $request->post('choose_date');
            if(!$choose_date) return jsonReturn(-1, '参数缺失');
            $download_file = $this->getZipper($choose_date);
            $result = $_SERVER['DOCUMENT_ROOT'].$download_file;
            if(!file_exists($result)){
                return jsonReturn(-1,'压缩图片下载失败');
            }
            $download_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].$download_file;
             DB::table('download_log')->insert(['code_date'=>$choose_date,'ctime'=>time()]);
            return jsonReturn(0,'压缩图片下载成功',['url'=>$download_url]);

        }

    }

    /**
     * @return mixed
     */
    public function getZipper($dir = 'qr')
    {
        $zip = new \ZipArchive();

        $img_path = $dir == 'qr' ? public_path('/uploads/'.$dir) : public_path('/uploads/qr/'.$dir);
        if (!is_dir($img_path)) {
            @mkdir($img_path, 0777, true);
        }

        $img_files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($img_path));

        $path = public_path('/uploads/download');

        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        $zip_file = $path . '/'.$dir.'.zip';

        if(!file_exists($zip_file)){
            file_put_contents($zip_file, "");
        }

        $zip->open($zip_file,  \ZipArchive::OVERWRITE | \ZipArchive::CREATE);

        $this->forZip($zip, $img_files, $img_path, '');

        $zip->close();
       
        $return_src = '/uploads/download/'.$dir.'.zip';
        return $return_src;
//        return response()->download($zip_file);
    }

    public function forZip($zip, $files, $file_path, $new_path)
    {

        foreach ($files as $name => $file) {
            // 我们要跳过所有子目录
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                // 用 substr/strlen 获取文件名
                $relativePath = $new_path . substr($filePath, strlen($file_path));
                $zip->addFile($filePath, $relativePath);
            }
        }
    }


}
