@extends('layout.erp_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 业务数据导入' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
           <p style="margin:5px 0 ;padding:0">导入数据格式必须严格按我司数据格式要求，否则将导致数据无法解析，业务不能顺利进行。</p>
           <p style="margin:5px 0 ;padding:0">请导入数据前，请先下载文件模板，如果模板文件不存在，请联系客服人员。</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('erp.order.import._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button hidden class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button id="excel_btn" class="btn  btn-3F51B5" style="color: white"><i class="fa fa-cloud-upload"></i>&nbsp;导入订单</button>
            <input id="excel_upload" hidden style='width:140px;display: inline-block;padding-left: 0' type='file' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="col-sm-5" onchange="upload(this)">
            <a href = "{{URL::asset('业务数据导入模板.xlsx')}}">
            <button id="excel_download" class="btn btn-3F51B5" style="color: white;background-color: #FF6600;border-color: #FF6600">
                <i class="fa fa-cloud-download"></i>&nbsp;
                    下载模板
            </button> </a>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/import/list">
                <thead>
                <tr class="table-head">
                    <td>客户订单号</td>
                    <td>预约时间</td>
					<td>合并发货</td>
					<td>客户简称</td>
					<td>产品名称</td>
					<td>订购数量</td>
					<td>指定物流方式</td>
					<td>收件人</td>
					<td>收件人手机</td>
					<td>收件人地址</td>
					<td>发件人</td>
					<td>发件人手机</td>
					<td>发件人地址</td>
					<td style="width:6%">状态</td>
					<td>备注</td>
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
<!---  引入当前页需使用的js  -->
@section("js-file")
    <script src="{{ URL::asset('js/erp/index.js')}}"></script>
@endsection
@section("pages-js")
    //======================Excel导入开始 =============//
    $("#excel_btn").click(function () {
        $("#excel_upload").click();
    });

    function upload(obj){
        var files = obj.files;
        for(var i = 0;i<files.length;i++){
            fd = new FormData();
            var file = files[i];
            tips(file.name,0,1);
            fd.append('file',file);
            fd.append('_token','{{csrf_token()}}');

            $("#excel_upload").val('');
            $.ajax({
                url: "/import/ExcelImport",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function (ret) {
                    if(ret['status']==200){
                        filename = ret['file_data']['filename'];
                        total = ret['file_data']['total'];
                        tips(filename,total,2);
                    }
                }
            });


        }

    }
    //======================Excel导入结束 =============//
@endsection
