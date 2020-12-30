@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 物流管理 / 运送方式' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
           {{--<p style="margin:5px 0 ;padding:0">将物流方式组合成适合业务场景的运送方式，在渠道前端进行展示</p>--}}
           {{--<p style="margin:5px 0 ;padding:0">比如设置普通快递包含中通、圆通、申通...，那么前端渠道只显示普通快递，在发货环节确定最终物流</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('backend.delivery.delivery._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '70%']" data-url="{{URL::asset('/delivery/delivery/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加运送配置
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/delivery/delivery/list">
                <thead>
                <tr class="table-head">
                    <td style="width: 15%;">运送名称</td>
					<td style="width: 15%;">展示名称</td>
					<td style="width: 20%;">包含快递</td>
					<td>适用场景</td>
					<td>货到付款</td>
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

@endsection