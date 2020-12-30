@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 物流管理 / 物流公司' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
           {{--<p style="margin:5px 0 ;padding:0">添加系统可能需要用到的快递的方式，快递名称、相关介绍以及简称</p>--}}
           {{--<p style="margin:5px 0 ;padding:0">这里只做基础信息的操作，不在此处设置价格和负责区域</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('backend.delivery.express._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '70%']" data-url="{{URL::asset('/delivery/express/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加快递
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/delivery/express/list">
                <thead>
                <tr class="table-head">
                    <td style="width: 35%;">快递名称</td>
                    <td>快递代号</td>
                    <td>快递类型</td>
                    <td>状态</td>
                    <td>创建时间</td>
                    <td>描述</td>
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
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection