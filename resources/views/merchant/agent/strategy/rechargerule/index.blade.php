@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 运营策略' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  tab栏 start -->
        <div class="statistics_tabs" style="padding-bottom: 20px">
            @component('component.navOperateTab',['navlist'=>['1'=>'充值规则']])

            @endcomponent
        </div>
        <!--  tab栏 end -->
        <!--  提示组件 start -->
        @component('component/tips')
           {{--<p>用于分销商线下支付相关充值配置</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.agent.strategy.rechargerule._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '70%']" data-url="{{URL::asset('/agent/strategy/rechargerule/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/agent/strategy/rechargerule/list">
                <thead>
                <tr class="table-head">
					<td>优惠名称</td>
					<td>充值金额</td>
					<td>奖励金额</td>
					<td>创建时间</td>
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
