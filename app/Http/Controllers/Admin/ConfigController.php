<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/12
 * Time: 10:10
 */

namespace App\Http\Controllers\Admin;

use App\Conf;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Admin;
use Illuminate\Support\Facades\Auth;

class ConfigController extends BaseController
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

        $adminInfo = Admin::find(Auth::id());

        $url = $request->route()->getName();
        $site_name = $this->site_name->value ? $this->site_name->value : '后台';
        return view('web.config.list',['adminInfo'=>$adminInfo,'menuList'=>$this->menuList,'url'=>$url,'site_name'=>$site_name]);
    }

    /**
     * 获取配置列表信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $conf = new Conf();
        $count = $conf->count();
        $list = $conf->orderBy('sort', 'desc')->get()->toArray();
        foreach ($list as $k => $v) {
            switch ($v['type']){
                case 1:
                    $list[$k]['type'] = '单文本框';
                    break;
                case 2:
                    $list[$k]['type'] = '多选框';
                    break;
                case 3:
                    $list[$k]['type'] = '单选框';
                    break;
                case 4:
                    $list[$k]['type'] = '下拉框';
                    break;
                case 5:
                    $list[$k]['type'] = '多文本框';
                    break;
                case 6:
                    $list[$k]['type'] = '文件';
                    break;
            }

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
     * 添加配置
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $post = $request->post();
            $conf = new Conf();
            $conf->key = trim($post['key']);
            $conf->name = trim($post['name']);
            $conf->type = intval($post['type']);
            $conf->value = trim($post['value']);
            $values = str_replace('，',',',trim($post['values']));
            $conf->values= $values;
            $conf->sort= intval($post['sort']);
            $conf->ctime = time();

            $add = $conf->save();
            if($add){
                return jsonReturn(0,'添加配置成功');
            }
            return jsonReturn(-1,'添加配置失败');

        }
        return view('web.config.add');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {

        $confInfo = Conf::find($id);

        if ($request->isMethod('post')) {

            $data = $request->post();
            $confInfo->key = trim($data['key']);
            $confInfo->name = trim($data['name']);
            $confInfo->type = intval($data['type']);
            $delImg = '';
            if($confInfo->type == 6){
                $delImg = $confInfo->value;
            }

            $confInfo->value = trim($data['value']);
            $values = str_replace('，',',',trim($data['values']));
            $confInfo->values= $values;
            $confInfo->sort= intval($data['sort']);
            $update = $confInfo->save();

            if ($update) {
                @unlink('.' . $delImg);
                return jsonReturn(0, '修改配置信息成功');
            }
            return jsonReturn(-1, '修改配置信息失败');

        }

        return view('web.config.edit', ['confInfo' => $confInfo]);
    }

    /**
     * 删除配置
     * @param $id
     * @return string
     */
    public function del($id)
    {
        $confInfo = Conf::find($id);
        $delImg = '';
        if($confInfo->type == 6) {
            $delImg = $confInfo['value'];
        }
        $del = Conf::destroy($id);
        if ($del) {
            @unlink('.' . $delImg);
            return jsonReturn(0, '删除配置成功');
        } else {
            return jsonReturn(-1, '删除配置失败');
        }
    }

    /**
     * 统一修改网站配置
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editConfig(Request $request)
    {
        $adminInfo = Admin::find(Auth::id());

        $url = $request->route()->getName();
        if($request->isMethod('post')){
            $conf = new Conf();
            $data = $request->post();
            // 复选框空选问题
            $checkFields2D = $conf->where('type',2)->get(['key'])->toArray();
            // 改为一维数组
            $checkFields = array();
            $allFields = array();
            if ($checkFields2D) {
                foreach ($checkFields2D as $k => $v) {
                    $checkFields[] = $v['key'];
                }
            }

            // 处理文字数据
            foreach ($data as $k => $v) {
                if($k == '_token'){
                    continue;
                }
                $allFields[] = $k;
                if(is_array($v)){
                    $confInfo = Conf::where('key',$k)->first(['values']);
                    $values = '';
                    if($confInfo){
                        $valuesArr = explode(',',$confInfo->values);
                        foreach($v as $key => $val){
                            $values .= ','.$valuesArr[$key];
                        }
                        $values = trim($values,',');

                        $conf->where('key',$k)->update(['value' => $values]);
                    }

                }else{
                    $conf->where('key',$k)->update(['value' => trim($v)]);
                }

            }
            // 如果复选框未选中任何一个选项，则设置空
            foreach ($checkFields as $k => $v) {
                if (!in_array($v, $allFields)) {
                    $conf->where('key',$v)->update(['value' => '']);
                }
            }
            return jsonReturn(0,'修改配置成功');

        }
        $confList = Conf::orderBy('sort','desc')->get(['id','key','name','value','values','type','sort'])->toArray();
        foreach($confList as $k =>$v){
            $confList[$k]['values'] = explode(',',$v['values'])? explode(',',$v['values']):array();
            if($v['type'] == 2){
                $confList[$k]['value'] = explode(',',$v['value'])? explode(',',$v['value']):array();
            }
        }
        $site_name = $this->site_name->value ? $this->site_name->value : '后台';
        return view('web.config.editconf',['confList'=>$confList,'adminInfo'=>$adminInfo,'menuList'=>$this->menuList,'url'=>$url,'site_name'=>$site_name]);
    }
    /**
     * 上传头像
     * @param Request $request
     * @return string
     */
    public function imgUpload(Request $request)
    {
        $fileType = $request->fileType;

        $file = request()->file('image');
        $key = $request->key;
        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();    //客户端文件名称..
            $tmpName = $file->getFileName();   //缓存在tmp文件夹中的文件名例如php8933.tmp 这种类型的.
            $realPath = $file->getRealPath();     //这个表示的是缓存在tmp文件夹下的文件的绝对路径
            $entension = $file->getClientOriginalExtension();   //上传文件的后缀.
            $mimeTye = $file->getMimeType();    //也就是该资源的媒体类型
            $newName = $newName = md5(date('ymdhis') . $clientName) . "." . $entension;    //定义上传文件的新名称
            $path = 'uploads';
            $tree = $path.'/'.$fileType;
            if(file_exists($fileType)){
                mkdir($tree,0777);
            }
            $path = $file->move($tree, $newName);    //把缓存文件移动到制定文件夹

            $data = [
                'src'=>'/'.$path->getPathname(),
                'key'=>$key,
            ];
            return jsonReturn(0,'上传成功',$data);
        }
        return jsonReturn(-1,'上传失败');


    }

}