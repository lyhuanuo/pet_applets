<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加文章</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="{{asset('layui/css/layui.css')}}">

    {{--<link rel="stylesheet" href="{{asset('styles/app.css')}}">--}}
    {{--<link rel="stylesheet" href="{{asset('styles/mobile.css')}}">--}}
    <link rel="stylesheet" href="{{asset('styles/simditor.css')}}">
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

        hr {
            margin: 30px 0;
        }
        .layui-form{
            padding:20px 20px 0 0;
        }

    </style>
</head>
<body>
<form class="layui-form layui-form-pane1" action="" lay-filter="first">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" lay-verify="required|title" lay-reqText="标题不能为空" required
                   placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label">类型</label>
        <div class="layui-input-block">
            <input type="radio" name="type" value="1" title="操作指南" checked>
            <input type="radio" name="type" value="2" title="用户协议" >
        </div>
    </div>



    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
             <textarea name="content" id="editor" class="layui-hide">


             </textarea>

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
<script src="{{asset('js/jquery-2.0.3.min.js')}}"></script>
<script src="{{asset('scripts/module.js')}}"></script>
<script src="{{asset('scripts/uploader.js')}}"></script>
<script src="{{asset('scripts/hotkeys.js')}}"></script>
<script src="{{asset('scripts/simditor.js')}}"></script>


<script>
    layui.use(['form', 'jquery','upload','layedit'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery;

        {{--var layeditcontent = layedit.build('content', {--}}
            {{--//hideTool: ['image']--}}
            {{--uploadImage: {--}}
                {{--url: "{{route('article/imgupload')}}"--}}
                {{--,type: 'post'--}}
            {{--}--}}
            {{--,tool: [ 'html', 'code', 'strong',  'underline', 'del', 'addhr', '|', 'fontFomatt', 'colorpicker', 'face'--}}
                {{--, '|', 'left', 'center', 'right', '|', 'link', 'unlink','image', 'image_alt', '|', 'fullScreen']--}}
            {{--,height: 450--}}
        {{--});--}}


        //事件监听
        form.on('radio', function (data) {
            console.log(data.value);
        });

        //监听提交
        form.on('submit(saveBtn)', function (data) {
            $.ajax({
                type : "post",
                url : "{{route('article/add')}}",
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                async : false,
                data : data.field,
                dataType : "json",
                success : function(data) {
                    layer.msg(data.msg)
                    if (data.code == 0) {
                        setTimeout(function(){
                            var index = parent.layer.getFrameIndex('article_add');
                            parent.layer.close(index);
                            parent.location.reload();

                        },2000)

                    }
                }
            });

            return false;
        });


        var editor = new Simditor({
            toolbar: [
                'title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale',
                'color', '|', 'ol', 'ul', 'blockquote', 'code', 'table', '|', 'link',
                'image', 'hr', '|', 'alignment'
            ],
            textarea: '#editor',
            placeholder: '写点什么...',
            imageButton: ['upload'],
            upload: {
                url: "{{route('article/imgupload')}}",
                fileKey: 'file',
                leaveConfirm: '正在上传文件..',
                connectionCount: 3
            }
        });


    });
</script>

</body>
</html>