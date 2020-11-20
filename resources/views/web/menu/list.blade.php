@extends('web.common.app')
@section('title')
    菜单列表
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
                            <label class="layui-form-label">菜单名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="title" autocomplete="off" class="layui-input">
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
        <table id="list" lay-filter="list"></table>
    </div>

    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="add" style="display:none">添加</button>
            <button class="layui-btn layui-btn-sm" lay-event="reload">重载</button>
        </div>
    </script>



    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" style="display:none">删除</a>
    </script>

    <script type="text/html" id="usernameTpl">
        <a href="" class="layui-table-link"></a>
    </script>

    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="sex" lay-skin="switch" lay-text="2|1">
    </script>
    <script>
        layui.use(['table', 'form', 'jquery'], function () {
            var table = layui.table,
                form = layui.form,
                $ = layui.jquery;

            //渲染
            table.render({
                elem: '#list'
                , height: 700
                , title: '菜单数据'
                , url: "{{route('menu/getlist')}}"
                ,where:{
                    'status':100
                }
                //,size: 'lg'
                , autoSort: false
                //,loading: false
                , totalRow: false
                , limit: 10
                , limits: [10, 20, 30]
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter']
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {
                        field: 'id',
                        title: 'ID',
                        width: 100,
                        fixed: 'left',
                        unresize: true,
                        sort: true,
                    }
                    , {field: 'title', title: '菜单名称',}
                    , {field: 'href', title: '跳转路由', hide: 0,}
                    , {field: 'target', title: '跳转方式', width: 200,}
                    , {field: 'sort', title: '排序', width: 120, sort: true}
                    , {field: 'status', title: '状态', width: 100}
                    , {field: 'ctime', title: '添加时间', width: 200, sort: true}
                    , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 200}
                ]]
                , parseData: function (res) { //res 即为原始返回的数据
                    return {
                        "code": '0', //解析接口状态
                        "msg": res.msg, //解析提示文本
                        "count": res.count, //解析数据长度
                        "data": res.data //解析数据列表
                    }
                }
                , page: false

            });

            //工具栏事件
            table.on('toolbar(list)', function (obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'add':
                        var index = layer.open({
                            title: '添加菜单',
                            type: 2,
                            shade: 0.3,
                            maxmin: true,
                            shadeClose: true,
                            id: 'menu_add',
                            area: ['60%', '80%'],
                            content: "{{route('menu/add')}}",
                        });
                        $(window).on("resize", function () {
                            layer.full(index);
                        });
                        break;
                    case 'reload':
                        table.reload('list', {
                            page: false
                            //,height: 300
                            //,url: 'x'
                        }, 'data');
                        break;
                }
                ;
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
                    layer.confirm('真的删除该菜单么', function (index) {
                        $.ajax({
                            type: "get",
                            url: "{{route('menu/del')}}/" + data.id,
                            async: false,
                            data: data.field,
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
                    var id = data.id;
                    var index = layer.open({
                        title: '修改菜单信息',
                        type: 2,
                        shade: 0.3,
                        maxmin: true,
                        shadeClose: true,
                        id: 'menu_edit',
                        area: ['60%', '80%'],
                        content: "{{route('menu/edit')}}/" + id,
                    });
                    $(window).on("resize", function () {
                        layer.full(index);
                    });
                }
            });

            //监听排序
            table.on('sort(list)', function (obj) {
                console.log(this)

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
                    , title: '菜单数据'
                    , url: "{{route('menu/getlist')}}"
                    , where: {
                        'title': $.trim(data.title),
                        'status': data.status,
                    }
                    //,size: 'lg'
                    , autoSort: false
                    //,loading: false
                    , totalRow: false
                    , limit: 10
                    , limits: [10, 20, 30]
                    , toolbar: '#toolbarDemo'
                    , defaultToolbar: ['filter']
                    , cols: [[
                        {type: 'checkbox', fixed: 'left'}
                        , {
                            field: 'id',
                            title: 'ID',
                            width: 100,
                            fixed: 'left',
                            unresize: true,
                            sort: true,
                        }
                        , {field: 'title', title: '菜单名称',}
                        , {field: 'href', title: '跳转路由', hide: 0,}
                        , {field: 'target', title: '跳转方式', width: 200,}
                        , {field: 'sort', title: '排序', width: 120, sort: true}
                        , {field: 'status', title: '状态', width: 100}
                        , {field: 'ctime', title: '添加时间', width: 200, sort: true}
                        , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 200}
                    ]]
                    , parseData: function (res) { //res 即为原始返回的数据
                        return {
                            "code": '0', //解析接口状态
                            "msg": res.msg, //解析提示文本
                            "count": res.count, //解析数据长度
                            "data": res.data //解析数据列表
                        }
                    }
                    , page: false

                });


                return false;
            });


        });
    </script>



@endsection
