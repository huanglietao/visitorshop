@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 分销管理 / 商家账号' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">分销商入驻商家信息管理列表，可对商家信息、权限、等级设置。</p>--}}
            {{--<p style="margin:5px 0 ;padding:0">标识“*”的选项为必填项，其它为选填项，信息内容填写时请注意内容格式及长度</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.agent.account._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            {{--<button data-area="['70%', '700px']" data-url="{{URL::asset('/agent/account/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">--}}
                {{--<i class="fa fa-plus"></i>--}}
                {{--&nbsp;添加--}}
            {{--</button>--}}
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/agent/account/list">
                <thead>
                <tr class="table-head">
                    <td style="width: 12%">头像</td>
					<td style="width: 9%">账号</td>
					<td style="width: 9%">所属商家</td>
					<td style="width: 12%">昵称</td>
					<td style="width: 12%">所属角色组</td>
					<td style="width: 12%">状态</td>
					<td style="width: 12%">上次登录时间</td>
					<td style="width: 12%">注册时间</td>
                    <td style="width: 10%">操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>
            @component('component/paginate',['limit' => \Config::get('pageLimit')])
            @endcomponent

        </div>
        <!-- 列表end    -->
    </div>


@endsection
<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection