@extends('web.common.app')

@section('title', '返家寄语列表')

@section('content')
    <div class="layuimini-container layuimini-page-anim">
        <div class="layuimini-main">


            <script type="text/html" id="toolbarDemo">
                <div class="layui-btn-container">

                    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
                    <button class="layui-btn layui-btn-sm" lay-event="reload">重载</button>
                </div>
            </script>

            <table class="layui-hide" id="list" lay-filter="list"></table>

            <script type="text/html" id="currentTableBar">
                <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="del">删除</a>
            </script>
        </div>
    </div>

    <script>

        layui.use(['table', 'jquery', 'form'], function () {
            var table = layui.table,
                form = layui.form,
                $ = layui.jquery;

            //渲染
            table.render({
                elem: '#list',
                height: 700,
                title: '返家寄语模板数据',
                url: "{{route('petremark/getlist')}}",
                where: {'status': 100, 'sex': 100},
                autoSort: false,
                totalRow: false,
                toolbar: '#toolbarDemo',
                defaultToolbar: ['filter'],
                cols: [[
                    {type: 'checkbox', align: 'center'},
                    {field: 'id', align: 'center', width: 80, title: 'ID', sort: true},
                    {field: 'title', align: 'center', width: 200,title: '模板标题'},
                    {field: 'remark', align: 'center', title: '模板内容'},
                    {field: 'status', align: 'center', title: '状态', width: 80},
                    {fixed: 'right', align: 'center', title: '操作', toolbar: '#currentTableBar', width: 200}
                ]],
                limits: [10, 20, 30],
                limit: 10,
                page: {}
                // ,skin: 'line'
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
                            title: '添加返家寄语模板',
                            type: 2,
                            shade: 0.3,
                            maxmin: true,
                            shadeClose: true,
                            id: 'remark_add',
                            area: ['60%', '80%'],
                            content: "{{route('petremark/add')}}",
                        });
                        $(window).on("resize", function () {
                            layer.full(index);
                        });
                        break;

                    case 'reload':
                        table.reload('list', {
                            page: {curr: 1}
                            //,height: 300
                            //,url: 'x'
                        }, 'data');
                        break;
                }
            });

            //监听行工具事件
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                if (obj.event === 'del') {
                    layer.confirm('真的删除该返家寄语模板么', function (index) {

                        $.ajax({
                            type: "get",
                            url: "{{route('petremark/del')}}/" + data.id,
                            async: false,

                            dataType: "json",
                            success: function (data) {
                                layer.msg(data.msg)
                                if (data.code == 0) {
                                    window.location.reload();
                                }
                            }
                        });
                    });
                } else if (obj.event === 'edit') {
                    var id = data.id;
                    var index = layer.open({
                        title: '修改寄语模板信息',
                        type: 2,
                        shade: 0.3,
                        maxmin: true,
                        shadeClose: true,
                        id: 'remark_edit',
                        area: ['60%', '80%'],
                        content: "{{route('petremark/edit')}}/" + id,
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