@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '示例参考/示例列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
           <p>这里请自行加上说明信息</p>
        @endcomponent
        <!--  提示组件 end -->

        <div style="margin:20px 0 10px 0">
            @component('component.navOperateTab',['navlist'=>$cateList,'defaut_key'=>$firstType])

            @endcomponent
        </div>

        <!-- 搜索功能 start  -->
        <div class="" style="display: none">
            @include('backend.category._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>


            <button @if($firstType == 'goods') style="display:  none;@endif" data-area="['70%', '700px']" data-url="{{URL::asset('/category/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>

        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/category/list">
                <thead>
                <tr class="table-head">

					<td>分类名称</td>
					<td>父级分类</td>
					<td>分类单位</td>
					<td>分类状态</td>
                    <td>创建时间</td>
					<td>更新时间</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>
           {{-- @component('component/paginate',['limit' => \Config::get('pageLimit')])
            @endcomponent--}}

        </div>
        <!-- 列表end    -->
    </div>


@endsection
<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script src="{{ URL::asset('js/backend/category.js')}}"></script>
@endsection
