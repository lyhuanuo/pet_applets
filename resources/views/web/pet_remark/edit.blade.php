<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改返家寄语模板信息</title>
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
        .layui-upload-img {
            width: 92px;
            height: 92px;
            margin: 0 10px 10px 0;
        }
        .layui-form{
            padding:20px 20px 0 0;
        }
        hr {
            margin: 30px 0;
        }

    </style>
</head>
<body>
<form class="layui-form layui-form-pane1" action="" lay-filter="first">
     <input type="hidden" name="id" value="{{$templateInfo['id']}}">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" lay-verify="required|title" lay-reqText="标题不能为空" required
                   placeholder="请输入标题" autocomplete="off" class="layui-input" value="{{$templateInfo['title']}}">
        </div>
    </div>
   <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入备注" class="layui-textarea" name="remark">{{$templateInfo['remark']}}</textarea>
        </div>
    </div>
     <div class="layui-form-item" pane>
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" value="1" lay-text="正常|禁用" @if ($templateInfo['status'] == 1) checked @endif>
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
    layui.use(['form', 'jquery','upload'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery;
        var preg = /(.+){6,12}$/;
        //自定义验证规则
        form.verify({
            title: function (value) {
                if (value.length < 2) {
                    return '标题长度少于2位';
                }
            },
        });
        //事件监听

        form.on('checkbox', function (data) {
            console.log(this.checked, data.elem.checked);
        });

        form.on('radio', function (data) {
            console.log(data.value);
        });

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            var data = data.field;
            $.ajax({
                type : "post",
                url : "{{route('petremark/edit')}}/"+data.id,
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async : false,
                data : data,
                dataType : "json",
                success : function(data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('remark_edit');
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