<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理-登录</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('layui/css/admin.css') }}" media="all">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="container layui-anim layui-anim-upbit">
    <div></div>
    <div class="admin-login-background">
        <div class="admin-header">
            <span>登录</span>
        </div>
        <form class="layui-form" method="post">
            <div>
                <i class="layui-icon layui-icon-username admin-icon"></i>
                <input type="text" name="username" placeholder="请输入用户名" autocomplete="off"
                       class="layui-input admin-input admin-input-username"
                       value="{{session('username')?session('username'):''}}">
            </div>
            <div>
                <i class="layui-icon layui-icon-password admin-icon"></i>
                <input type="password" name="password" placeholder="请输入密码" autocomplete="off"
                       class="layui-input admin-input" value="{{session('password')?session('password'):''}}">
            </div>
            <div>
                <input type="text" name="captcha" placeholder="请输入验证码" autocomplete="off"
                       class="layui-input admin-input admin-input-verify" value="">
                <img class="admin-captcha" width="90" height="30" style="cursor: pointer" src="{{captcha_src('flat')}}"
                     onclick="this.src='{{captcha_src("flat")}}'+Math.random()">
            </div>
            <button class="layui-btn admin-button" lay-submit="" lay-filter="login" type="button">登 陆</button>
        </form>
    </div>

</div>
{{--<script src="{{asset('admin/lib/layui-v2.5.5/layui.js')}}" charset="utf-8"></script>--}}
<script src="{{asset('layui/layui.js')}}" charset="utf-8"></script>

<script>
    layui.use(['form', 'layer'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery;

        // 登录过期的时候，跳出ifram框架
        if (top.location != self.location) top.location = self.location;

        // 进行登录操作
        form.on('submit(login)', function (data) {

            data = data.field;
            if ($.trim(data.username) == '') {
                layer.msg('用户名不能为空');
                return false;
            }
            if ($.trim(data.password) == '') {
                layer.msg('密码不能为空');
                return false;
            }
            if ($.trim(data.captcha) == '') {
                layer.msg('验证码不能为空');
                return false;
            }

            $.ajax({
                url: "{{route('dologin')}}",
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{username:$.trim(data.username),password:$.trim(data.password),captcha:$.trim(data.captcha)},
                timeout: 2000,
                success: function (res) {

                    if (res.code == 200) {
                        layer.msg(res.msg, {time: 2000, icon: 6}, function () {
                            window.location.href = "{{ route("/") }}"
                        });
                    } else {
                        layer.msg(res.msg, {time: 2000, icon: 5},function () {
                            $('.admin-captcha').attr('src',"{{captcha_src("flat")}}"+Math.random());
                        });

                    }
                },
                error: function () {
                    layer.msg("请求错误");
                    $('.admin-captcha').attr('src',"{{captcha_src("flat")}}"+Math.random());

                }
            });

            return false;

        });
    });
</script>
</body>
</html>