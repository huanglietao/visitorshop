@extends('layout.erpkf_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '示例参考/示例列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
           <p>管理员即客服人员列表展示、基础信息的查看及编辑等功能</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('erpkf.auth.kfusers._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '700px']" data-url="{{URL::asset('/auth/kfusers/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/auth/kfusers/list">
                <thead>
                <tr class="table-head">
                    <td>头像</td>
                    <td>用户名</td>
					<td>昵称</td>
					<td>所属角色</td>
					<td>邮箱</td>
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

        </div>
        <!-- 列表end    -->
    </div>


@endsection
