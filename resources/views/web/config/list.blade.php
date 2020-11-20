@extends('web.common.app')
@section('title')
    网站配置列表
@endsection
@section('style')

@endsection
@section('content')
    <div class="layuimini-main">

        <table id="list" lay-filter="list"></table>
    </div>

    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>

            <button class="layui-btn layui-btn-sm" lay-event="reload">重载</button>
        </div>
    </script>



    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>

    <script>
        layui.use(['table', 'jquery'], function () {
            var table = layui.table,
                $ = layui.jquery;

            //渲染
            table.render({
                elem: '#list'
                , height: 700
                , title: '配置数据'
                , url: "{{route('config/getlist')}}"
                , page: false
                , autoSort: false
                , totalRow: false
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter']
                // , skin: 'line'
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
                    , {field: 'key', align: 'center', title: '配置标识',width:150}
                    , {field: 'name',  align: 'center',title: '配置名称', }
                    , {field: 'value',  align: 'center',title: '配置值'}
                    , {field: 'values', align: 'center', title: '配置可选值'}
                    , {field: 'type', align: 'center', title: '配置类型',width:150}
                    , {field: 'sort',  align: 'center',title: '排序',width:80,sort:true}
                    , {field: 'ctime',  align: 'center',title: '添加时间', width: 200, sort: true,}
                    , {fixed: 'right', align: 'center', title: '操作', toolbar: '#barDemo', width: 150}
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
                            title: '添加配置',
                            type: 2,
                            shade: 0.3,
                            maxmin: true,
                            shadeClose: true,
                            id: 'config_add',
                            area: ['60%', '80%'],
                            content: "{{route('config/add')}}",
                        });
                        $(window).on("resize", function () {
                            layer.full(index);
                        });
                        break;
                    case 'reload':
                        table.reload('list', {
                            // page: {curr: 1}
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


            //监听行工具事件
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                //console.log(obj)
                if (obj.event === 'del') {
                    layer.confirm('真的删除该配置么', function (index) {
                        $.ajax({
                            type: "get",
                            url: "{{route('config/del')}}/" + data.id,
                            async: false,
                            dataType: "json",
                            success: function (data) {
                                layer.msg(data.msg)
                                if (data.code == 0) {
                                    obj.del();
                                    // obj.close(index)
                                    window.location.reload();
                                }
                            }
                        });
                    });
                } else if (obj.event === 'edit') {
                    var id = data.id;
                    var index = layer.open({
                        title: '修改配置信息',
                        type: 2,
                        shade: 0.3,
                        maxmin: true,
                        shadeClose: true,
                        id: 'config_edit',
                        area: ['60%', '80%'],
                        content: "{{route('config/edit')}}/" + id,
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });

                }
            });

            //监听排序
            table.on('sort(list)', function (obj) {
                console.log(this)

                //return;
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


            var active = {
                parseTable: function () {
                    table.init('parse-table-demo', {
                        limit: 3
                    });
                }
                , add: function () {
                    table.addRow('list')
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
        });
    </script>



@endsection
