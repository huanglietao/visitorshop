@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 营销管理 / 优惠券列表 / 优惠码' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">优惠码列表页面，优惠券类型为优惠码时生成。</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        {{--<div class="" style="margin-top:20px">--}}
            {{--@include('merchant.marketing.couponNumber._search')--}}
        {{--</div>--}}
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            {{--<button data-area="['70%', '700px']" data-url="{{URL::asset('/marketing/couponNumber/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">--}}
                {{--<i class="fa fa-plus"></i>--}}
                {{--&nbsp;添加--}}
            {{--</button>--}}
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/marketing/couponNumber/list?cou_id={{$cou_id}}">
                <thead>
                <tr class="table-head">
					<td>优惠券名称</td>
					<td>优惠码</td>
					<td>面值</td>
					<td>是否使用</td>
					<td>使用会员</td>
					<td>使用订单号</td>
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
