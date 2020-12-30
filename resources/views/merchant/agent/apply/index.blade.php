@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 分销管理 / 商家申请表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">分销商账户申请记录表，可以创建分销账户，修改分销账户资料信息</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.agent.apply._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            {{--<button data-area="['70%', '700px']" data-url="{{URL::asset('/agent/apply/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">--}}
                {{--<i class="fa fa-plus"></i>--}}
                {{--&nbsp;添加--}}
            {{--</button>--}}
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/agent/apply/list">
                <thead>
                <tr class="table-head">
					<td>店铺名称</td>
					<td>店铺类型</td>
					<td>店铺等级</td>
					<td>联系人</td>
					<td>手机号码</td>
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

<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection