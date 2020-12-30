@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard'])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('backend.maintenance.exceptionlog._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['65%', '70%']" data-url="{{URL::asset('/auth/cmsadmin/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/maintenance/exceptionlog/list" style="width: 100%">
                <thead>
                <tr class="table-head">
                    <td style="width: 3%">ID</td>
                    <td style="width: 8%">日志文件</td>
                    <td style="width: 5%">是否处理</td>
                    <td >标题</td>
                    <td style="width: 25%">涉及文件</td>
                    <td style="width: 4%">所在行数</td>
                    <td style="width:5%">创建时间</td>
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
<!---  引入当前页需使用的js  -->
@section("js-file")
    <script src="{{ URL::asset('js/backend/exception.js')}}"></script>
@endsection

