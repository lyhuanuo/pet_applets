@extends('web.common.app')
@section('title')
    管理员日志列表
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
                            <label class="layui-form-label">管理员名称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="username" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-primary" lay-submit
                                    lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索
                            </button>
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

    <script>
        layui.use(['table','form','jquery'], function () {
            var table = layui.table,
                form = layui.form,
                $ = layui.jquery;

            //渲染
            table.render({
                elem: '#list'
                , height: 700
                , title: '管理员日志数据'
                , url: "{{route('log/getlist')}}"

                , page: {}

                , autoSort: false
                , totalRow: false
                , limit: 20
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter']
                , cols: [[
                    {type: 'checkbox', fixed: 'center'}
                    , {
                        field: 'id',
                        title: 'ID',
                        width: 80,
                        fixed: 'center',
                        unresize: true,
                        sort: true,
                    }
                    , {field: 'username', title: '管理员名称',}
                    , {field: 'login_ip', title: 'IP', width: 200}
                    , {field: 'log_url', title: 'URL', }
                    , {field: 'log_info', title: '日志信息',  }
                    , {field: 'ctime', title: '操作时间', width: 200, sort: true}
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

                if (obj.event === 'del') {
                    layer.confirm('真的删除该记录么', function (index) {
                        $.ajax({
                            type: "get",
                            url: "{{route('log/del')}}/" + data.id,
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
                }
            });

            //监听排序
            table.on('sort(list)', function (obj) {
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
                    , title: '管理员日志数据'
                    , url: "{{route('log/getlist')}}"
                    , where:{}
                    , page: {}

                    , autoSort: false
                    , totalRow: false
                    , limit: 20
                    , toolbar: '#toolbarDemo'
                    , defaultToolbar: ['filter']
                    , cols: [[
                        {type: 'checkbox', fixed: 'center'}
                        , {
                            field: 'id',
                            title: 'ID',
                            width: 80,
                            fixed: 'center',
                            unresize: true,
                            sort: true,
                        }
                        , {field: 'username', title: '管理员名称',}
                        , {field: 'login_ip', title: 'IP', width: 200}
                        , {field: 'log_url', title: 'URL', }
                        , {field: 'log_info', title: '日志信息',  }
                        , {field: 'ctime', title: '操作时间', width: 200, sort: true}
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


            var $ = layui.jquery, active = {
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
