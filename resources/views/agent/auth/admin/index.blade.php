@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 权限管理 / 管理员列表' ])
    @endcomponent
    {{--@component('component/crumb')--}}
    {{--@endcomponent--}}
    <!-- 面包屑 end  -->

<div id="main" >
    <!-- 操作提示 start -->
    @component('component/tips')
        {{--<p style="margin:5px 0 ;padding:0">该页面展示了所有能使用DMS系统的人员账号清单。</p>--}}
        {{--<p style="margin:5px 0;padding:0">管理员可手动添加并分配对应系统功能权限，查看该管理操作系统日志信息。</p>--}}
        {{--<p style="margin:5px 0;padding:0">管理员账号支持主账号与子账号关联关系，主账号能看到所有子账号信息，各子账号只能看到自己信息，可配合权限使用。</p>--}}
    @endcomponent
    <!-- 操作提示 end -->

    <!-- 搜索功能 start  -->
    <div style="margin-top:20px">
        <link rel="stylesheet" href="{{ URL::asset('css/agent/auth/admin.css') }}">
        @include('agent.auth.admin._search')
    </div>
    <!--  搜索功能 end -->

    <!-- 操作按钮 start  -->
    <div id="act-btn" style="padding:20px 0">
        <button class="btn btn-white btn-refresh">
            <i class="fa fa-refresh"></i> &nbsp;刷新
        </button>
        <button data-area="['70%', '70%']" data-url="{{URL::asset('/auth/admin/form')}}" data-title = "添加"
                class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add"  style="padding:3px 8px;font-size: 12px">
            <i class="fa fa-plus"></i>
            &nbsp;添加管理员
        </button>
    </div>
    <!-- 操作按钮 end  -->


    <div id="table">
        <!-- table start -->
        <table class="no-border-table" data-url="/auth/admin/list">
            <thead>
            <tr class="table-head">
                <td>账号</td>
                <td>姓名</td>
                <td>角色组</td>
                <td>Email</td>
                <td>状态</td>
                <td>创建时间</td>
                <td>最后登录时间</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody class="tbl-content">

            </tbody>
        </table>
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
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection