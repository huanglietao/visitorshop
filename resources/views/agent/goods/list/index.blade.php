<link rel="stylesheet" href="{{ URL::asset('css/agent/goods/goods.css') }}">
@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/goods_crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 商品管理 / 商品中心' ])
        <div class="goods-crumb-one">
            <div class="goods-crumb-two goods-crumb-three">
                <form  method="post" action="/goods/category/searchgoods" >
                    {{ csrf_field() }}
                    <input type="text" placeholder="请输入商品名称" name="cname">
                    <button type="submit">搜索</button>
                </form>
            </div>
        </div>
    @endcomponent
    {{--@component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 商品管理 / 商品中心' ])--}}
    {{--@endcomponent--}}
    <!-- 面包屑 end  -->

    <div id="main">
        {{--侧滑栏+轮播 start--}}
        <div class="goods-five">

            {{--侧滑栏--}}
            <div class="menu" style="padding: 30px 0;background-color: rgb(27, 35, 56);">
                {{--<div class="menuTop"></div>--}}
                <ul>
                    <li>
                        <a href="{{URL::asset('/goods/category/index/all')}}" class="goods-thirteen">全部</a>
                        <i class="fa fa-caret-right fa-lg goods-fourteen" style="margin-top: 10px;"></i>
                    </li>
                    @foreach($category_list as $cate_key => $cate_val)
                        <li>
                            <a href="{{URL::asset('/goods/category/index/'.$cate_key)}}" class="goods-thirteen">{{$cate_val[0]}}</a>
                            <i class="fa fa-caret-right fa-lg goods-fourteen" style="margin-top: 10px;"></i>
                            <div class="submenu">
                                @foreach($products_list[$cate_key] as $product_key => $product_val)
                                    @if($product_key%4==0)<div class="goods-thirty-four">
                                    {{--@else--}}
                                    @endif
                                    <a href="{{URL::asset('/goods/detail/index/'.$product_val['prod_id'])}}">
                                        <div class="goods-seven">
                                            <div class="goods-eight">
                                                <img class="goods-nine" style="height: 70px" @if($product_val['prod_main_thumb']) src="{{$product_val['prod_main_thumb']}}"@else src="/images/home/moren.jpg" @endif onerror="this.src='/images/home/moren.jpg'">
                                            </div>
                                            <div class="goods-ten">
                                                <span class="goods-eleven">{{$product_val['prod_name']}}</span>
                                                {{--<span class="goods-twelve">成品尺寸:12.7*8.9cm</span>--}}
                                            </div>
                                        </div>
                                    </a>
                                    @if($product_key%4==3)</div>
                                    @else
                                    @endif
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                </ul>
                {{--<div class="menuTop"></div>--}}
            </div>

            {{--轮播--}}
            <div class="goods-fifteen goods-thirty-five">
                <div class="goods-thirty-six">
                    <div class="slider goods-thirty-seven" id="slider" e-fun="slider">
                        @if(isset($goodsAd[0]['ad_images']))
                            @foreach($goodsAd[0]['ad_images'] as $k => $v)
                                <a href="javascript:" class="goods-thirty-eight">
                                    <img src="{{$v}}" alt="" class="goods-thirty-nine" />
                                </a>
                            @endforeach
                        @else
                            <a href="javascript:" class="goods-thirty-eight">
                                <img src="/images/dms01.jpg" alt="" class="goods-thirty-nine" />
                            </a>
                            <a href="javascript:" class="goods-thirty-eight">
                                <img src="/images/dms02.jpg" alt="" class="goods-thirty-nine" />
                            </a>
                        @endif

                        {{--<a href="javascript:" class="goods-thirty-eight">--}}
                            {{--<img src="/images/8.jpg" alt="" class="goods-thirty-nine" />--}}
                        {{--</a>--}}
                        <div class="s_tag goods-fourty">
                            <span class="goods-fourty-one"></span>
                            <span class="goods-fourty-two"></span>
                            <span class="goods-fourty-two"></span>
                        </div>
                        <span class="s_btn goods-fourty-three"></span>
                        <span class="s_btn goods-fourty-three"></span>
                    </div>
                </div>
            </div>
        </div>
        {{--侧滑栏+轮播 end--}}

        {{--商品列表 start--}}
        @foreach($category as $cate_k => $cate_v)
            @component('component/goods_list',['is_show' => 1,'href'=>$cate_k])
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
    </div>
@endsection


<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/goods/slider-photo.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/agent/goods/fileUpload.js') }}"></script>
@endsection

@section("pages-js")

@endsection




