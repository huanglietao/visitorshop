@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '模板管理/素材管理' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px;">
            @include('backend.template.material._search')
        </div>
        <!--  提示功能 end -->
        <div style="margin:20px 0 10px 0">
            @component('component.navOperateTab',['navlist'=>$cateList,'defaut_key'=>$materType])

            @endcomponent
        </div>
        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['65%', '70%']" data-url="{{URL::asset('/template/material/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/template/material/list">
                <thead>
                <tr class="table-head">
                    <td>示意图片</td>
                    <td>素材类型</td>
                    <td>所属分类</td>
                    <td>素材用途</td>
                    <td>规格标签</td>
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
    <script src="{{ URL::asset('js/backend/material.js')}}"></script>
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection