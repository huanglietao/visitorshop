@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 分销管理 / 推广订单列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">商户分销商列表</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.agent.inviter._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button id="inviter_export" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
            {{--<button data-area="['70%', '700px']" data-url="{{URL::asset('/agent/info/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">--}}
                {{--<i class="fa fa-plus"></i>--}}
                {{--&nbsp;添加--}}
            {{--</button>--}}
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/agent/inviter/list">
                <thead>
                <tr class="table-head">
					<td>订单号</td>
					<td>交易时间</td>
                    <td>总数量</td>
					<td>订单状态</td>
					<td>实收款</td>
                    <td>来源</td>
                    <td>邀请人</td>
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
    <script src="{{ URL::asset('js/merchant/statistics/index.js')}}"></script>
@endsection