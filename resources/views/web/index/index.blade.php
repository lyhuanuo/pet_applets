@extends('web.common/app')
@section('title')
    控制台
@endsection
@section('style')
    .layui-top-box {padding:40px 20px 20px 20px;color:#000}
    .layui-top-box > h2{margin:0 auto}
    .panel {margin-bottom:17px;background-color:#fff;border:1px solid transparent;border-radius:3px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}
    .panel-body {padding:15px}
    .panel-title {margin-top:0;margin-bottom:0;font-size:14px;color:inherit}
    .label {display:inline;padding:.2em .6em .3em;font-size:75%;font-weight:700;line-height:1;color:#fff;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25em;margin-top: .3em;}
    .layui-red {color:red}
    .main_btn > p {height:40px;}
@endsection
@section('content')

    <div class="layuimini-main layui-top-box">
        <h2>欢迎来到【 {{$site_name}} 】控制台</h2>
    </div>

@endsection