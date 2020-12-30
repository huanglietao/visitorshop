@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '模板中心/主模板子页' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
           <p>子页是封面或者内页组合而成，供用户生成作品使用，设计人员通过添加背景、素材及相应布局组成主模板的子页</p>
        @endcomponent
        <!--  提示组件 end -->


        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['50%', '70%']" data-url="{{URL::asset('/templatecenter/mainchild/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/templatecenter/mainchild/list">
                <thead>
                <tr class="table-head">
                    <td>子页名称</td>
					<td>子页类型</td>
					<td>是否跨页</td>
					<td>设计区尺寸(mm)</td>
					<td>DPI</td>
					<td>排序</td>
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
    <script src="{{ URL::asset('js/backend/tempcenter/maintemp.js')}}"></script>
@endsection