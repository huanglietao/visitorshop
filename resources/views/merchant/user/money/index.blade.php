@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 会员管理 / 会员列表 / 资金变动' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">记录当前会员所有的流水资金记录</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.user.money._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/user/money/list?user_id={{$user_id}}">
                <thead>
                <tr class="table-head">
                    <td style="width:10%">账号</td>
                    <td style="width:10%">交易金额</td>
                    <td style="width:10%">账户余额</td>
                    <td style="width:10%">交易类型</td>
					<td style="width:10%">交易流水号</td>
					<td style="width:10%">第三方交易流水号</td>
					<td style="width:15%">操作人</td>
					<td style="width:15%">备注</td>
                    <td style="width:10%">创建时间</td>
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
