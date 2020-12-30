
@extends('layout.iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '系统设置/基本信息' ])
    @endcomponent
    <!-- 面包屑组件end  -->
    <link rel="stylesheet" href="{{URL::asset('css/agent/system/system.css')}}">

<div id="main">
    <div style="margin-bottom:20px">
        @component('component.navOperateTab',['navlist'=>['1'=>'基本信息','2'=>'密码管理']])
            {{--<p style="margin:5px 0 ;padding:0">系统设置是针对商家应用系统时基本的数据配置及管理。</p>--}}
            {{--<p style="margin:5px 0;padding:0">主要包括：店铺基本信息配置、密码配置等</p>--}}
        @endcomponent
    </div>
    @component('component/tips')

    @endcomponent

    <div class="statistics_loading"></div>
    <div id="statistics-view">

    </div>




</div>



@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/agent/system/system.js')}}"></script>
    <script src="{{ URL::asset('assets/chart.js/Chart.bundle.min.js')}}"></script>
@endsection
@section("pages-js")

@endsection