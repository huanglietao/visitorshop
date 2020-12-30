<link rel="stylesheet" href="{{ URL::asset('css/agent/goods/goods.css') }}">
@extends('layout.iframe')

@section("main-content")

    @php
        if($category_id == 'all'){
            $category_name = '全部';
        }else{
            $category_name = $category_info[0];
        }
    @endphp

    <!-- 面包屑 start  -->
    {{--@component('component/goods_crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 商品管理 / 商品分类 / '.$category_name])--}}
        {{--<div class="goods-crumb-one">--}}
            {{--<div class="goods-crumb-two goods-crumb-three">--}}
                {{--<form method="post" action="/goods/category/searchgoods">--}}
                    {{--{{ csrf_field() }}--}}
                    {{--<input type="text" placeholder="请输入商品名称" name="cname">--}}
                    {{--<button type="submit">搜索</button>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@endcomponent--}}
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 商品管理 / 商品分类' ])
    @endcomponent
    <!-- 面包屑 end  -->

    <div id="main">
        {{--图片 start--}}
        <div class="category-one">
            <img class="category-two" src="/images/9.jpg">
        </div>
        {{--图片 end--}}

        {{--商品列表 start--}}
        @if($category_id == 'all')
            @foreach($category_info as $cate_k => $cate_v)
                @component('component/goods_list',['is_show' => 0])
                    @slot('title')
                        {{$cate_v[0]}}
                    @endslot
                    @slot('subtitle')
                        {{$cate_v[1]}}
                    @endslot
                    @foreach($products_list[$cate_k] as $product_key => $product_val)
                        <a href="{{URL::asset('/goods/detail/index/'.$product_val['prod_id'])}}">
                            <div class="goods-twenty-seven category-four">
                                <div class="goods-twenty-eight-two">
                                    <img class="goods-nine-two" @if($product_val['prod_main_thumb']) src="{{$product_val['prod_main_thumb']}}" @else src="/images/home/moren.jpg" @endif onerror="this.src='/images/home/moren.jpg'">
                                </div>
                                <span class="goods-twenty-nine">{{$product_val['prod_name']}}</span>
                                <span class="goods-thirty-one">{{$product_val['prod_title']}}</span>
                                <span class="goods-thirty-two">
                                    ￥{{$product_val['prod_fee']}}元
                                    <div id="collect" data-value="{{$product_val['prod_id']}}" style="float: right;display: inline-block">
                                        @if(in_array($product_val['prod_id'],$collect))
                                            <span style='color: red' data-value='1'><i class='fa fa-heart fa-lg' style='margin-right: 5px'></i>已收藏</span>
                                        @else
                                            <span style="color: black" data-value="0"><i class="fa fa-heart-o fa-lg" style="margin-right: 5px"></i>收藏</span>
                                        @endif
                                    </div>
                                </span>
                            </div>
                        </a>
                    @endforeach
                @endcomponent
            @endforeach
        @else
            @component('component/goods_list',['is_show' => 0])
                @slot('title')
                    {{$category_info[0]}}
                @endslot
                @slot('subtitle')
                    {{$category_info[1]}}
                @endslot
                @foreach($products_list as $product_key =>$product_val)
                    <a href="{{URL::asset('/goods/detail/index/'.$product_val['prod_id'])}}">
                        <div class="goods-twenty-seven category-four">
                            <div class="goods-twenty-eight-two">
                                <img class="goods-nine-two" @if($product_val['prod_main_thumb']) src="{{$product_val['prod_main_thumb']}}"@else src="/images/home/moren.jpg" @endif onerror="this.src='/images/home/moren.jpg'" />
                            </div>
                            <span class="goods-twenty-nine">{{$product_val['prod_name']}}</span>
                            <span class="goods-thirty-one">{{$product_val['prod_title']}}</span>
                            <span class="goods-thirty-two">
                                ￥{{$product_val['prod_fee']}}元
                                <div id="collect" data-value="{{$product_val['prod_id']}}" style="float: right;display: inline-block">
                                    @if(in_array($product_val['prod_id'],$collect))
                                        <span style='color: red' data-value='1'><i class='fa fa-heart fa-lg' style='margin-right: 5px'></i>已收藏</span>
                                    @else
                                        <span style="color: black" data-value="0"><i class="fa fa-heart-o fa-lg" style="margin-right: 5px"></i>收藏</span>
                                    @endif
                                </div>
                            </span>
                        </div>
                    </a>
                @endforeach
            @endcomponent
        @endif
        {{--商品列表 end--}}
    </div>

@endsection


<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/goods/fileUpload.js') }}"></script>
@endsection
@section("pages-js")

@endsection




