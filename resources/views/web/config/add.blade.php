<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加配置信息</title>
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
        <label class="layui-form-label">配置标识 *</label>
        <div class="layui-input-block">
            <input type="text" name="key" lay-verify="required|key" lay-reqText="配置标识不能为空" required
                   placeholder="请输入配置标识" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">配置名称 *</label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required|name" lay-reqText="配置名称不能为空" required
                   placeholder="请输入配置名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">类型</label>
        <div class="layui-input-block">
            <select name="type" lay-verify="required" lay-verType="tips">
                <option value="1" selected >单文本框</option>
                <option value="2">多选框</option>
                <option value="3">单选框</option>
                <option value="4">下拉框</option>
                <option value="5">多文本框</option>
                <option value="6">文件</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">默认配置值</label>
        <div class="layui-input-block">
            <input type="text" name="value" lay-verify="value"
                   placeholder="默认配置值" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">配置可选值</label>
        <div class="layui-input-block">
            <textarea placeholder="多选框和单选框请配置可选值，多个用逗号隔开" class="layui-textarea" name="values"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-block">
            <input type="number" name="sort" min="0" step="1" value="0" lay-verify="sort" lay-reqText="排序不能为空" required  autocomplete="off" class="layui-input">
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
            var data = data.field;
            $.ajax({
                type : "post",
                url : "{{route('config/add')}}",
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async : false,
                data : data,
                dataType : "json",
                success : function(data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('config_add');
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