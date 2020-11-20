@extends('web.common.app')
@section('title')
    宠物列表
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
                            <label class="layui-form-label">宠物名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="name" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">会员昵称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="nickname" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">状态</label>
                            <div class="layui-input-inline">
                                <select name="status" id="">
                                    <option value="100" selected> 全部</option>
                                    <option value="0"> 丢失中</option>
                                    <option value="1"> 已找回</option>
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
            <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
            <button class="layui-btn layui-btn-sm" lay-event="reload">重载</button>
        </div>
    </script>

    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>


    <script>
        layui.use(['table', 'jquery', 'form'], function () {
            var table = layui.table,
                $ = layui.jquery,
                form = layui.form;

            //渲染
            table.render({
                elem: '#list'
                , height: 700
                //,width: 600
                , title: '丢失宠物数据'
                , url: "{{route('petlost/getlist')}}"
                , where: {'status': 100}
                //,size: 'lg'
                , page: {}
                , autoSort: false
                //,loading: false
                , totalRow: false
                , limit: 30
                , limits: [20, 30, 50]
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter','exports']
                , cols: [[
                    {type: 'checkbox', align: 'center'}
                    , {
                        field: 'id',
                        title: 'ID',
                        width: 60,
                        align: 'center',
                        unresize: true,
                        sort: true,
                    }
                    , {field: 'name', title: '宠物名称', align: 'center', width: 120}
                    , {
                        field: 'img', title: '图片', align: 'center', templet: function (d) {
                            return "<div><img height ='50px' src=" + d.img + " ></div>"
                        }
                    }
                    , {field: 'sex', title: '性别', align: 'center', width: 60}
                    , {field: 'address', title: '丢失地址', align: 'center', }
                    , {field: 'lost_time', title: '丢失时间', align: 'center'}
                    , {field: 'phone', title: '联系手机号', align: 'center', width: 120}
                    , {field: 'wx', title: '联系微信', align: 'center', width: 120}
                    , {field: 'amount', title: '悬赏金额', align: 'center', width: 100}
                    , {field: 'nickname', title: '所属会员', align: 'center'}
                    , {field: 'status', title: '状态', align: 'center',width:100}
                    , {field: 'ctime', title: '添加时间', align: 'center', sort: true, width: 160}
                    , {fixed: 'right', title: '操作', align: 'center', toolbar: '#barDemo', width: 110}
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
                            title: '添加宠物丢失信息',
                            type: 2,
                            shade: 0.3,
                            maxmin: true,
                            shadeClose: true,
                            id: 'pet_lost_add',
                            area: ['60%', '80%'],
                            content: "{{route('petlost/add')}}",
                        });
                        $(window).on("resize", function () {
                            layer.full(index);
                        });
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
                    case 'reload':
                        table.reload('list', {
                            page: {curr: 1}
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


            //监听表格行点击
            table.on('tr', function (obj) {
                console.log(obj)
            });

            //监听表格复选框选择
            table.on('checkbox(list)', function (obj) {
                console.log(obj)
            });

            //监听表格单选框选择
            table.on('radio(test)', function (obj) {
                console.log(obj)
            });

            //监听表格单选框选择
            table.on('rowDouble(list)', function (obj) {
                console.log(obj);
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
                if (obj.event === 'del') {  //删除
                    layer.confirm('真的删除该宠物丢失信息么', function (index) {
                        $.ajax({
                            type: "get",
                            url: "{{route('petlost/del')}}/" + data.id,
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
                } else if (obj.event === 'edit') {      //修改
                    var index = layer.open({
                        title: '修改宠物丢失信息',
                        type: 2,
                        shade: 0.3,
                        maxmin: true,
                        shadeClose: true,
                        id: 'pet_lost_edit',
                        area: ['60%', '80%'],
                        content: "{{route('petlost/edit')}}/" + data.id,
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
                    ,
                    height: 700
                    ,
                    title: '宠物丢失数据'
                    ,
                    url: "{{route('petlost/getlist')}}"
                    ,
                    where: {
                        'status': data.status,
                        'name': data.name,
                        'nickname': data.nickname
                    }
                    //,size: 'lg'
                    ,
                    page: {}
                    ,
                    autoSort: false
                    //,loading: false
                    ,
                    totalRow: false
                    ,
                    limit: 20
                    ,
                    limits: [20, 30, 50]
                    ,
                    toolbar: '#toolbarDemo'
                    ,
                    defaultToolbar: ['filter', 'exports', 'print']
                    ,
                    cols: [[
                        {type: 'checkbox', align: 'center'}
                        , {
                            field: 'id',
                            title: 'ID',
                            width: 60,
                            align: 'center',
                            unresize: true,
                            sort: true,
                        }
                        , {field: 'name', title: '宠物名称', align: 'center', width: 120}
                        , {
                            field: 'img', title: '图片', align: 'center', templet: function (d) {
                                return "<div><img height ='50px' src=" + d.img + " ></div>"
                            }
                        }
                        , {field: 'sex', title: '性别', align: 'center', width: 60}
                        , {field: 'address', title: '丢失地址', align: 'center', }
                        , {field: 'lost_time', title: '丢失时间', align: 'center'}
                        , {field: 'phone', title: '联系手机号', align: 'center', width: 120}
                        , {field: 'wx', title: '联系微信', align: 'center', width: 120}
                        , {field: 'amount', title: '悬赏金额', align: 'center', width: 100}
                        , {field: 'nickname', title: '所属会员', align: 'center'}
                        , {field: 'status', title: '状态', align: 'center',width:100}
                        , {field: 'ctime', title: '添加时间', align: 'center', sort: true, width: 160}
                        , {fixed: 'right', title: '操作', align: 'center', toolbar: '#barDemo', width: 110}
                    ]]
                    ,
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
        });
    </script>



@endsection
