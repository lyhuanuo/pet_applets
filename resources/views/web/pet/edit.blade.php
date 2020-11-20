<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改宠物信息</title>
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

        .btn {
            border-radius: 0px;
            font-weight: 100;
            cursor: pointer;
            display: inline-block;
            padding: 5px;
            font-size: 14px;
            font-family: '微软雅黑'
        }

        .btn-primary {
            color: #fff;
            text-shadow: 0 1px rgba(0, 0, 0, .1);
            background-image: -webkit-linear-gradient(top, #4d90fe 0, #4787ed 100%);
            background-image: -o-linear-gradient(top, #4d90fe 0, #4787ed 100%);
            background-image: -webkit-gradient(linear, left top, left bottom, from(#4d90fe), to(#4787ed));
            background-image: linear-gradient(to bottom, #4d90fe 0, #4787ed 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff4d90fe', endColorstr='#ff4787ed', GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            background-repeat: repeat-x;
            border: 1px solid #3079ed;
        }

        .btn-success {
            color: #fff;
            text-shadow: 0 1px rgba(0, 0, 0, .1);
            background-image: -webkit-linear-gradient(top, #35aa47 0, #35aa47 100%);
            background-image: -o-linear-gradient(top, #35aa47 0, #35aa47 100%);
            background-image: -webkit-gradient(linear, left top, left bottom, from(#35aa47),
            to(#35aa47));
            background-image: linear-gradient(to bottom, #35aa47 0, #35aa47 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff35aa47',
            endColorstr='#ff35aa47', GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            background-repeat: repeat-x;
            border: 1px solid #359947;
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

        .img {
            width: 100px;
            height: 100px;
            margin: 20px;
            cursor: pointer;
        }

        .btn-upload {
            margin: 20px;
            float: left;
            display: block;
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            background: #ebebeb;
            line-height: 100px;
            font-size: 14px;
            text-align: center;
            color: #808080;
            cursor: pointer;
        }

        .box {
            width: 100%;
            height: 100%;
            background: #333;
            position: absolute;
            top: 0px;
            left: 0px;
            margin: 0 auto;
            align-items: center; /*定义body的元素垂直居中*/
            justify-content: center; /*定义body的里的元素水平居中*/
            display: none;
            overflow: hidden
        }

        .box img {
            width: 100%;
            position: absolute;
        }

    </style>
</head>
<body>
<form class="layui-form layui-form-pane1" action="" lay-filter="first" id="formdata" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$petInfo['id']}}">
    <div class="layui-form-item">
        <label class="layui-form-label">已关联二维码</label>
        <div class="layui-input-block">
            <input type="text"  name="code_number" value="{{$petInfo['code_number']}}"  class="layui-input" disabled>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">宠物名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required|name" lay-reqText="宠物名称不能为空" required
                   placeholder="请输入宠物名称" autocomplete="off" class="layui-input" value="{{$petInfo['name']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">宠物品种</label>
        <div class="layui-input-block">
            <input type="text" name="type" lay-verify="required|type" lay-reqText="宠物品种不能为空" required
                   placeholder="请输入宠物品种" autocomplete="off" class="layui-input" value="{{$petInfo['type']}}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">宠物图片</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <div class="gallery" id="gallery">
                    @foreach($petInfo['imgArr'] as $v)
                    <div class="img-item    oldimg" style="float: left;" >
                        <span class="delete" id="{{$v}}">x</span>
                        <img src="{{$v}}" class="img">
                        <input type="hidden" name="oldimg[]" value="{{$v}}">
                    </div>
                    @endforeach

                    <div class="img-item" style="display: inline-block;" id="first-btn-upload">
                        <label for="btn-upload" class="btn-upload" id="btn-upload">点击上传</label>
                        <div style="clear: both;"></div>
                    </div>
                </div>
                <input id="file" type="file" multiple style="display: none">

            </div>

        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">所属会员</label>
        <div class="layui-input-block">
            <select name="member_id" lay-filter="member_id" lay-search lay-verify="required|member_id" >
                @foreach ($memberList as $k => $v)
                    <option value="{{$v['id']}}" @if($v['id'] == $petInfo['member_id']) selected @endif>{{$v['nickname']}}</option>
                @endforeach

            </select>
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label">性别</label>
        <div class="layui-input-block">
            <input type="radio" name="sex" value="1" title="GG" @if($petInfo['sex'] == 1) checked @endif>
            <input type="radio" name="sex" value="2" title="MM" @if($petInfo['sex'] == 2) checked @endif>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">生日</label>
        <div class="layui-input-block">
            <input type="text" name="birthday" id="date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off"
                   class="layui-input" value="{{$petInfo['birthday']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机</label>
        <div class="layui-input-block">
            <input type="tel" name="phone" lay-verify="phone" lay-verType="tips" autocomplete="off"
                   class="layui-input" value="{{$petInfo['phone']}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">微信号</label>
        <div class="layui-input-block">
            <input type="text" name="wx" lay-verify="wx"
                   placeholder="请输入微信号" autocomplete="off" class="layui-input" value="{{$petInfo['wx']}}">
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label">关联状态</label>
        <div class="layui-input-block">
            @if($petInfo['relation'] == 0)
            <input type="radio" name="relation" value="0" title="未关联"  checked >
            @else
            <input type="radio" name="relation" value="1" title="已关联" checked >
            @endif

        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">返家寄语</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入返家寄语" class="layui-textarea" name="remark">{{$petInfo['remark']}}</textarea>
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
    layui.use(['form', 'jquery', 'upload', 'laydate'], function () {
        var form = layui.form,
            layer = layui.layer,
            upload = layui.upload,
            laydate = layui.laydate,
            $ = layui.$;

        // 创建数组保存图片
        var files = new Array();
        var id = 0;

        form.verify({
            phone:function(value){
                if ($.trim(value) != '' && !/^1\d{10}$/.test($.trim(value))) {
                    return '手机号码格式错误';
                }
            },

            member_id:function(value){
                if(value == 0){
                    return '请选择所属的会员';
                }
            }
        });
        //日期
        laydate.render({
            elem: '#date' //指定元素  元素选择器
            , type: 'date'  //选择时间类型 可选值:year(年) month(年月)  date(年月日)  time(时分秒)  datetime(年月日时分秒)
            , format: 'yyyy-MM-dd'  //时间格式  常用时间格式:yyyy-MM-dd HH:mm:ss
            , range: false  //是否开始左侧选择  为true时可以左右选择时间
            , value: "{{$petInfo['birthday']}}"//初始值
            // , min: 0 //几天前或者指定日期'2018-04-28 12:30:00'
            , max: new Date().valueOf() //几天后或者指定日期'2017-04-29 12:30:00'
            , btns: ['confirm'] //选择框右下角显示的按钮 清除-现在-确定
            , done: function (value, date) {//时间回调
                console.log(value);
                console.log(date);
            }
        });

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            var data = data.field
            // 创建FormData根据form
            var formData = new FormData($("#formdata")[0]);
            // 遍历图片数组把图片添加到FormData中
            // var files = document.getElementById("file").files;
            var num = $('#formdata').find('.oldimg').length;

            var maxsize = 0;
            for (var i = 0; i < files.length; i++) {
                formData.append("images[]", files[i]);
                maxsize = maxsize + files[i].size;
            }
            if((files.length + num) >3){
                layer.msg('上传的文件不能超过三张！');
                return false;
            }
            if(maxsize>52428800){
                layer.msg('上传的文件总大小不能超过50MB，请重新上传！');
                return false;
            }
            $.ajax({
                type: 'post',
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{route('pet/edit')}}/"+data.id, // ajax请求路径
                data: formData,
                async: false,
                dataType:"json",
                contentType: false,
                processData: false,
                success: function(data){
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('pet_edit');
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


        // 预览
        function preView(obj) {
            var pimg = obj;
            // var pimg = document.querySelector("img");
            var oImg = document.querySelector(".box img");
            var oBox = document.querySelector(".box");
            // pimg.onclick=function(){
            oBox.style.display = "flex"
            oImg.src = pimg.src
            // }
            oBox.onclick = function () {
                oBox.style.display = "none"
                oImg.src = ''
            }
            var hammer = new Hammer(oImg);//hammer实例化
            hammer.get('pan').set({direction: Hammer.DIRECTION_ALL});//激活pan(移动)功能
            hammer.get('pinch').set({enable: true});//激活pinch(双指缩放)功能
            hammer.on("panstart", function (event) {
                var left = oImg.offsetLeft;
                var tp = oImg.offsetTop;
                hammer.on("panmove", function (event) {
                    oImg.style.left = left + event.deltaX + 'px';
                    oImg.style.top = tp + event.deltaY + 'px';
                });
            })

            hammer.on("pinchstart", function (e) {
                hammer.on("pinchout", function (e) {
                    oImg.style.transition = "-webkit-transform 300ms ease-out";
                    oImg.style.webkitTransform = "scale(2.5)";
                });
                hammer.on("pinchin", function (e) {
                    oImg.style.transition = "-webkit-transform 300ms ease-out";
                    oImg.style.webkitTransform = "scale(1)";
                });
            })
        }

        // 选择图片按钮隐藏input[file]
        $("#btn-upload").click(function () {
            $("#file").trigger("click");
        });
        // 选择图片
        $("#file").change(function () {
            // 获取所有图片
            var img = document.getElementById("file").files;
            // 遍历
            for (var i = 0; i < img.length; i++) {
                // 得到图片
                var file = img[i];
                // 判断是否是图片

                var flag = judgeImgSuffix(file.name);
                if (flag) {

                } else {
                    alert("要求图片格式为png,jpg,jpeg,bmp");
                    return;
                }

                // 把图片存到数组中
                files[id] = file;
                id++;
                // 获取图片路径
                var url = URL.createObjectURL(file);

                // 创建img
                var box = document.createElement("img");
                box.setAttribute("src", url);
                box.className = 'img';
                box.onclick = function () {
                    preView(this);
                };

                // 创建div
                var imgBox = document.createElement("div");
                imgBox.style.float = 'left';
                imgBox.className = 'img-item';

                // 创建span
                var deleteIcon = document.createElement("span");
                deleteIcon.className = 'delete';
                deleteIcon.innerText = 'x';
                // 把图片名绑定到data里面
                deleteIcon.id = img[i].name;
                // 把img和span加入到div中
                imgBox.appendChild(deleteIcon);
                imgBox.appendChild(box);
                // 获取id=gallery的div
                var body = document.getElementsByClassName("gallery")[0];
                // body.appendChild(imgBox);
                var showPlace = document.getElementsByClassName("img-item")[0];
                body.insertBefore(imgBox, showPlace);
                // 点击span事件
                $(deleteIcon).click(function () {
                    // 获取data中的图片名
                    var filename = $(this).attr('id');
                    // 删除父节点
                    $(this).parent().remove();
                    var fileList = Array.from(files);
                    // 遍历数组
                    for (var j = 0; j < fileList.length; j++) {
                        // 通过图片名判断图片在数组中的位置然后删除
                        if (fileList[j].name == filename) {
                            fileList.splice(j, 1);
                            id--;
                            break;
                        }
                    }
                    files = fileList;
                });
            }
        });

        $('.delete').click(function(res){
            $(this).parent().remove();
        });

        // 判断是否是图片类型
        function judgeImgSuffix(path) {
            var index = path.lastIndexOf('.');
            var suffix = "";
            if (index > 0) {
                suffix = path.substring(index + 1);
            }
            if ("png" == suffix || "jpg" == suffix || "jpeg" == suffix || "bmp" == suffix || "PNG" == suffix || "JPG" == suffix || "JPEG" == suffix || "BMP" == suffix) {
                return true;
            } else {
                return false;
            }
        }

    });
</script>
</body>
</html>
