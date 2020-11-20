@extends('web.common.app')

@section('title', '管理员列表')

@section('content')
    <div class="layuimini-container layuimini-page-anim">
        <div class="layuimini-main">

            <fieldset class="table-search-fieldset">
                <legend>搜索信息</legend>
                <div style="margin: 10px 10px 10px 10px">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">管理员名称</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="username" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-inline">
                                    <select name="sex" id="">
                                        <option value="100"> 全部</option>
                                        <option value="0"> 保密</option>
                                        <option value="1"> 男</option>
                                        <option value="2"> 女</option>
                                    </select>

                                </div>
                            </div>
                            <div class="layui-inline">
                                <label class="layui-form-label">状态</label>
                                <div class="layui-input-inline">
                                    <select name="status" id="">
                                        <option value="100"> 全部</option>
                                        <option value="1"> 正常</option>
                                        <option value="0"> 禁用</option>
                                    </select>

                                </div>
                            </div>
                            <div class="layui-inline">
                                <button type="submit" class="layui-btn layui-btn-success" lay-submit
                                        lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索
                                </button>
                                <button class="layui-btn layui-btn-danger" lay-event="search_clear">清空搜索</button>
                            </div>
                        </div>
                    </form>
                </div>
            </fieldset>

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
            <script type="text/html" id="usernameTpl">
                <a href="" class="layui-table-link"></a>
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
                title: '管理员数据',
                url: "{{route('user/getlist')}}",
                where: {'status': 100, 'sex': 100},
                autoSort: false,
                totalRow: false,
                toolbar: '#toolbarDemo',
                defaultToolbar: ['filter'],
                cols: [[
                    {type: 'checkbox', align: 'center'},
                    {field: 'id', align: 'center', width: 80, title: 'ID', sort: true},
                    {field: 'username', align: 'center', title: '管理员名称'},
                    {
                        field: 'avatar', align: 'center', title: '头像', width: 150, templet: function (d) {
                            return "<div><img height='50px' src=" + d.avatar + " ></div>"
                        }
                    },
                    {field: 'phone', align: 'center', title: '手机号'},
                    {field: 'sex', align: 'center', width: 80, title: '性别'},
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
                            title: '添加管理员',
                            type: 2,
                            shade: 0.3,
                            maxmin: true,
                            shadeClose: true,
                            id: 'user_add',
                            area: ['60%', '80%'],
                            content: "{{route('user/add')}}",
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
                    layer.confirm('真的删除该管理员么', function (index) {

                        $.ajax({
                            type: "get",
                            url: "{{route('user/del')}}/" + data.id,
                            async: false,

                            dataType: "json",
                            success: function (data) {
                                layer.msg(data.msg)
                                if (data.code == 0) {
                                    obj.del();
                                    window.location.reload();
                                }
                            }
                        });
                    });
                } else if (obj.event === 'edit') {
                    var id = data.id;
                    var index = layer.open({
                        title: '修改管理员信息',
                        type: 2,
                        shade: 0.3,
                        maxmin: true,
                        shadeClose: true,
                        id: 'user_edit',
                        area: ['60%', '80%'],
                        content: "{{route('user/edit')}}/" + id,
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

            //监听搜索
            form.on('submit(data-search-btn)', function (data) {
                var data = data.field

                //渲染
                table.render({
                    elem: '#list',
                    height: 700,
                    title: '管理员数据',
                    url: "{{route('user/getlist')}}",
                    where: {'status': data.status, 'sex': data.sex, 'username': data.username},
                    autoSort: false,
                    totalRow: false,
                    toolbar: '#toolbarDemo',
                    defaultToolbar: ['filter'],
                    cols: [[
                        {type: 'checkbox', align: 'center'},
                        {field: 'id', align: 'center', width: 80, title: 'ID', sort: true},
                        {field: 'username', align: 'center', title: '管理员名称'},
                        {
                            field: 'avatar', align: 'center', title: '头像', width: 150, templet: function (d) {
                                return "<div><img height='50px' src=" + d.avatar + " ></div>"
                            }
                        },
                        {field: 'phone', align: 'center', title: '手机号'},
                        {field: 'sex', align: 'center', width: 80, title: '性别'},
                        {field: 'status', align: 'center', title: '状态', width: 80, totalRow: true},
                        {fixed: 'right', align: 'center', title: '操作', toolbar: '#currentTableBar', width: 200}
                    ]],
                    limits: [10, 20, 30],
                    limit: 10,
                    page: {},

                    parseData: function (res) { //res 即为原始返回的数据
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