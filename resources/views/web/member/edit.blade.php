<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改会员信息</title>
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

    </style>
</head>
<body>
<form class="layui-form layui-form-pane1" action="" lay-filter="first">
    <input type="hidden" name="id" value="{{$memberInfo['id']}}">
   <div class="layui-form-item" pane>
        <label class="layui-form-label">用户类型 * </label>
        <div class="layui-input-block">
            <input type="radio" name="member_type" value="0" title="微信用户" @if ($memberInfo['member_type'] == 0) checked @endif>
            <input type="radio" name="member_type" value="1" title="支付宝用户" @if ($memberInfo['member_type'] == 1) checked @endif>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">openid *</label>
        <div class="layui-input-block">
            <input type="text" name="openid" lay-verify="required|openid" lay-reqText="openid不能为空"
                   placeholder="请输入openid" autocomplete="off" class="layui-input" disabled value="{{$memberInfo['openid']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">昵称 *</label>
        <div class="layui-input-block">
            <input type="text" name="nickname" lay-verify="required|nickname" lay-reqText="昵称不能为空" required
                   placeholder="请输入昵称" autocomplete="off" class="layui-input"  value="{{$memberInfo['nickname']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-block">
            <input type="text" name="realname" lay-verify="realname"
                   placeholder="请输入真实姓名" autocomplete="off" class="layui-input"  value="{{$memberInfo['realname']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">头像</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <input type="hidden" name="avatar" value="{{$memberInfo['avatar']}}">
                <button type="button" class="layui-btn" id="avatar"  lay-method="get">上传头像</button>

                <div class="layui-upload-list">
                    <img class="layui-upload-img" src="{{$memberInfo['avatar']?$memberInfo['avatar']:'/admin/images/avatar.png'}}" id="demo1">
                    <p id="demoText"></p>
                </div>

            </div>
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label">性别</label>
        <div class="layui-input-block">
            <input type="radio" name="sex" value="0" title="保密" @if ($memberInfo['sex'] == 0) checked @endif>
            <input type="radio" name="sex" value="1" title="男" @if ($memberInfo['sex'] == 1) checked @endif>
            <input type="radio" name="sex" value="2" title="女" @if ($memberInfo['sex'] == 2) checked @endif>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">手机</label>
        <div class="layui-input-block">
            <input type="tel" name="phone" lay-verify="phone" lay-verType="tips" autocomplete="off"
                   class="layui-input" value="{{$memberInfo['phone']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">微信号</label>
        <div class="layui-input-block">
            <input type="text" name="wx" lay-verify="wx"
                   placeholder="请输入微信号" autocomplete="off" class="layui-input"  value="{{$memberInfo['wx']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">城市</label>
        <div class="layui-input-block">
            <input type="text" name="city" lay-verify="city" placeholder="请输入城市"
                   autocomplete="off" class="layui-input"  value="{{$memberInfo['city']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">省份</label>
        <div class="layui-input-block">
            <input type="text" name="province" lay-verify="province" placeholder="请输入省份"
                   autocomplete="off" class="layui-input"  value="{{$memberInfo['province']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">国家</label>
        <div class="layui-input-block">
            <input type="text" name="country" lay-verify="country" placeholder="请输入国家"
                   autocomplete="off" class="layui-input"  value="{{$memberInfo['country']}}">
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" value="1" lay-text="正常|禁用" @if ($memberInfo['status'] == 1) checked @endif>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入备注" class="layui-textarea" name="remark">{{$memberInfo['remark']}}</textarea>
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
            $ = layui.$;

        //自定义验证规则
        form.verify({
            nickname: function (value) {
                if (value.length < 1) {
                    return '昵称长度少于1位';
                }
            },

            phone:function(value){
                if ($.trim(value) != '' && !/^1\d{10}$/.test($.trim(value))) {
                    return '手机号码格式错误';
                }
            }
        });

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
                url : "{{route('member/edit')}}/"+data.id,
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async : false,
                data : data,
                dataType : "json",
                success : function(data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('member_edit');
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