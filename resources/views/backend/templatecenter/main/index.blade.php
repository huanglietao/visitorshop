
@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '模板中心/主模板库' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('backend.templatecenter.main._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            {{--<button  data-url="/templatecenter/main/form" data-title = "添加" class=" btn btn-primary btn-sm btn-3F51B5 btn-add btn-temp">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>--}}
            <a target="_blank" href="/#/templatecenter/main/form" class="btn btn-primary btn-sm btn-3F51B5 btn-add" data-title = "添加"><i class="fa fa-plus"></i>&nbsp;添加</a>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/templatecenter/main/list">
                <thead>
                <tr class="table-head">
                    <td>示意图</td>
					<td>模板名称</td>
					<td>模板分类</td>
					<td>所属规格</td>
                    <td>子页数</td>
					<td>前端显示</td>
					<td>审核状态</td>
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
@section("js-file")
    <script src="{{ URL::asset('js/backend/tempcenter/maintemp.js')}}?v=1.01"></script>
@endsection