<link rel="stylesheet" href="{{ URL::asset('css/agent/goods/goods.css') }}">
@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 商品管理 / 我的收藏' ])
    @endcomponent
    <!-- 面包屑 end  -->

    <div id="main">
        {{--图片 start--}}
        <div class="category-one">
            <img class="category-two" src="/images/9.jpg">
        </div>
        {{--图片 end--}}

        {{--商品列表 start--}}
        @component('component/goods_list',['is_show' => 0])
            @slot('title')
                我的收藏
            @endslot
            @slot('subtitle')
                喜欢的商品
            @endslot
            @if($products_list)
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
            @else
                <div style="margin: 0 auto;color:#6A6969">
                    暂无收藏商品
                </div>
            @endif
        @endcomponent
        {{--商品列表 end--}}
    </div>

@endsection


<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/goods/fileUpload.js') }}"></script>
@endsection
@section("pages-js")

@endsection




