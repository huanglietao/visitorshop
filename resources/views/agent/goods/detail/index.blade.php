<!DOCTYPE html>
<link rel="stylesheet" href="{{ URL::asset('css/agent/goods/goods.css') }}">
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    input[type="number"]{
        -moz-appearance: textfield;
    }
</style>
@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    {{--@component('component/goods_crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 商品管理 / 商品列表 / 商品详情' ])--}}
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
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 商品管理 / 商品详情' ])
    @endcomponent
    <!-- 面包屑 end  -->

    {{--商品 start--}}
    <div id="main">
        <div class="detail-one">
            {{--上半部分 start--}}
            <input id="prod_id" hidden type="text" value="{{$product['prod_id']}}"/>
            <input id="mid" hidden type="text" value="{{$product['mid']}}"/>
            <input id="aid" hidden type="text" value="{{$product['aid']}}"/>
            <input id="comprint" hidden type="text" value="{{$is_comprint}}"/>

            <div class="detail-two" style="position: relative;" id="attribute-main">
                <div class="detail-three" >
                    <div class="preview">
                        <div id="vertical" class="bigImg">
                            <img @if($product['prod_main_thumb']) src="{{$product['prod_main_thumb']}}"@else src="/images/home/moren.jpg" @endif onerror="this.src='/images/home/moren.jpg'" width="400" height="400" alt="" id="midimg" />
                            <div style="left: 165px; top: 77px; display: none;" id="winSelector"></div>
                        </div>

                        <div class="smallImg">
                            <div id="imageMenu">
                                <ul style="margin-left: 0px;">
                                    @foreach($product['prod_photos'] as $key=>$value)
                                        <li><img @if($value) src="{{$value}}" @else src="/images/home/moren.jpg" @endif onerror="this.src='/images/home/moren.jpg'" width="56" height="56"/></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div id="bigView" style="width: 470px; height: 420px; top: 75px; left: 565px; display: none;">
                            <img width="800" height="800" alt="" @if($product['prod_main_thumb']) src="{{$product['prod_main_thumb']}}" @else src="/images/home/moren.jpg" @endif onerror="this.src='/images/home/moren.jpg'"  style="left: -330px; top: -154px;" />
                        </div>
                    </div>
                    {{--<div class="detail-eighteen">--}}
                        {{--<span class="detail-twenty"><i class="fa fa-share-alt fa-lg detail-nineteen"></i>分享</span>--}}
                        {{--<span class="detail-twenty-one"><i class="fa fa-star-o fa-lg detail-nineteen"></i>收藏&nbsp;&nbsp;&nbsp;(21576人气)</span>--}}
                    {{--</div>--}}
                </div>
                <div class="detail-twenty-two" id="attribute-detail">
                    <div class="detail-twenty-three">
                        <span class="detail-twenty-four">{{$product['prod_name']}}</span>
                        <span class="detail-twenty-five">{{$product['prod_title']}}</span>
                    </div>
                    <div class="detail-twenty-six">
                        <div class="detail-twenty-seven">
                            <span class="detail-twenty-eight">本店售价</span>
                            <span id="product_prices" class="detail-twenty-nine">
                                @if(empty($attr_info))
                                    {{--无属性集合，则价格只有一个，显示一个即可--}}
                                    @if($product['product_price'])￥{{min($product['product_price'])}}@else ￥0.00 @endif
                                @else
                                    {{--有属性集合，则价格有多个，显示价格区间--}}
                                    @if($product['product_price'])￥{{min($product['product_price'])}}~{{max($product['product_price'])}} @else ￥0.00 @endif
                                @endif



                            </span>
                            <span id="product_price" style="display: none" class="detail-twenty-nine"></span>
                            <div class="detail-thirty">
                                {{--<div class="detail-thirty-one detail-eighty-five">累计评价</div>--}}
                                {{--<div class="detail-thirty-two">21757</div>--}}
                                {{--<div class="detail-thirty-one detail-eighty-six">|</div>--}}
                                {{--<div class="detail-thirty-one detail-eighty-seven">累计销量</div>--}}
                                {{--<div class="detail-thirty-three">28615</div>--}}
                            </div>
                        </div>
                        {{--<div class="detail-thirty-four">
                            <span class="detail-thirty-five">市场价</span>
                            <span class="detail-thirty-six">￥49.9</span>
                            <div class="detail-thirty-seven">
                                <div class="detail-thirty-eight">
                                    <span class="detail-eighty-eight">手机购买</span>
                                    <div class="detail-eighty-nine">
                                        <div onmouseover="this.className = 'detail-weixin on';" onmouseout="this.className = 'detail-weixin';">
                                            <i class="fa fa-qrcode fa-lg detail-thirty-nine" ></i>
                                            <div class="detail-weixin_nr">
                                                <div class="detail-arrow"></div>
                                                <img src="/images/25.jpg" width="100" height="100" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                    </div>
                    {{--<div class="detail-forty">
                        <div class="detail-forty-one">运费</div>
                        <div class="detail-forty-two">广州市&nbsp;&nbsp;至&nbsp;&nbsp;</div>
                        <div class="detail-forty-three">广州市</div>
                        <div class="detail-forty-five">快递：0.00元</div>
                    </div>--}}

                    {{--商品SKU start--}}
                    @if(!empty($attr_info))
                        <input id="rel_attr_ids" type="text" hidden  value="{{$rel_attr_ids}}" data-type="sku"/>
                        @foreach($attr_info as $attr_key => $attr_val)
                            {{--<div class="detail-forty-six" @if(count($attr_val['attr_val_name'])==1)style="display: none" @endif>--}}
                            <div class="detail-forty-six">
                                <span style="float: left;margin-right: 10px;color: rgb(121, 119, 119);width: 50px;margin-top: 5px;">{{$attr_val['attr_name']}}</span>

                                <div id="attr_val" style="overflow: hidden">
                                    <input type="number" data-value="numbers" hidden value="{{$attr_val['rel_attr_id'][0]}}"/>
                                    @foreach($attr_val['attr_val_name'] as $attr_k => $attr_v)
                                        @if($attr_k==0)
                                            <div class="detail-forty-eight has" style="border:1px solid red" data-id="{{$attr_val['rel_attr_id'][$attr_k]}}">{{$attr_v}}
                                            <i></i>
                                            </div>
                                        @else
                                            <div class="detail-forty-eight" data-id="{{$attr_val['rel_attr_id'][$attr_k]}}">{{$attr_v}}</div>
                                        @endif

                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <input id="rel_attr_ids" type="hidden" value="0"  data-type="spu" />
                    @endif



                    {{--商品SKU end--}}
                    <div class="detail-forty-six" style=";overflow: hidden">
                        <div class="detail-forty-seven" style="width: 50px;margin-right: 10px;">数量</div>
                        <div class="detail-forty-nine">
                            <input id="nums" class="detail-fifty prod_nums" type="number" value="1" />
                            <div class="detail-fifty-one">
                                <div class="detail-fifty-two"><i class="fa fa-angle-up fa-lg detail-fifty-three" style="vertical-align: 15%;"></i></div>
                                <div class="detail-fifty-four"><i class="fa fa-angle-down fa-lg detail-fifty-three" style="vertical-align: 15%;"></i></div>
                            </div>
                        </div>
                        <div class="detail-fifty-five" @if($product['prod_stock_status']==0)style="display: none" @else @endif >库存&nbsp;
                            <input id="prod_stock_status" type="hidden" value="{{$product['prod_stock_status']}}"/>
                            <span>{{$product['prod_stock_inventory']}}</span>件
                        </div>
                    </div>


                    {{--按钮 start--}}
                    <div class="detail-fifty-six" style="position: relative">
                        <div class="detail-fifty-seven" style="@if($price_type == SPU && $spu_onsale == 0 || $sku_onsale==0) @else display: none @endif">
                            <button class="btn btn-white" disabled="disabled" style="width: 90px;left: 75px;cursor: not-allowed;background-color: #9b9b9b;color: white;">未上架</button>
                        </div>
                        @if($is_entity)
                            <div class="detail-ninety-one" style="@if($price_type == SPU && $spu_onsale == 0) display: none @endif">
                                <button class="btn btn-white btn-add-cart" style="width: 110px;background-color: red;color: white;">加入购物车</button>
                            </div>
                        @else
                            <div class="detail-ninety-one" style="@if($price_type == SPU && $spu_onsale == 0 || $sku_onsale==0) display: none @endif">
                                <button id="maketemplate" class="btn btn-white" style="width: auto;left: 0px;"><i class="fa fa-paint-brush fa-lg"></i> &nbsp;&nbsp;在线设计</button>
                                <button id="workUpload" class="btn btn-white" style="width: auto;left: 115px;"><i class="fa fa-cloud-upload fa-lg"></i> &nbsp;&nbsp;上传稿件</button>
                                <button id="makeURL" class="btn btn-white" style="width: auto;left: 230px;"><i class="fa fa-link fa-lg"></i> &nbsp;&nbsp;制作链接</button>
                            </div>
                        @endif
                    </div>
                    {{--按钮 end--}}

                    {{--温馨提示 start--}}
                    @if($product['prod_aftersale_flag'])
                    <div class="detail-sixty-one" style="">
                        <span class="detail-sixty-two">温馨提示：</span>
                        @foreach($product['prod_aftersale_flag'] as $ask => $asv)
                            <span class="detail-sixty-three">{{$after_sale[$asv]}}</span>
                        @endforeach
                    </div>
                    {{--温馨提示 end--}}
                    @endif
                </div>
                <div style="clear: both"></div>
            </div>
            {{--上半部分 end--}}

            {{--下半部分 start--}}
            <div class="detail-sixty-four" id="detail-sixty-four-father">
                {{--推荐 start--}}
                <div class="detail-sixty-five" id="detail-sixty-five-son">
                    <div class="detail-sixty-six">相关推荐</div>
                    @foreach($recommendPro as $k => $v)
                        @if($k<4)
                        <a href="{{URL::asset('/goods/detail/index/'.$v['prod_id'])}}">
                            <div class="detail-sixty-seven">
                                <div class="detail-sixty-eight">
                                    <img class="detail-sixty-nine" @if($v['prod_main_thumb']) src="{{$v['prod_main_thumb']}}" @else src="/images/home/moren.jpg" @endif onerror="this.src='/images/home/moren.jpg'" width="56" height="56"/>
                                </div>
                                <div class="detail-seventy" style="overflow: hidden">{{$v['prod_name']}}</div>
                                <div class="detail-seventy-one" style="overflow: hidden">{{$v['prod_title']}}</div>
                                <div class="detail-seventy-two">￥ {{$v['product_price']}} 元</div>
                            </div>
                        </a>
                        @endif
                    @endforeach


                </div>
                {{--推荐 end--}}

                {{--商品详情介绍 start--}}
                <div class="detail-seventy-three" id="detail-seventy-three-son">
                    {{--导航栏组件 start--}}
                    @component('component.navOperateTab',['navlist'=>['商品详情'],'extendPadding'=>'0 30'])

                    @endcomponent
                    {{--导航栏组件 end--}}

                    <div class="bottom">
                        <div name="content" class="details goodsDetail goodsShow" style="text-align: center;padding: 10px">
                            {!! $product['prod_details_pc'] !!}
                        </div>
                        <div name="content"  class="comment goodsDetail">用户评论</div>
                        <div name="content"  class="transaction goodsDetail">用户交易</div>
                    </div>
                </div>
                {{--商品详情介绍 end--}}
                <div style="clear: both"></div>
            </div>
            {{--下半部分 end--}}
        </div>
    </div>
    {{--商品 end--}}

@endsection


<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/goods/magnifier.js') }}"></script>
    {{--<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.1.1.js"></script>--}}
@endsection

@section("pages-js")
    //父元素高度动态赋值
    var attribute_main = document.getElementById('attribute-main');
    var attribute_detail = document.getElementById('attribute-detail');
    attribute_main.style.height=attribute_detail.offsetHeight+'px';

    var detail_sixty_four = document.getElementById('detail-sixty-four-father');
    var detail_seventy_three = document.getElementById('detail-seventy-three-son');
    var detail_sixty_five = document.getElementById('detail-sixty-five-son');
    var height = detail_seventy_three.offsetHeight > detail_sixty_five.offsetHeight ? detail_seventy_three.offsetHeight : detail_sixty_five.offsetHeight;
    detail_sixty_four.style.height = height+'px';

    //窗口尺寸改变
    window.onresize = function() {
        var detail_sixty_four = document.getElementById('detail-sixty-four-father');
        var detail_seventy_three = document.getElementById('detail-seventy-three-son');
        var detail_sixty_five = document.getElementById('detail-sixty-five-son');
        var height = detail_seventy_three.offsetHeight > detail_sixty_five.offsetHeight ? detail_seventy_three.offsetHeight : detail_sixty_five.offsetHeight;
        detail_sixty_four.style.height = height+'px';
    }
@endsection




