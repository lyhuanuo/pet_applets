@extends('web.common.app')
@section('title')
    会员扫码日志列表
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
                        <div class="layui-inline" style="display:none">
                            <label class="layui-form-label">会员openid</label>
                            <div class="layui-input-inline">
                                <input type="text" name="openid" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline" style="display:none">
                            <label class="layui-form-label">会员昵称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="nickname" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">二维码编号</label>
                            <div class="layui-input-inline">
                                <input type="text" name="code_number" autocomplete="off" class="layui-input">
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
            <button class="layui-btn layui-btn-sm" lay-event="reload">重载</button>
        </div>
    </script>



    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>

    <script type="text/html" id="usernameTpl">
        <a href="" class="layui-table-link"></a>
    </script>

    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="sex" lay-skin="switch" lay-text="2|1">
    </script>
    <script>
        layui.use(['table', 'jquery', 'form'], function () {
            var table = layui.table,
                form = layui.form,
                $ = layui.jquery;

            //渲染
            table.render({
                elem: '#list'
                , height: 700
                , title: '扫码日志数据'
                , url: "{{route('memberlog/getlist')}}"
                , page: {}
                , autoSort: false
                , totalRow: false
                , limit: 20
                , limits: [20, 30, 50]
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter']
                , cols: [[
                    {type: 'checkbox', fixed: 'center'}
                    , {
                        field: 'id',
                        title: 'ID',
                        width: 80,
                        fixed: 'center',
                        align: 'center',
                        unresize: true,
                        sort: true,
                    }
                    // , {field: 'openid', title: 'openid', align: 'center',}
                    // , {
                    // 	field: 'nickname', title: '会员昵称', align: 'center',templet:function (d) {
                    // 		return "<div><a href='' class='layui-table-link'>"+d.nickname+"</a></div>";
                    //     }
                    // }
                    // , {
                    //     field: 'nickname', title: '会员昵称', align: 'center',
                    // }
                    , {field: 'code_number', title: '所扫的二维码编号', align: 'center'}
                    , {field: 'latitude', title: '经度', align: 'center', width: 150,}
                    , {field: 'longitude', title: '纬度', align: 'center', width: 150,}

                    , {field: 'address', title: '地址', align: 'center',}
                    , {field: 'ctime', title: '添加时间', align: 'center', width: 200, sort: true,}

                    , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 150}
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


            //监听行工具事件
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                //console.log(obj)
                if (obj.event === 'del') {
                    layer.confirm('真的删除该记录么', function (index) {
                        $.ajax({
                            type: "get",
                            url: "{{route('memberlog/del')}}/" + data.id,
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
                }
            });

            //监听搜索
            form.on('submit(data-search-btn)', function (data) {
                var data = data.field

                //渲染
                table.render({
                    elem: '#list'
                    , height: 700
                    , title: '扫码日志数据'
                    , url: "{{route('memberlog/getlist')}}"
                    , where: {'nickname': data.nickname, 'code_number': data.code_number,'openid':data.openid}
                    , page: {}
                    , autoSort: false
                    , totalRow: false
                    , limit: 20
                    , limits: [20, 30, 50]
                    , toolbar: '#toolbarDemo'
                    , defaultToolbar: ['filter']
                    , cols: [[
                        {type: 'checkbox', fixed: 'center'}
                        , {
                            field: 'id',
                            title: 'ID',
                            width: 80,
                            fixed: 'center',
                            align: 'center',
                            unresize: true,
                            sort: true,
                        }
                        // , {field: 'openid', title: 'openid', align: 'center',}
                        // , {
                        //     field: 'nickname', title: '会员昵称', align: 'center',
                        // }
                        , {field: 'code_number', title: '所扫的二维码编号', align: 'center'}
                        , {field: 'latitude', title: '经度', align: 'center', width: 150,}
                        , {field: 'longitude', title: '纬度', align: 'center', width: 150,}

                        , {field: 'address', title: '地址', align: 'center',}
                        , {field: 'ctime', title: '添加时间', align: 'center', width: 200, sort: true,}

                        , {fixed: 'right', title: '操作', toolbar: '#barDemo', width: 150}
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


            var $ = layui.jquery, active = {
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
