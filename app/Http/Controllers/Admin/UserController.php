<?php

namespace App\Http\Controllers\Admin;

use App\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BaseController;

class UserController extends BaseController
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
        return view('web.user.list',['adminInfo'=>$adminInfo,'menuList'=>$this->menuList,'url'=>$url,'site_name'=>$site_name]);
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
        $offset = ($page-1 )*$limit;
        // 排序
        $key = $request->key ?$request->key :'id';
        $order = $request->order?$request->order:'desc';
        //搜索

        $status = intval($request->status);
        $username = trim($request->username);
        $sex = intval($request->sex);
        $where = [];
        if($status != 100){
            $where[] = ['status','=', $status];
        }
        if($sex != 100){
            $where[] = ['sex','=', $sex];
        }
        if ($username) {
            $where[] = ['username', 'like', '%' . $username . '%'];
        }

        $admin = new Admin();
        $count=$admin->where($where)->count();
        $list = $admin->where($where)->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();

        foreach($list as $k =>$v){

            $list[$k]['status'] = $v['status'] == 1?'正常':'禁用';
            $list[$k]['sex'] = $v['sex'] == 0?'保密':($v['sex'] == 1?'男':'女');
            $list[$k]['ctime'] = $v['ctime'] > 0 ?date('Y-m-d H:i:s',$v['ctime']) :'无';
        }

        $data = [
            'code'=>200,
            'msg'=>'获取成功',
            'count'=>$count,
            'data'=>$list,
        ];
        return json_encode($data);
    }

    /**
     * 添加管理员
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function add(Request $request)
    {

        if($request->isMethod('post')){
            $admin = new Admin();
            $data = $request->post();
            $count = $admin->where('username','=',trim($data['username']))->count();
            if($count > 0){
                return jsonReturn(-1, '管理员名称已存在，无需再添加');
            }
            $admin->username = trim($data['username']);
            $admin->password = bcrypt(trim($data['password']));
            $admin->avatar = trim($data['avatar']);
            $admin->phone = trim($data['phone']);
            if(isset($data['status'])){
                $admin->status = intval($data['status']);
            }else{
                $admin->status = 0;
            }
            $admin->sex = intval($data['sex']);
            $admin->remark = trim($data['remark']);
            $admin->ctime = time();
            $add = $admin->save();
            if ($add) {
                return jsonReturn(0, '添加管理员成功');
            }
            return jsonReturn(-1, '添加管理员失败');
        }

        return view('web.user.add');
    }

    /**
     * 修改管理员
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function edit(Request $request,$id)
    {
        $admin = new Admin();
        $adminInfo = $admin->find($id);

        if($request->isMethod('post')){
            $data = $request->post();
            $count = $admin->where('username','=',trim($data['username']))->where('id','!=',$id)->count();
            if($count > 0) return jsonReturn(-1, '管理员名称已存在，修改失败');

            $adminInfo->username = trim($data['username']);
            if(isset($data['password']) && !empty(trim($data['password']))){
                $adminInfo->password = bcrypt(trim($data['password']));
            }
            $delImg =  '';
            if(!empty(trim($data['avatar'])) && trim($data['avatar']) != $adminInfo->avatar){
                $delImg = $adminInfo->avatar;
                $adminInfo->avatar = trim($data['avatar']);
            }
            $adminInfo->phone = trim($data['phone']);
            if(isset($data['status'])){
                $adminInfo->status = intval($data['status']);
            }else{
                $adminInfo->status = 0;
            }
            $adminInfo->sex = intval($data['sex']);
            $adminInfo->remark = trim($data['remark']);
            $save = $adminInfo->save();
            if ($save) {
                @unlink('.'.$delImg);
                return jsonReturn(0, '修改管理员成功');
            }
            return jsonReturn(-1, '修改管理员失败');
        }

        return view('web.user.edit',['adminInfo'=>$adminInfo]);
    }

    /**
     * 删除管理员
     * @param $id
     * @return string
     */
    public function del($id)
    {
        if($id == Auth::id()) return jsonReturn(-1, '不能删除管理员本身');
        $adminInfo = Admin::find($id);
        $delImg = $adminInfo->avatar;
        $del = Admin::destroy($id);
        if ($del) {
            @unlink('.'.$delImg);
            return jsonReturn(0, '删除管理员成功');
        } else {
            return jsonReturn(-1, '删除管理员失败');
        }

    }



    /**
     * 获取当前管理员信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function baseInfo(Request $request)
    {
        $admin = new Admin();
        $userInfo = $admin->find(Auth::id());
        if($request->isMethod('post')){
            $data = $request->post();
            $count = $admin->where('username','=',trim($data['username']))->where('id','!=',Auth::id())->count();
            if($count > 0){
                return jsonReturn(-1, '管理员名称已存在，修改失败');
            }
            $userInfo->username = trim($data['username']);
            if(isset($data['password']) && !empty(trim($data['password']))){
                $userInfo->password = bcrypt(trim($data['password']));
            }

            $userInfo->phone = trim($data['phone']);
            if(isset($data['status'])){
                $userInfo->status = intval($data['status']);
            }else{
                $userInfo->status = 0;
            }

            $userInfo->sex = intval($data['sex']);
            $userInfo->remark = trim($data['remark']);
            $delImg = '';
            if(!empty(trim($data['avatar'])) && $userInfo->avatar != trim($data['avatar'])){
                $delImg = $userInfo->avatar;
                $userInfo->avatar =  trim($data['avatar']);
            }

            $save =$userInfo->save();
            if($save){
                @unlink('.'.$delImg);
                return jsonReturn(0,'修改资料成功');
            }
            return jsonReturn(-1,'修改资料失败');

        }

        return view('web.user.edit_baseinfo',['userInfo'=>$userInfo]);

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
            return jsonReturn(0,'上传成功','/'.$path->getPathname());
        }
        return jsonReturn(-1,'上传失败');


    }


}
