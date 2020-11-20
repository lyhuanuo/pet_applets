<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改管理员</title>
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
    <input type="hidden" name="id" value="{{$adminInfo['id']}}">
    <div class="layui-form-item">
        <label class="layui-form-label">管理员名称</label>
        <div class="layui-input-inline" style="width:500px">
            <input type="text" name="username" lay-verify="required|username" lay-reqText="管理员名称不能为空" required
                   placeholder="请输入管理员名称" autocomplete="off" class="layui-input" value="{{$adminInfo['username']}}">
        </div>
        <div class="layui-form-mid layui-word-aux">请务必填写管理员名称</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline" style="width:500px">
            <input type="password" name="password" lay-verify="password" placeholder="不修改无需填写" autocomplete="off"
                   class="layui-input">
        </div>

    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">头像</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <input type="hidden" name="avatar" value="{{$adminInfo['avatar']}}">
                <button type="button" class="layui-btn" id="avatar"  lay-method="get">上传头像</button>

                <div class="layui-upload-list">
                    <img class="layui-upload-img" src="{{$adminInfo['avatar']?$adminInfo['avatar']:'/admin/images/avatar.png'}}" id="demo1">
                    <p id="demoText"></p>
                </div>

            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机</label>
        <div class="layui-input-block">
            <input type="tel" name="phone" lay-verify="phone" lay-verType="tips" autocomplete="off"
                   class="layui-input" value="{{$adminInfo['phone']}}">
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label">性别</label>
        <div class="layui-input-block">
            <input type="radio" name="sex" value="0" title="保密" @if ($adminInfo['sex'] == 0) checked @endif>
            <input type="radio" name="sex" value="1" title="男" @if ($adminInfo['sex'] == 1) checked @endif>
            <input type="radio" name="sex" value="2" title="女" @if ($adminInfo['sex'] == 2) checked @endif>
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" value="1" lay-text="正常|禁用" @if ($adminInfo['status'] == 1) checked @endif>
        </div>
    </div>


    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入备注" class="layui-textarea" name="remark">{{$adminInfo['remark']}}</textarea>
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
            upload = layui.upload,
            $ = layui.jquery;
        var preg = /(.+){6,12}$/;
        //自定义验证规则
        form.verify({
            username: function (value) {
                if (value.length < 4) {
                    return '管理员名称长度少于4位';
                }
            },
            password: function (value) {
                if ($.trim(value) != '' && !preg.test($.trim(value))) {
                    return '密码必须6到12位';
                }
            },
            phone:function(value){
                if ($.trim(value) != '' && !/^1\d{10}$/.test($.trim(value))) {
                    return '手机号码格式错误';
                }
            }
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
                url : "{{route('user/edit')}}/"+data.id,
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async : false,
                data : data,
                dataType : "json",
                success : function(data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('user_edit');
                            parent.layer.close(index);
                            parent.location.reload();

                        },2000)

                    }
                }
            });

            return false;
        });

        var uploadInst = upload.render({
            elem: '#avatar',
            url: "{{route('user/imgupload')}}",
            fileAccept: 'image/*',
            field: 'image',
            exts: "jpg|png|gif|bmp|jpeg|pdf",
            data: { //额外参数
                'fileType': 'images',
            },
            done: function (res) {
                //如果上传失败
                if (res.code == -1) {
                    return layer.msg(res.msg);
                }
                //上传成功
                $('#demo1').attr('src', res.data);
                $("input[name='avatar']").val(res.data);
                $('#demoText').html('');
            }
            , error: function () {
                this.item.html('重选上传');
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function () {
                    uploadInst.upload();
                });
            }

        });

    });
</script>
</body>
</html>