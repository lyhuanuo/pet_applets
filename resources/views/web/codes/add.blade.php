<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加随机生成二维码</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">

    <style>
        body {
            padding: 10px;
        }

        .layui-upload-img {
            width: 92px;
            height: 92px;
            margin: 0 10px 10px 0;
        }

        hr {
            margin: 30px 0;
        }
    </style>
</head>
<body>

<form class="layui-form layui-form-pane1" action="" lay-filter="first">

    <div class="layui-form-item">
        <label class="layui-form-label">生成数量 *</label>
        <div class="layui-input-inline" style="width:500px">
            <input type="number" name="number" min="0" lay-verify="required|number" lay-reqText="数量不能为空" required
                   placeholder="请输入需要生成的数量" value="0" autocomplete="off" class="layui-input" step="1" >
        </div>
        <div class="layui-form-mid layui-word-aux">请务必输入需要的数量</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">水印文字</label>
        <div class="layui-input-inline" style="width:500px">
            <input type="text" name="text"  lay-verify="text"
                   placeholder="输入水印文字，限10字以内最佳" value="" autocomplete="off" class="layui-input"  >
        </div>
        <div class="layui-form-mid layui-word-aux">水印文字存在将生产水印图，反之没有</div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="save_code">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<br><br><br>
<script src="{{asset('layui/layui.js')}}"></script>
<script>

    layui.use(['form', 'layedit', 'laydate', 'upload'], function () {
        var form = layui.form,
            layer = layui.layer,
            layedit = layui.layedit,
            $ = layui.jquery;
        //自定义验证规则
        form.verify({
            number: function (e) {
                if (!e || isNaN(e)) return "只能填写数字";
                if(e <= 0 ) return "请填写要生成的数量";
            },
        });


        //监听提交
        form.on('submit(save_code)', function (data) {
            $.ajax({
                type : "post",
                url : "{{route('codes/add')}}",
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async : false,
                data : data.field,
                dataType : "json",
                success : function(data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('codes_add');
                            parent.layer.close(index);
                            parent.window.location.reload();

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
