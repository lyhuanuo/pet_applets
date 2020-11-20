@extends('web.common.app')
@section('title')
    会员列表
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
                            <label class="layui-form-label">openid</label>
                            <div class="layui-input-inline">
                                <input type="text" name="openid" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">用户昵称</label>
                            <div class="layui-input-inline">
                                <input type="text" name="nickname" autocomplete="off" class="layui-input">
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
                            <label class="layui-form-label">用户类型</label>
                            <div class="layui-input-inline">
                                <select name="member_type" id="">
                                    <option value="100"> 全部</option>
                                    <option value="1"> 支付宝用户</option>
                                    <option value="0"> 微信用户</option>
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
            <button class="layui-btn layui-btn-sm" id="exports_all">全部导出</button>
        </div>
    </script>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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
                , title: '会员数据'
                , url: "{{route('member/getlist')}}"
                , where: {'status': 100,'sex':100,'member_type':100}
                , page: {}
                , autoSort: false
                , totalRow: false
                , limit: 20
                , limits: [10, 20, 30]
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter', 'exports']
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
                    , {field: 'openid', align: 'center', title: 'openid'}
                    , {field: 'nickname',  align: 'center',title: '昵称', width: 200,}
                    , {
                        field: 'avatar',  align: 'center',title: '头像', width: 150, templet: function (d) {
                            return "<div><img  height='50px' src=" + d.avatar + "></div>"
                        }
                    }
                    , {field: 'sex',  align: 'center',title: '性别', width: 80,}
                    , {field: 'phone', align: 'center', title: '手机', width: 120}
                    , {field: 'wx',  align: 'center',title: '微信'}
                    , {field: 'status', align: 'center', title: '状态', width: 120}
                    , {field: 'member_type', align: 'center', title: '用户类型', width: 120}
                    , {field: 'ctime',  align: 'center',title: '添加时间', width: 200, sort: true, totalRow: true}
                    , {fixed: 'right', align: 'center', title: '操作', toolbar: '#barDemo', width: 200}
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
                            title: '添加会员',
                            type: 2,
                            shade: 0.3,
                            maxmin: true,
                            shadeClose: true,
                            id: 'member_add',
                            area: ['60%', '80%'],
                            content: "{{route('member/add')}}",
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
                    case 'LAYTABLE_TIPS':
                        layer.alert('Table for layui-v' + layui.v);
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
            table.on('rowDouble(list)', function (obj) {
                console.log(obj);
            });


            //监听行工具事件
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                //console.log(obj)
                if (obj.event === 'del') {
                    layer.confirm('真的删除该会员么', function (index) {
                        $.ajax({
                            type: "get",
                            url: "{{route('member/del')}}/" + data.id,
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
                    var id = data.id;
                    var index = layer.open({
                        title: '修改会员信息',
                        type: 2,
                        shade: 0.3,
                        maxmin: true,
                        shadeClose: true,
                        id: 'member_edit',
                        area: ['60%', '80%'],
                        content: "{{route('member/edit')}}/" + id,
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
                    elem: '#list'
                    , height: 700
                    , title: '会员数据'
                    , url: "{{route('member/getlist')}}"
                    , where: {'status': data.status,'sex':data.sex,'nickname':data.nickname,'openid':data.openid,'member_type':data.member_type}
                    , page: {}
                    , autoSort: false
                    , totalRow: false
                    , limit: 20
                    , limits: [10, 20, 30]
                    , toolbar: '#toolbarDemo'
                    , defaultToolbar: ['filter', 'exports']
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
                        , {field: 'openid',  align: 'center',title: 'openid'}
                        , {field: 'nickname',  align: 'center',title: '昵称', width: 200,}
                        , {
                            field: 'avatar',  align: 'center',title: '头像', width: 150, templet: function (d) {
                                return "<div><img height='50px' src=" + d.avatar + " ></div>"
                            }
                        }
                        , {field: 'sex', title: '性别',  align: 'center',width: 80,}
                        , {field: 'phone', title: '手机', align: 'center', width: 120}
                        , {field: 'wx', title: '微信' ,align: 'center',}
                        , {field: 'status', title: '状态', align: 'center', width: 120}
                        , {field: 'member_type', align: 'center', title: '用户类型', width: 120}
                        , {field: 'ctime', title: '添加时间',  align: 'center',width: 200, sort: true, totalRow: true}
                        , {fixed: 'right', title: '操作',  align: 'center',toolbar: '#barDemo', width: 200}
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

                var openid = $.trim($("input[name='openid']").val());
                var nickname = $.trim($("input[name='nickname']").val());
                var status = $("select[name='status']").val();
                var sex = $("select[name='sex']").val();

                var url = "{{route('member/export')}}" + "?sex=" + sex + '&openid=' + openid + "&nickname=" + nickname + "&status=" + status;
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
