<?php
    $label = [
        'username' => '编号',
        'nickname' => '姓名',
        'mid'      => '所属账号',
        'role'      => '角色组',
        'email'    => '邮件',
        'status'   => '状态',
        'create_time'   => '创建时间'

    ];
?>
<link rel="stylesheet" href="{{URL::asset('assets/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 权限管理 / 操作日志' ])
    @endcomponent
    {{--@component('component/crumb')--}}

    {{--@endcomponent--}}
    <!-- 面包屑 end  -->
<div id="main" >
    <!-- 操作提示 start -->
    @component('component/tips')
        <p style="margin:5px 0;padding:0">该页面展示了所有能使用DMS系统的管理人员使用系统过程中的操作日志记录。</p>
        <p style="margin:5px 0;padding:0">根据操作日志可追溯管理人员操作系统过程，或审查关键节点操作内容记录。</p>
    @endcomponent
    <!-- 操作提示 end -->
    <!-- 搜索功能 start  -->
    <div style="margin-top:20px">
        <link rel="stylesheet" href="{{ URL::asset('css/agent/auth/admin.css') }}">
        @include('agent.auth.daily._search')
    </div>
    <!--  搜索功能 end -->
    <div id="act-btn" style="padding:20px 0">
        <button class="btn btn-white btn-refresh">
            <i class="fa fa-refresh"></i> &nbsp;刷新
        </button>
    </div>

    <div id="table">
        <!-- table start -->
        <table class="no-border-table" data-url="/daily/list">
            <thead>
            <tr class="table-head">
                <td>
                    @component('component/checkbox',['checkbox'=>[''],'name'=>['all'],'custom_class'=>'checkall'])
                    @endcomponent
                    {{--<input type="checkbox" id="all" class="checkall btn-checkbox" >--}}
                    {{--<label for="all"></label>--}}
                </td>
                <td>编号</td>
                <td>操作员</td>
                <td>URL</td>
                <td>操作IP</td>
                <td>创建时间</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody class="tbl-content">

            </tbody>
        </table>


        <div id="down-act-btn" class="down-act-btn" style="margin:5px 0 0 24px;float: left;">
            <button class="btn btn-write ">清除日志</button>
        </div>

        @component('component/paginate',['limit' => $pageLimit])
        @endcomponent
        <!-- table end -->
    </div>
</div>
@endsection

<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script src="{{ URL::asset('js/agent/auth/admin.js')}}"></script>
@endsection