@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '商户管理 / 商户资料' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">商户基础信息的录入。</p>--}}
            {{--<p style="margin:5px 0 ;padding:0">商户资料管理主要是商家基本资质信息及账户充值信息等功能集合</p>--}}
            {{--<p style="margin:5px 0;padding:0">商户资料管理中主要包括：基础信息设置、商家账户充值、商家账户资金变动日志及商家功能权限等。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('backend.merchant.info._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '70%']" data-url="{{URL::asset('/merchant/info/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/merchant/info/list">
                <thead>
                <tr class="table-head">
                    <td>商户名称</td>
					<td>联系人</td>
					<td>手机号</td>
					<td>座机</td>
					<td>注册资本(万元)</td>
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
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection
