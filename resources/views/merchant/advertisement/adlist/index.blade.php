@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '广告管理/广告列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.advertisement.adlist._search')
        </div>
        <!--  提示功能 end -->

        <!-- tab功能 start  -->
        <div style="margin:20px 0 10px 0">
            @component('component.navOperateTab',['navlist'=>$channelList,'defaut_key'=>$firstChannel,'extendClass'=>'adlist_channel'])
            @endcomponent
        </div>
        <!--  tab功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['65%', '60%']" data-url="{{URL::asset('/advertisement/adlist/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/advertisement/adlist/list">
                <thead>
                <tr class="table-head">
                    <td>广告图</td>
                    <td>标题</td>
                    <td>所属渠道</td>
                    <td>广告类型</td>
                    <td>位置说明</td>
                   {{-- <td>广告标识</td>--}}
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
    <script src="{{ URL::asset('js/merchant/ad.js')}}"></script>
@endsection