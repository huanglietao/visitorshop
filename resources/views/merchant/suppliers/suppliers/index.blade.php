@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 供求管理 / 供应商列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">供应商管理主要是针对生产供应链商家进行业务数据配置及管理</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.suppliers.suppliers._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button id="btn_area" data-area="['70%', '70%']" data-url="{{URL::asset('/suppliers/suppliers/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/suppliers/suppliers/list">
                <thead>
                <tr class="table-head">
                    <td>供应商名称</td>
                    <td>供应商编号</td>
                    <td>联系人</td>
                    <td>电话/手机</td>
                    <td>所在区域</td>
                    <td>供应商类型</td>
                    <td>供应商产能</td>
                    <td>订单分配量</td>
                    <td>产能单位</td>
                    <td>状态</td>
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
<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script src="{{ URL::asset('js/backend/delivery.js')}}"></script>
    <script src="{{ URL::asset('js/backend/suppliers.js')}}"></script>

@endsection