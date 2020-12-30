<!doctype html>
@extends('layout.mch_iframe')

<link rel="stylesheet" href="{{URL::asset('css/agent/works/works.css')}}">
@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '作品管理/作品列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.works.workslist._search')
        </div>
        <!--  搜索功能 end -->

        <!-- tab状态按钮 start  -->
        <div class="nav_tab">
            @component('component.navOperateTab',['url'=>'/works/workslist/statusCount','navlist'=>$statusCount,'extendClass'=>'works_tab','defaut_key'=>$defaultKey])
            @endcomponent
        </div>
        <!-- tab状态按钮 end  -->
        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/works/workslist/list">
                <thead>
                <tr class="table-head">
                    <td>作品信息</td>
                    <td>作品属性</td>
                    <td>状态</td>
                    <td>来源</td>
                    <td>作者信息</td>
                    {{--<td>作品标签</td>--}}
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
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/merchant/works/works.js')}}"></script>
    <script src="{{ URL::asset('assets/jeromeetienne-jquery/jquery.qrcode.min.js')}}"></script>
@endsection