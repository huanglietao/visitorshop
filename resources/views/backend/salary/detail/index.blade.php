@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '示例参考/示例列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
           {{--<p>这里请自行加上说明信息</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('backend.salary.detail._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button id="excel_btn" class="btn  btn-3F51B5" style="color: white;"><i class="fa fa-cloud-upload"></i>&nbsp;导入</button>
            <input id="excel_upload" hidden style='width:140px;display: inline-block;padding-left: 0' type='file' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" class="col-sm-5" onchange="upload(this)">
            <button id="export" class="btn btn-write btn-undo" style="margin-right: 0;float: right"><i class="fa fa-download"></i> 导出</button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/salary/detail/list">
                <thead>
                <tr class="table-head">
                    <td style="width: 15%">日期</td>
					<td style="width: 14%">姓名</td>
					<td style="width: 14%">职位</td>
					<td style="width: 10%">职位系数</td>
					<td style="width: 10%">班次</td>
					<td style="width: 14%">总产出数量</td>
					<td style="width: 10%">单价</td>
					<td style="width: 15%">工资</td>
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
    <script src="{{ URL::asset('js/backend/salary/salary.js')}}"></script>
@endsection

@section("pages-js")

@endsection
<style>
    .finance_order_checkbox_first_td {
        color: #797777;
        font-size: 12px;
        text-align: left;
        padding-left: 10px;
        width: 1%;
        white-space: nowrap;
    }
</style>