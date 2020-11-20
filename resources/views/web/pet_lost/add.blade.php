<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加丢失宠物信息</title>
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

        .layui-form {
            padding: 20px 20px 0 0;
        }


        .gallery .img-item {
            position: relative;
            cursor: pointer;
        }

        .gallery .img-item .delete {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            color: #fff;
            background: rgba(232, 0, 0, 0.7);
            line-height: 20px;
            text-align: center;
            border-radius: 50%;
            top: 25px;
            right: 25px;
            cursor: pointer;
        }


        .box img {
            width: 100%;
            position: absolute;
        }

    </style>
</head>
<body>
<form class="layui-form layui-form-pane1" action="" lay-filter="first" >

    <div class="layui-form-item" pane>
        <label class="layui-form-label">选择丢失宠物</label>
        <div class="layui-input-inline" style="width:80%">
            <select name="pet_id" lay-filter="pet_id" lay-search lay-verify="pet_id" required>
                <option value="0">选择丢失宠物</option>
                @foreach ($petList as $k => $v)
                    <option value="{{$v['id']}}">{{$v['name']}}</option>
                @endforeach

            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">丢失时间</label>
        <div class="layui-input-block">
            <input type="text" name="lost_time" id="date" lay-verify="datetime" placeholder="yyyy-MM-dd HH:mm:ss" autocomplete="off"
                   class="layui-input" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系手机号</label>
        <div class="layui-input-block">
            <input type="tel" name="phone" lay-verify="phone" lay-verType="tips" autocomplete="off"
                   class="layui-input" placeholder="请输入手机号">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">联系微信号</label>
        <div class="layui-input-block">
            <input type="text" name="wx" lay-verify="wx"
                   placeholder="请输入微信号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">悬赏金额</label>
        <div class="layui-input-block">
            <input type="number" name="amount"  step="0.01" min="0"
                   placeholder="请输入悬赏金额" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">丢失地址</label>
        <div class="layui-input-block">
            <input type="text" name="address" lay-verify="name" lay-reqText="丢失地址不能为空"
                   placeholder="请输入丢失地址" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" name="status" lay-skin="switch" value="1" lay-text="已找回|丢失中">
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
    layui.use(['form', 'jquery', 'laydate'], function () {
        var form = layui.form,
            layer = layui.layer,
            laydate = layui.laydate,
            $ = layui.$;


        form.verify({
            phone:function(value){
                if ($.trim(value) != '' && !/^1\d{10}$/.test($.trim(value))) {
                    return '手机号码格式错误';
                }
            },
            pet_id:function(value){
                if(value == 0){
                    return '请选择丢失的宠物';
                }
            },


        });
        //日期

        laydate.render({
            elem: '#date' //指定元素  元素选择器
            , type: 'datetime'  //选择时间类型 可选值:year(年) month(年月)  date(年月日)  time(时分秒)  datetime(年月日时分秒)
            , format: 'yyyy-MM-dd HH:mm:ss'  //时间格式  常用时间格式:yyyy-MM-dd HH:mm:ss
            , range: false  //是否开始左侧选择  为true时可以左右选择时间
            , value: new Date() //初始值 今天
            // , min: 0 //几天前或者指定日期'2018-04-28 12:30:00'
            , max: new Date().valueOf()//几天后或者指定日期'2017-04-29 12:30:00'
            , btns: ['confirm'] //选择框右下角显示的按钮 清除-现在-确定
            , done: function (value, date) {//时间回调
                console.log(value);
                console.log(date);
            }
        });

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            $.ajax({
                type: 'post',
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{route('petlost/add')}}", // ajax请求路径
                data: data.field,
                async: false,
                dataType:"json",
                success: function(res){
                    layer.msg(res.msg)
                    if (res.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('pet_lost_add');
                            parent.layer.close(index);
                            parent.location.reload();

                        },2000)

                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.msg("发生错误!");
                }
            });

            return false;
        });


    });
</script>
</body>
</html>
