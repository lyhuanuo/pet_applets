@extends('web.common.app')
@section('title')
    二维码列表
@endsection
@section('style')

@endsection
@section('content')
    <div class="layuimini-main">

        <fieldset class="table-search-fieldset">
            <legend>搜索信息</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">二维码编号</label>
                            <div class="layui-input-inline">
                                <input type="text" name="code_number" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">状态</label>
                            <div class="layui-input-inline">
                                <select name="status" id="">
                                    <option value="100">全部</option>
                                    <option value="1"> 已使用</option>
                                    <option value="0"> 未使用</option>
                                </select>

                            </div>
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-success" lay-submit
                                    lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索
                            </button>
                            <button class="layui-btn layui-btn-danger" lay-event="search_clear">清空搜索</button>
                        </div>
                         <div class="layui-inline">
                            <label class="layui-form-label" style="width:150px">选择下载日期</label>
                            <div class="layui-input-inline">
                                <select name="choose_date" id="choose_date" style="width:200px">
                                    <option value="">请选择</option>
                                    @foreach ($dateList as $value)
                                    <option value="{{$value['date']}}"> {{$value['value']}}@if (in_array($value['date'],$downloadList)) (已下载) @endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <a class="layui-btn layui-btn-sm" id="choose_download" href="javascript:void(0);">选择日期下载</a>
                    </div>
                </form>
            </div>
        </fieldset>
        <table id="list" lay-filter="list"></table>
    </div>

    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
            <button class="layui-btn layui-btn-sm" lay-event="reload">重载</button>
            <button class="layui-btn layui-btn-sm" id="exports_all">全部导出</button>
            <a class="layui-btn layui-btn-sm" id="download" href="{{$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/uploads/download/qr.zip'}}">下载全部二维码图片</a>

        </div>
    </script>



    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit" style="display: none">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="clear">清空</a>
    </script>

    <script type="text/html" id="usernameTpl">
        <a href="" class="layui-table-link"></a>
    </script>

    <script>
        layui.use(['table', 'form','jquery'], function () {
            var table = layui.table,
                form = layui.form,
                $ = layui.jquery;

            //渲染
            table.render({
                elem: '#list'
                , height: 700
                , title: '二维码数据'
                , url: "{{route('codes/getlist')}}"
                , where: {'status': 100}
                , page: {}

                , autoSort: false
                //,loading: false
                , limit: 30
                , limits: [20, 30, 50]
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter','exports']
                , cols: [[
                    {type: 'checkbox', align: 'center'}
                    , {
                        field: 'id',
                        title: 'ID',
                        width: 80,
                        align: 'center',
                        unresize: true,
                        sort: true,
                    }
                    , {field: 'code_number', title: '二维码编号', align: 'center'}
                    , {
                        field: 'code', title: '二维码', align: 'center', width: 150, templet: function (d) {
                            return "<div><img height='50px' src=" + d.code + " ></div>"
                        }
                    }
                    , {
                        field: 'picture', title: '图片', align: 'center', width: 150, templet: function (d) {
                            return "<div><img height ='50px' src=" + d.picture + " ></div>"
                        }
                    }
                    , {field: 'status', title: '状态', align: 'center', width: 100,}
                    , {field: 'binding_time', align: 'center', title: '绑定时间', sort: true,}
                    , {field: 'ctime', title: '添加时间', align: 'center', sort: true,}
                    , {fixed: 'right', title: '操作', align: 'center',toolbar: '#barDemo', width: 150}
                ]]
                , parseData: function (res) { //res 即为原始返回的数据
                    return {
                        "code": '0', //解析接口状态
                        "msg": res.msg, //解析提示文本
                        "count": res.count, //解析数据长度
                        "data": res.data //解析数据列表
                    }
                },
                done: function (res, curr, count) {
                    $('.layui-table-cell').css({'height': 'auto'});
                }

            });

            //工具栏事件
            table.on('toolbar(list)', function (obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'add':
                        var index = layer.open({
                            title: '添加二维码',
                            type: 2,
                            shade: 0.3,
                            maxmin: true,
                            id: 'codes_add',
                            shadeClose: true,
                            area: ['60%', '80%'],
                            content: "{{route('codes/add')}}",
                        });
                        $(window).on("resize", function () {
                            layer.full(index);
                        });
                        break;
                    case 'export':

                        break;
                    case 'getCheckData':
                        var data = checkStatus.data;
                        layer.alert(JSON.stringify(data));
                        break;
                    case 'getCheckLength':
                        var data = checkStatus.data;
                        layer.msg('选中了：' + data.length + ' 个');
                        break;
                    case 'isAll':
                        layer.msg(checkStatus.isAll ? '全选' : '未全选')
                        break;
                    case 'LAYTABLE_TIPS':
                        layer.alert('Table for layui-v' + layui.v);
                        break;
                    case 'reload':
                        table.reload('list', {

                            //,height: 300
                            //,url: 'x'
                        }, 'data');
                        break;
                }
                ;
            });

            table.on('row(list)', function (obj) {
                console.log(obj);
                //layer.closeAll('tips');
            });



            //监听表格复选框选择
            table.on('checkbox(list)', function (obj) {
                console.log(obj)
            });


            //监听单元格编辑
            table.on('edit(list)', function (obj) {
                var value = obj.value //得到修改后的值
                    , data = obj.data //得到所在行所有键值
                    , field = obj.field; //得到字段

                console.log(obj)
            });

            //监听行工具事件
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                //console.log(obj)
                if (obj.event === 'del') {
                    layer.confirm('真的删除该二维码么', function (index) {
                        $.ajax({
                            type: "get",
                            url: "{{route('codes/del')}}/" + data.id,
                            async: false,
                            dataType: "json",
                            success: function (data) {
                                layer.msg(data.msg)
                                if (data.code == 0) {
                                    obj.del();
                                    // obj.close(index)
                                    obj.window.reload();
                                }
                            }
                        });
                    });
                } else if (obj.event === 'edit') {
                    var index = layer.open({
                        title: '修改二维码信息',
                        type: 2,
                        shade: 0.3,
                        maxmin: true,
                        shadeClose: true,
                        id: 'codes_edit',
                        area: ['60%', '80%'],
                        content: "{{route('codes/edit')}}/" + data.id,
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                } else if (obj.event === 'clear') {
                    layer.confirm('真的要清空该二维码相关信息么', function (index) {
                        $.ajax({
                            type: "post",
                            url: "{{route('codes/clear')}}",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            async: false,
                            data: {'id': data.id},
                            dataType: "json",
                            success: function (data) {
                                layer.msg(data.msg)
                                setTimeout(function(){
                                    window.location.reload();
                                },1000)
                            }
                        });
                    });
                }
            });

            //监听排序
            table.on('sort(list)', function (obj) {
                layer.msg('排序成功');
                //服务端排序
                table.reload('list', {
                    initSort: obj
                    //,page: {curr: 1} //重新从第一页开始
                    , where: { //重新请求服务端
                        key: obj.field //排序字段
                        , order: obj.type //排序方式
                    }
                });
            });

            //监听搜索
            form.on('submit(data-search-btn)', function (data) {
                var data = data.field

                //渲染
                table.render({
                    elem: '#list'
                    , height: 700
                    , title: '二维码数据'
                    , url: "{{route('codes/getlist')}}"
                    , where: {'status': data.status,'code_number':data.code_number}
                    , page: {}

                    , autoSort: false
                    //,loading: false
                    , limit: 30
                    , limits: [20, 30, 50]
                    , toolbar: '#toolbarDemo'
                    , defaultToolbar: ['filter', 'exports']
                    , cols: [[
                        {type: 'checkbox', align: 'center'}
                        , {
                            field: 'id',
                            title: 'ID',
                            width: 80,
                            align: 'center',
                            unresize: true,
                            sort: true,
                        }
                        , {field: 'code_number', title: '二维码编号', align: 'center'}
                        , {
                            field: 'code', title: '二维码', align: 'center', width: 150, templet: function (d) {
                                return "<div><img height='50px' src=" + d.code + " ></div>"
                            }
                        }
                        , {
                            field: 'picture', title: '图片', align: 'center', width: 150, templet: function (d) {
                                return "<div><img height ='50px' src=" + d.picture + " ></div>"
                            }
                        }
                        , {field: 'status', title: '状态', align: 'center', width: 100,}
                        , {field: 'binding_time', align: 'center', title: '绑定时间', sort: true,}
                        , {field: 'ctime', title: '添加时间', align: 'center', sort: true,}
                        , {fixed: 'right', title: '操作',align: 'center', toolbar: '#barDemo', width: 150}
                    ]]
                    , parseData: function (res) { //res 即为原始返回的数据
                        return {
                            "code": '0', //解析接口状态
                            "msg": res.msg, //解析提示文本
                            "count": res.count, //解析数据长度
                            "data": res.data //解析数据列表
                        }
                    },
                    done: function (res, curr, count) {
                        $('.layui-table-cell').css({'height': 'auto'});
                    }

                });


                return false;
            });

            $('#exports_all').click(function () {

                var code_number = $.trim($("input[name='code_number']").val());
                var status = $("select[name='status']").val();

                var url = "{{route('codes/export')}}" + "?code_number=" + code_number + "&status=" + status;
                //console.log(url);
                window.open(url);

            });



            var active = {
                parseTable: function () {
                    table.init('parse-table-demo', {
                        limit: 3
                    });
                }
                , add: function () {
                    table.addRow('test')
                }
            };
            $('i').on('click', function () {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
            $('.layui-btn').on('click', function () {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
            //选择日期下载二维码图片
            $('#choose_download').click(function(){
                var  choose_date = $("#choose_date").val();
                if(!choose_date){
                    layer.msg('请选择日期下载');
                    return false;
                }
                $.ajax({
                    type: "post",
                    url: "{{route('codes/chooseDownload')}}",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    async: false,
                    data: {'choose_date': choose_date},
                    dataType: "json",
                    success: function (data) {
                        layer.msg(data.msg);
                        if(data.code == 0){
                            download_file(data.data.url)
                        }
                        setTimeout(function(){
                            window.location.reload();
                        },2000);
                    }
                });
            });
        });
        function download_file(url)
        {

            if(typeof(download_file.iframe)== "undefined")
            {
                var iframe = document.createElement("iframe");
                download_file.iframe = iframe;
                document.body.appendChild(download_file.iframe);
            }
            download_file.iframe.src = url;
            download_file.iframe.style.display = "none";

        }
    </script>



@endsection
