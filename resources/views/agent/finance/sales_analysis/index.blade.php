<!DOCTYPE html>
<link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">

@extends('layout.iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台/财务统计/销售分析' ])
    @endcomponent
    <!-- 面包屑组件end  -->
    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            <p class="accounts-two">销售分析包括：订单统计、商品统计、地区统计、物流统计</p>
            <p class="accounts-two">销售总额是所选时间范围内所有订单状态下商品售价总额+运费总额；异常订单指的是所选统计条件下，已取消+已退货+无效的订单。</p>
            <p class="accounts-two">平均客单价：指的是销售总额÷成交总笔数（成交顾客的转化率）</p>
        @endcomponent

    <!--  提示组件 end -->
        <!--  tab栏 start -->
        <div class="statistics_tabs" style="margin-top: 20px;">
            @component('component.navOperateTab',['navlist'=>['1'=>'销售分析','2'=>'订单统计','3'=>'商品统计','4'=>'地区统计','5'=>'物流统计'],'extendClass'=>"s_analy_tab"])

            @endcomponent
        </div>
    <!--  tab栏 start -->
        <div class="statistics_loading"></div>
        <div id="statistics-view">

        </div>

    </div>
@endsection

<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/finance/analysis.js') }}"></script>
    <script src="{{ URL::asset('assets/chart.js/Chart.bundle.min.js')}}"></script>
@endsection

@section("pages-js")

@endsection






