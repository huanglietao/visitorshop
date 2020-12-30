@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 营销管理 / 优惠券列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">优惠券管理页面，优惠券生成以及发放规则设置。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.marketing.coupon._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '70%']" data-url="{{URL::asset('/marketing/coupon/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/marketing/coupon/list">
                <thead>
                <tr class="table-head">
					<td style="width:15%;">名称</td>
					<td style="width:10%;">类型</td>
					<td style="width:10%;">面额</td>
					<td style="width:10%;">需满金额</td>
					<td style="width:10%;">所属子系统</td>
					<td style="width:10%;">使用次数</td>
					<td style="width:10%;">生效时间</td>
					<td style="width:10%;">失效时间</td>
                    <td style="width:10%;">操作</td>
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
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/merchant/marketing.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
@endsection
@section("pages-js")

@endsection