@extends('web.common.app')
@section('title')
    网站配置
@endsection
@section('style')

    .layui-input, .layui-textarea {
        width:70%
    }
    .layui-upload-img {
    width: 92px;
    height: 92px;
    margin: 0 10px 10px 0;
    }
    .layui-form{
    padding:20px 20px 0 0;
    }
@endsection
@section('content')
    <div class="layuimini-main" id="config">
        <div class="">
            <form class="layui-form" action="">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                @foreach ($confList as $k => $v)
                    @if($v['type'] ==1)
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{$v['name']}}</label>
                            <div class="layui-input-block">
                                <input type="text" name="{{$v['key']}}" value="{{$v['value']}}" autocomplete="off"
                                       class="layui-input">
                            </div>
                        </div>
                    @endif
                    @if($v['type'] ==2)
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{$v['name']}}</label>
                            <div class="layui-input-block">
                                @foreach ($v['values'] as $val)
                                <input type="checkbox" name="{{$v['key']}}[]"
                                       @if (in_array($val,$v['value'])) checked @endif title="{{$val}}" >
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($v['type'] ==3)
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{$v['name']}}</label>
                            <div class="layui-input-block">
                                @foreach ($v['values'] as $val)
                                <input type="radio" name="{{$v['key']}}"
                                       @if ($v['value'] == $val) checked @endif title="{{$val}}" value="{{$val}}">
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($v['type'] ==4)
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{$v['name']}}</label>
                            <div class="layui-input-block">
                                <select name="{{$v['key']}}" lay-verify="required">
                                    @foreach($v['values'] as $val)
                                        <option value="{{$val}}"
                                                @if ($v['value'] == $val) selected @endif > {{$val}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    @endif
                    @if($v['type'] ==5)
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{$v['name']}}</label>
                            <div class="layui-input-block">
                                <textarea class="layui-textarea" name="{{$v['key']}}">{{$v['value']}}</textarea>

                            </div>
                        </div>
                    @endif
                    @if($v['type'] ==6)
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{$v['name']}}</label>
                            <div class="layui-input-block">
                                <div class="layui-upload">
                                    <input type="hidden" name="{{$v['key']}}" value="{{$v['value']}}">
                                    <button type="button" class="layui-btn image" lay-method="get" lay-data="{key:'{{$v['key']}}'}">上传图片</button>
                                    <div class="layui-upload-list">
                                        <img class="layui-upload-img"
                                             src="{{$v['value']?$v['value']:'/admin/images/addImg.png'}}" id="demo1">
                                        <p id="demoText"></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="saveBtn">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>


        </div>
    </div>




    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>

    <script>
        layui.use(['table', 'jquery', 'form', 'layer','upload'], function () {
            var table = layui.table,
                form = layui.form,
                layer = layui.layer,
                upload = layui.upload,
                $ = layui.jquery;




            //监听提交
            form.on('submit(saveBtn)', function (data) {
                var data = data.field;
                $.ajax({
                    type: "post",
                    url: "{{route('config/editconf')}}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    async: false,
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        layer.msg(data.msg)
                        if (data.code == 0) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000)

                        }
                    }
                });
                return false;
            });

            var uploadInst = upload.render({
                elem: '.image',
                url: "{{route('config/imgupload')}}",
                fileAccept: 'image/*',
                field: 'image',
                exts: "jpg|png|gif|bmp|jpeg|pdf",
                data: { //额外参数
                    'fileType': 'images',
                },
                before:function(a){
                    var key = this.key;
                    this.data.key = key
                },
                done: function (res,index) {
                    //如果上传失败
                    if (res.code == -1) {
                        return layer.msg(res.msg);
                    }
                    console.log(index)
                    //上传成功
                    $('#demo1').attr('src', res.data.src);

                    $("input[name='"+res.data.key+"']").val(res.data.src);
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



@endsection
