@extends('layout.iframe')
<link rel="stylesheet" href="{{ URL::asset('css/agent/auth/admin.css') }}">
@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 权限管理 / 角色管理' ])
    @endcomponent
    {{--@component('component/crumb')--}}
    {{--@endcomponent--}}
    <!-- 面包屑 end  -->
<div id="main">
    <!-- 操作提示 start -->
    @component('component/tips')
        {{--<p style="margin:5px 0;padding:0">该页面展示了DMS系统中所有的系统使用角色列表信息。</p>--}}
        {{--<p style="margin:5px 0;padding:0">角色是权限分类。系统使用者（管理员）在系统使用中预先定义好的身份（一组权限组合），可以定义多组角色，比如：技术、运营、客服等。</p>--}}
        {{--<p style="margin:5px 0;padding:0">如预先设定了权限角色组，在添加管理员时可直接给管理员赋予对应的角色组权限。</p>--}}
    @endcomponent
    <!-- 操作提示 end -->

    <!-- 搜索功能 start  -->
    <div class="" style="margin-top:20px">
        @include('agent.auth.rule._search')
    </div>
    <!--  搜索功能 end -->

    <div id="act-btn" style="padding:20px 0">
        <button class="btn btn-white btn-refresh">
            <i class="fa fa-refresh"></i> &nbsp;刷新
        </button>
        <button data-area="['70%', '70%']" data-url="{{URL::asset('/auth/rule/form')}}" data-title = "添加"
                class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add"  style="padding:3px 8px;font-size: 12px">
            <i class="fa fa-plus"></i>
            &nbsp;添加角色
        </button>
    </div>


    <div id="table">
        <!-- table start -->
        <table class="no-border-table" data-url="/auth/rule/list">
            <thead>
            <tr class="table-head">
                <td>角色名</td>
                <td>状态</td>
                <td>创建时间</td>
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
@endsection