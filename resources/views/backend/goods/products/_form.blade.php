<!DOCTYPE html>
@extends('layout.iframe')

@section("main-content")
@section("pages-css")
    <link rel="stylesheet" href="{{ URL::asset('css/merchant/goods/size.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/backend/goods/products.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/umeditor/themes/default/css/umeditor.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/selectpicker/css/bootstrap-select.css') }}">
@endsection



    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '商品管理/商品列表/添加商品' ])
    @endcomponent
    <!-- 面包屑组件end  -->
    <div id="main">
    <!--  提示组件 start -->
    @component('component/tips')
    <p style="margin-top: 1rem">添加商品共分4个步骤：选分类、填信息、设属性、选关联。</p>
    <p>标识为“*”的选项为必填项，其它为选填项，请根据选项内容提示按需谨慎填写。</p>
    @endcomponent
    <!--  提示组件 end -->

        <div class="cate-main">
            <form class="form-horizontal common-form" id="form-save" method="post" action="/goods/products/save" onsubmit="return false;" autocomplete="off">
                <!--  选择分类 start -->
            {!! $goodsCategory !!}
            <!--  选择分类 end -->

                {{--填写商品信息 start--}}
                {!! $goodsInfo !!}
                {{--填写商品信息 end--}}

                {{--填写货品信息 start--}}
                {!! $goodsAttr !!}
                {{--填写货品信息 end--}}



            </form>
        </div>



@endsection
<!---  引入当前页需使用的js  -->
@section("pages-js")


@endsection
@section("js-file")
   <script src="{{ URL::asset('assets/umeditor/umeditor.config.js')}}"></script>
   <script src="{{ URL::asset('assets/umeditor/umeditor.js')}}"></script>
   <script src="{{ URL::asset('assets/umeditor/lang/zh-cn/zh-cn.js')}}"></script>

   <script src="{{ URL::asset('assets/selectpicker/js/bootstrap-select.js')}}"></script>
   <script src="{{ URL::asset('js/backend/goods/add.js')}}?v={{config('common.js_version')}}"></script>

@endsection
