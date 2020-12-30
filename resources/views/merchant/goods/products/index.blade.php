<!DOCTYPE html>
@extends('layout.mch_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/merchant/goods/products.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '商品管理/商品列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')

        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.goods.products._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
            <button data-area="['80%', '80%']" data-title="添加商品" data-url="{{URL::asset('/goods/products/add_standard_products')}}" type="button" class="btn btn-primary btn-3F51B5 btn-sm btn-dialog"><i class="fa fa-plus"></i>
                &nbsp;添加标准化商品</button>
            <button data-url="{{URL::asset('/goods/products/form')}}" data-title = "添加" class="btn btn-primary btn-sm goods-add btn-3F51B5 btn-add">
                <i class="fa fa-plus"></i>
                &nbsp;添加自定义商品
            </button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/goods/products/list?{{$_SERVER['QUERY_STRING']}}">
                <thead>
                <tr class="s_header_tr"></tr>
                <tr class="table-head">
                    <td class="first_td">
                       {{-- @component('component/checkbox',['checkbox'=>[''],'name'=>['checkall'],'custom_class'=>"cc_checkall checkbox",'right_distance'=>10])
                        @endcomponent--}}
                    </td>
                    <td>
                        <span>商品名称</span>
                    </td>
                    <td>所属分类</td>
                    <td>价格/运费</td>
                    {{--<td>标签</td>--}}
                    <td>排序</td>
                    <td>库存</td>
                    <td>销售端</td>
                    <td>状态</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
                <tr class="s_header_tr"></tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>

            <div id="act-btn" style=" margin: 6px 0 0 10px;float: left;">
         {{--       @component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"cc_checkall checkbox",'right_distance'=>10])
                @endcomponent--}}
                {{--<select name="examine_status" class="examine_status" style="padding:4px 8px;color: #6A6969;background: #ffffff;width: 120px;height: 26px;">
                    <option value="">请选择批量操作</option>
                    <option value="1">操作1</option>
                    <option value="0">操作2</option>
                </select>
                <button class="btn btn-orange btn-dialog" style="color: #ffffff;vertical-align: top;"  data-url="" data-title = "批量发货">确定</button>--}}
            </div>

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

    <script src="{{ URL::asset('js/merchant/goods/products.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/goods/standard_products.js')}}"></script>
@endsection
