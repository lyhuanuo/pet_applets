<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加菜单信息</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
    <style>
        body {
            background-color: #ffffff;
        }

        .layui-iconpicker-body.layui-iconpicker-body-page .hide {
            display: none;
        }
        .layui-form{
            padding:20px 20px 0 0;
        }
    </style>
</head>
<body>
<form class="layui-form layui-form-pane1" action="" lay-filter="first">

    <div class="layui-form-item">
        <label class="layui-form-label">菜单名称</label>
        <div class="layui-input-block">
            <input type="text" name="title" lay-verify="required|title" lay-reqText="菜单名称不能为空" required
                   placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">父级菜单</label>
        <div class="layui-input-block">
            <select name="pid" lay-verify="required" lay-verType="tips">
                <option value="0">顶级菜单</option>
                @foreach ($menuList as $k => $v)
                <option value="{{$v['id']}}" >{{$v['html'].$v['title']}}</option>
                @endforeach

            </select>
        </div>
    </div>

    <div class="layui-form-item" style="display: none">
        <label class="layui-form-label">菜单图标</label>
        <div class="layui-input-block">
            <input type="text" name="icon" lay-verify="icon" lay-verType="tips" autocomplete="off"
                   class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">跳转路由</label>
        <div class="layui-input-block">
            <input type="text" name="href" lay-verify="href" lay-verType="alert" autocomplete="off"
                   class="layui-input">
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label">跳转打开方式</label>
        <div class="layui-input-block">
            <input type="radio" name="target" value="" title="无"   checked>
            <input type="radio" name="target" value="_self" title="当前页面打开" >
            <input type="radio" name="target" value="_blank" title="打开新的窗口" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-inline">
            <input type="number" name="sort" lay-verify="required|number" placeholder="0" autocomplete="off"
                   class="layui-input" min="0"  step="1" value="0">
        </div>

    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="正常"    checked>
            <input type="radio" name="status" value="0" title="禁用" >
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <textarea name="remark" placeholder="请输入内容" class="layui-textarea"></textarea>
           
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="saveBtn">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<script src="{{asset('layui/layui.js')}}"></script>
<script>
    layui.use(['form', 'jquery'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.$;


        //监听提交
        form.on('submit(saveBtn)', function (data) {

            $.ajax({
                type : "post",
                url : "{{route('menu/add')}}",
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async : false,
                data : data.field,
                dataType : "json",
                success : function(data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('menu_add');
                            parent.layer.close(index);
                            parent.location.reload();

                        },2000)

                    }
                }
            });



            return false;
        });

    });
</script>
</body>
</html>