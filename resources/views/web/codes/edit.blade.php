<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改二维码信息</title>
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

        .layui-form {
            padding: 20px 20px 0 0;
        }

        hr {
            margin: 30px 0;
        }

    </style>
</head>
<body>
<form class="layui-form layui-form-pane1" action="" lay-filter="first">
    <input type="hidden" name="id" value="{{$codesInfo['id']}}">
    <div class="layui-form-item">
        <label class="layui-form-label">二维码编号</label>
        <div class="layui-input-inline" style="width:500px">
            <input type="text" name="code_number" lay-verify="required|code_number" lay-reqText="二维码编号不能为空" required
                   placeholder="请输入二维码编号" autocomplete="off" disabled class="layui-input"
                   value="{{$codesInfo['code_number']}}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">二维码</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <input type="hidden" name="code" value="{{$codesInfo['code']}}">
                <img class="layui-upload-img"
                     src="{{$codesInfo['code']?$codesInfo['code']:'/admin/images/avatar.png'}}" >
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">图片</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <input type="hidden" name="code" value="{{$codesInfo['picture']}}">
                <img class="layui-upload-img"
                     src="{{$codesInfo['picture']?$codesInfo['picture']:'/admin/images/avatar.png'}}" >
            </div>
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" value="1" lay-text="已使用|未使用"
                   @if ($codesInfo['status'] == 1) checked @endif>
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
    layui.use(['form', 'jquery', 'upload'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery;
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
                type: "post",
                url: "{{route('codes/edit')}}/" + data.id,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async: false,
                data: data,
                dataType: "json",
                success: function (data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function () {
                            var index = parent.layer.getFrameIndex('codes_edit');
                            parent.layer.close(index);
                            parent.location.reload();

                        }, 2000)

                    }
                }
            });

            return false;
        });

    });
</script>
</body>
</html>