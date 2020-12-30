@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 供求管理 / 订单推送列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">订单推送管理主要是查看订单的推送状态，对推送失败的订单可以进行重新推送</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.suppliers.orderpush._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            {{--<button data-area="['70%', '700px']" data-url="{{URL::asset('/suppliers/orderpush/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">--}}
                {{--<i class="fa fa-plus"></i>--}}
                {{--&nbsp;添加--}}
            {{--</button>--}}
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/suppliers/orderpush/list">
                <thead>
                <tr class="table-head">
                    <td>订单号</td>
                    <td>订单推送状态</td>
                    <td>错误信息</td>
                    <td>开始时间</td>
                    <td>结束时间</td>
                    <td>操作</td>
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
