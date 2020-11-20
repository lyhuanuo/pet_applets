<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Menu;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Auth;

class ArticleController extends BaseController
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
        return view('web.article.list',['adminInfo'=>$adminInfo,'menuList'=>$this->menuList,'url'=>$url,'site_name'=>$site_name]);
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
        $offset = ($page-1 )*$limit;
        // 排序
        $key = $request->key ?$request->key :'id';
        $order = $request->order?$request->order:'desc';
        //搜索

        $type = intval($request->type);
        $title = trim($request->title);

        $where = [];
        if($type != 100){
            $where[] = ['type','=', $type];
        }

        if ($title) {
            $where[] = ['title', 'like', '%' . $title . '%'];
        }

        $article = new Article();
        $count=$article->where($where)->count();
        $list = $article->where($where)->offset($offset)->limit($limit)->orderBy($key, $order)->get()->toArray();

        foreach($list as $k =>$v){

            $list[$k]['type'] = $v['type'] == 1?'操作指南':'用户协议';
            $list[$k]['ctime'] = $v['ctime'] > 0 ?date('Y-m-d H:i:s',$v['ctime']) :'无';
        }

        $data = [
            'code'=>0,
            'msg'=>'获取成功',
            'count'=>$count,
            'data'=>$list,
        ];
        return json_encode($data);
    }


    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $article = new Article();
            $data = $request->post();

            $article->title = trim($data['title']);

            $article->content = trim($data['content']);

            $article->type = intval($data['type']);

            $article->ctime = time();
            $add = $article->save();
            if ($add) {
                return jsonReturn(0, '添加文章信息成功');
            }
            return jsonReturn(-1, '添加文章信息失败');
        }

        return view('web.article.add');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $article = new Article();
        $articleInfo = $article->find($id);

        if($request->isMethod('post')){

            $data = $request->post();
            $articleInfo->title = trim($data['title']);

            $articleInfo->content = trim($data['content']);

            $articleInfo->type = intval($data['type']);

            $update = $articleInfo->save();
            if ($update) {
                return jsonReturn(0, '修改文章信息成功');
            }
            return jsonReturn(-1, '修改文章信息失败');
        }


        return view('web.article.edit',['articleInfo'=>$articleInfo]);
    }

    public function del($id)
    {
        $del = Article::destroy($id);
        if ($del) {
            return jsonReturn(0, '删除文章成功');
        } else {
            return jsonReturn(-1, '删除文章失败');
        }
    }


    /**
     * 上传图片
     * @param Request $request
     * @return string
     */
    public function imgUpload(Request $request)
    {
        $fileType = 'images';

        $file = request()->file('file');
        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();    //客户端文件名称..
            $tmpName = $file->getFileName();   //缓存在tmp文件夹中的文件名例如php8933.tmp 这种类型的.
            $realPath = $file->getRealPath();     //这个表示的是缓存在tmp文件夹下的文件的绝对路径
            $entension = $file->getClientOriginalExtension();   //上传文件的后缀.
            $mimeTye = $file->getMimeType();    //也就是该资源的媒体类型
            $newName = $newName = md5(date('ymdhis') . $clientName) . "." . $entension;    //定义上传文件的新名称
            $path = 'uploads/simditor';
            $tree = $path.'/'.$fileType;
            if(file_exists($fileType)){
                mkdir($tree,0777);
            }
            $path = $file->move($tree, $newName);    //把缓存文件移动到制定文件夹
//            return jsonReturn(0,'上传成功',array('src'=>'/'.$path->getPathname()));
            return json_encode(array('success'=>true,'file_path'=>'/'.$path->getPathname()));
        }
        return json_encode(array('success'=>false));


    }


}
