<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('layui/css/public.css')}}" media="all">
    <style type="text/css">
        @yield('style')
    </style>
    <script src="{{asset('layui/layui.js')}}" type="text/javascript"></script>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">{{$site_name}}</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item @if ($url =='/')  layui-this @endif"><a href="/">控制台</a></li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="{{$adminInfo['avatar']?$adminInfo['avatar']:'/admin/images/avatar.png'}}"
                         class="layui-nav-img">
                    {{$adminInfo['username']}}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:void(0)" class="baseinfo">基本资料</a></dd>
                    <dd><a href="javascript:void(0)" class="login-out">退出登录</a></dd>
                </dl>
            </li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll" id="">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="menu-area" id="menu-area">
                @foreach($menuList as $k => $v)
                    <li class="layui-nav-item @if(isset($v['children']) && in_array($url,array_column($v['children'],'href'))) layui-nav-itemed @endif   ">
                        <a class="@if ($v['href'] == $url) layui-this @endif" href="@if (!empty($v['href'])){{route($v['href'])}} @else javascript:void(0); @endif" target="@if($v['target'] == '_blank'){{$v['target']}} @endif">{{$v['title']}}</a>
                        @if(isset($v['children']))
                        <dl class="layui-nav-child">
                            @foreach($v['children'] as $ke => $val)
                                <dd class="@if ($val['href'] == $url) layui-this @endif" style="padding-left: 10px;"><a
                                            href="{{route($val['href'])}}" target="@if($val['target'] == '_blank'){{$val['target']}} @endif">{{$val['title']}}</a></dd>
                            @endforeach
                        </dl>
                            @endif
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

    <div class="layui-body">
        <div class="layuimini-container">
            <!-- 内容主体区域 -->
            @yield('content')
        </div>
    </div>

    <div class="layui-footer">
        @yield('footer')
    </div>
</div>
<script>
    //JavaScript代码区域
    layui.use(['element', 'jquery', 'layer', 'tree'], function () {
        var element = layui.element,
            $ = layui.jquery,
            layer = layui.layer;

        $('.login-out').on("click", function () {

            $.get("{{route('logout')}}", {}, function (data) {
                layer.msg(data.msg, {time: 2000, icon: 6}, function () {
                    window.location.href = "{{route('login')}}";
                });
            }, 'json')

        });

        $('.baseinfo').click(function () {
            var index = layer.open({
                title: '修改资料',
                type: 2,
                shade: 0.2,
                maxmin: true,
                shadeClose: true,
                id: 'baseinfo',
                area: ['70%', '70%'],
                content: "{{route('user/baseinfo')}}",
            });
            $(window).on("resize", function () {
                layer.full(index);
            });
        })


    });
</script>
</body>
</html>
