@extends('layout.iframe')
<link rel="stylesheet" href="{{ URL::asset('css/agent/auth/admin.css') }}">
@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 推广管理 / 我的推广' ])
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
        @include('agent.extension.pomoters._search')
    </div>
    <!--  搜索功能 end -->

    <!--  数据统计 start -->
    <div class="order_statistics_data">
        <div class="order_statistics_data_child">
            <p class="osdc_title">我的邀请码</p>
            <p class="osdc_content">
                <span class="osdc_content_num osdc_content_blue">
                    @if(!empty($self_info['code']))
                        {{$self_info['code']}}
                    @endif
                </span>
            </p>
        </div>
        <div class="order_statistics_data_child">
            <p class="osdc_title">订单总数</p>
            <p class="osdc_content">
                <span class="osdc_content_num osdc_content_blue">{{$self_info['order']}}</span>
                <span class="osdc_content_unit osdc_content_blue">单</span>
            </p>
        </div>
        <div class="order_statistics_data_child">
            <p class="osdc_title">订单总金额</p>
            <p class="osdc_content">
                <span class="osdc_content_num osdc_content_blue">{{$self_info['amount']}}</span>
                <span class="osdc_content_unit osdc_content_blue">元</span>
            </p>
        </div>

    </div>
    <!--  数据统计 end -->

    <div id="act-btn" style="padding:20px 0">
        <button class="btn btn-white btn-refresh">
            <i class="fa fa-refresh"></i> &nbsp;刷新
        </button>
    </div>


    <div id="table">
        <!-- table start -->
        <table class="no-border-table" data-url="/extension/pomoters/list">
            <thead>
            <tr class="table-head">
                <td>名称</td>
                <td>邀请码</td>
                <td>订单数</td>
                <td>订单金额</td>
                <td>创建时间</td>
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
<style>
    /*统计数据css start*/
    .order_statistics_data {
        width: 100%;
        border-radius: 5px;
        background: rgb(240, 242, 245);
        margin-top: 20px;
        display: flex;
    }

    .order_statistics_data_child {
        width: 8%;
        margin: 10px 1%;
        padding-left: 1%;
        white-space: nowrap;
    }

    .order_statistics_data_child p {
        line-height: 2;
        margin-bottom: 0!important;
    }

    .osdc_content_num {
        font-weight: bold;
        font-size: 14px;
    }

    .osdc_content_unit {
        font-weight: bold;
        font-size: 12px;
    }

    .osdc_content_blue {
        color: #3F51B5;
    }

    .osdc_content_gray {
        color: #797777;
    }
    /*统计数据css end*/
</style>