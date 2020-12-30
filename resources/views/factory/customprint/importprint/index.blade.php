<!doctype html>
<title>自定义打单</title>
@extends('layout.mch_iframe')

@section("main-content")
    <div id="main">
        <!-- tab状态按钮 start  -->
        <div class="nav_tab">
            @component('component.navOperateTab',['navlist'=>$navList,'defaut_key'=>$default_key,])
            @endcomponent
        </div>
        <!-- tab状态按钮 end  -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('factory.customprint.importprint._search')
        </div>
        <!--  搜索功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding-bottom: 20px; padding-top: 20px;">
            <button class="btn btn-white btn-refresh" style="display: none"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            {{--            <button class="btn btn-write btn-dialog"  data-url="{{URL::asset('/order/test')}}" data-title="批量发货">批量发货</button>--}}
            {{--<a href="/import_print/download">模板下载</a>--}}
            <div style="float: left;margin-bottom: 10px;display: flex">
                <select name="express" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control express">
                    @foreach ($express_list as $k=>$v)
                        <option value={{$k}}>{{$v}}</option>
                    @endforeach
                </select>
                <button class="btn btn-3F51B5 btn-primary btn-print-all" style="cursor:pointer;white-space: nowrap;margin-left: 10px;">批量打单</button>
                <button class="btn btn-3F51B5 btn-primary btn-print-all btn-print-new" style="cursor:pointer;white-space: nowrap;margin-left: 10px;">打印新单</button>
            </div>
            <div style="float: right;">
                <a href="/print_template.zip" target="_blank" style="margin-right: 20px;font-size: 12px">模板下载</a>
                <button id="excel_btn" class="btn btn-write" data-title="导入"><i class="fa fa-download"></i>导入</button>
                <input id="excel_upload" hidden style='width:140px;display: inline-block;padding-left: 0' type='file' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" class="col-sm-5" onchange="upload(this)">
                <button class="btn btn-write btn-order-export" id="export" data-title="导出"><i class="fa fa-upload"></i>导出</button>
            </div>


            <input type="hidden" class="tab_val" value="ALL">
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/import_print/list">
                <thead>
                <tr class="table-head" style="text-align: left">
                    <td style="padding-left: 10px">
                        @component('component/checkbox',['checkbox'=>[''],'name'=>['checkall'],'custom_class'=>"checkall btn-all checkbox",'left_distance'=>10])
                        @endcomponent
                    </td>
                    <td>订单号</td>
                    <td>快递</td>
                    <td>快递单号</td>
                    <td>收件人信息</td>
                    <td>寄件人信息</td>
                    <td>商品内容</td>
                    <td>留言/备注</td>
                    <td>创建时间</td>
                    <td>是否打印</td>
                    <td>操作</td>

                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>


            @component('component/paginate',['limit' => \Config::get('pageLimit')])
            @endcomponent

        </div>
        <!--  列表 end -->


    </div>



@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/factory/custom_print.js')}}"></script>
@endsection







