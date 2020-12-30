@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 会员管理 / 会员列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p>会员列表查看所有会员信息</p>--}}
            {{--<p>标识“*”的选项为必填项，其它为选填项，信息内容填写时请注意内容格式及长度</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.user.user._search',['gradeList'=>$gradeList])
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '70%']" data-url="{{URL::asset('/user/user/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/user/user/list">
                <thead>
                <tr class="table-head">
                    <td style="width:20%">头像</td>
                    <td style="width:14%">账号</td>
                    <td style="width:14%">昵称</td>
                    <td style="width:14%">等级</td>
					<td style="width:14%">状态</td>
                    <td style="width:12%">加入时间</td>
                    <td style="width:12%">操作</td>
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
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/merchant/user/index.js')}}"></script>
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
@endsection
@section("pages-js")

@endsection
