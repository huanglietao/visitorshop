@extends('layout.mch_iframe')
@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '示例参考/示例列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">该页面展示了所有能使用DMS系统的人员账号清单。</p>
            <p style="margin:5px 0;padding:0">管理员可手动添加并分配对应系统功能权限，查看该管理操作系统日志信息。。</p>
            <p style="margin:5px 0;padding:0">管理员账号支持主账号与子账号关联关系，主账号能看到所有子账号信息，各子账号只能看到自己信息，可配合权限使用。</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('agent.demo._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['70%', '700px']" data-url="{{URL::asset('/demo/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加管理员
            </button>



                @component('component/tips_modal')
                @slot('slot_ele')
                    <button >查看物流</button>
                @endslot
                    <p>中通快递：75170877098216</p>
                    <ul>
                        <li>[广州市]番禺南沙的自贸区派件师傅D[17665154055]正在派件（95720为中通快递员外呼专属号码，请放心接听）2019-09-02 09:33:33</li>
                        <li>[广州市]快件已到达番禺南沙2019-09-02 06:54:29</li>
                        <li>以上为最新跟踪信息查看全部</li>
                    </ul>
                @endcomponent

        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/demo/list">
                <thead>
                <tr class="table-head">
                    <td>账号</td>
                    <td>姓名</td>
                    <td>所属账号</td>
                    <td>角色组</td>
                    <td>Email</td>
                    <td>状态</td>
                    <td>创建时间</td>
                    <td>最后登录时间</td>
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