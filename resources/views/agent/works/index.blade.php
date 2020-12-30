<!doctype html>
@extends('layout.iframe')

@section("main-content")
    <link rel="stylesheet" href="{{URL::asset('css/agent/works/works.css')}}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 作品管理  /  作品列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">

        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p>该页面展示了所有作品数据列表。</p>--}}
            {{--<p>根据不同的状态可以查看不同的数据以及显示不同的操作。</p>--}}
            {{--<p>在不同的作品状态下可以有不同的操作方式。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('agent.works._search')
        </div>
        <!--  搜索功能 end -->

        <!-- tab状态按钮 start  -->
        <div class="nav_tab">
            @component('component.navOperateTab',['url'=>'/works/statusCount','navlist'=>$statusCount,'extendClass'=>'works_tab','defaut_key'=>$defaultKey])
            @endcomponent
        </div>
        <!-- tab状态按钮 end  -->
        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:0 0 10px 15px;@if($defaultKey=='all') display: none @endif">
            <button hidden class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <div class="works_checkBox">
                @component('component/checkbox',['checkbox'=>['全选/反选'],'name'=>['checkall'],'custom_class'=>"checkall checkall-1 checkbox",'left_distance'=>25,'right_distance'=>15])
                @endcomponent
            </div>
            <button @if($sync_sdk==0)class="btn btn-write btn-check" @else class="btn btn-write  btn-tongbu-check" @endif @if($defaultKey!=WORKS_DIY_STATUS_WAIT_CONFIRM)style="display: none"@endif><i class="fa fa-check"></i> 批量订购</button>
            <button onclick="batch('review')" class="btn btn-write btn-search-plus" @if($defaultKey!=WORKS_DIY_STATUS_MAKING)style="display: none"@endif><i class="fa fa-search-plus"></i> 批量审核</button>
            <button onclick="batch('paperclip')" class="btn btn-write btn-paperclip" @if($defaultKey!=WORKS_DIY_STATUS_WAIT_CONFIRM)style="display: none"@endif><i class="fa fa-paperclip"></i> 批量标签</button>
            <button onclick="batch('clone')" class="btn btn-write btn-clone" @if($defaultKey!=WORKS_DIY_STATUS_WAIT_CONFIRM  && $defaultKey!=WORKS_DIY_STATUS_ORDER)style="display: none"@endif><i class="fa fa-clone" style="background-color: #d4d1d1"></i> 批量克隆</button>
            <button onclick="batch('trash')" class="btn btn-write btn-trash" @if($defaultKey=="all"  || $defaultKey==WORKS_DIY_STATUS_DELETE || $defaultKey==WORKS_DIY_STATUS_ORDER)style="display: none"@endif><i class="fa fa-trash"></i> 批量删除</button>
            <button onclick="batch('undo')" class="btn btn-write btn-undo" @if($defaultKey!=WORKS_DIY_STATUS_DELETE)style="display: none"@endif><i class="fa fa-undo"></i> 批量恢复</button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <input id="sync_sdk" hidden type="text" value="{{$sync_sdk}}" />
            <table class="no-border-table" data-url="/works/list">
                <thead>
                <tr class="table-head">
                    <td>作品信息</td>
                    <td>作品属性</td>
                    <td>订购数量</td>
                    <td>状态</td>
                    <td>作者信息</td>
                    <td>作品标签</td>
                    <td>最后操作时间</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>

            <!-- 操作按钮 start  -->
            <div id="down-act-btn" class="down-act-btn" style="@if($defaultKey=='all') display: none @endif">
                <div class="works_checkBox" >
                    @component('component/checkbox',['checkbox'=>['全选/反选'],'name'=>['checkall'],'custom_class'=>"checkall checkall-2 checkbox",'left_distance'=>25,'right_distance'=>15])
                    @endcomponent
                </div>
                <button @if($sync_sdk==0)class="btn btn-write btn-check" @else class="btn btn-write  btn-tongbu-check" @endif @if($defaultKey!=WORKS_DIY_STATUS_WAIT_CONFIRM)style="display: none"@endif><i class="fa fa-check"></i> 批量订购</button>
                <button onclick="batch('review')" class="btn btn-write btn-search-plus" @if($defaultKey!=WORKS_DIY_STATUS_MAKING)style="display: none"@endif><i class="fa fa-search-plus"></i> 批量审核</button>
                <button onclick="batch('paperclip')" class="btn btn-write btn-paperclip" @if($defaultKey!=WORKS_DIY_STATUS_WAIT_CONFIRM)style="display: none"@endif><i class="fa fa-paperclip"></i> 批量标签</button>
                <button onclick="batch('clone')" class="btn btn-write btn-clone" @if($defaultKey!=WORKS_DIY_STATUS_WAIT_CONFIRM  && $defaultKey!=WORKS_DIY_STATUS_ORDER)style="display: none"@endif><i class="fa fa-clone" style="background-color: #d4d1d1"></i> 批量克隆</button>
                <button onclick="batch('trash')" class="btn btn-write btn-trash" @if($defaultKey=="all"  || $defaultKey==WORKS_DIY_STATUS_DELETE || $defaultKey==WORKS_DIY_STATUS_ORDER)style="display: none"@endif><i class="fa fa-trash"></i> 批量删除</button>
                <button onclick="batch('undo')" class="btn btn-write btn-undo" @if($defaultKey!=WORKS_DIY_STATUS_DELETE)style="display: none"@endif><i class="fa fa-undo"></i> 批量恢复</button>
            </div>
            <!-- 操作按钮 end  -->

            @component('component/paginate',['limit' =>  \Config::get('pageLimit')])

            @endcomponent

        </div>
        <!--  列表 end -->

    </div>



@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/agent/works/works.js')}}?v=1.01"></script>
    <script src="{{ URL::asset('assets/jeromeetienne-jquery/jquery.qrcode.min.js')}}"></script>
@endsection
@section("pages-js")

@endsection






