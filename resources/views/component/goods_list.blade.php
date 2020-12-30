<!-- 商品模块商品列表组件-->

<div class="goods-list-one">
    <div class="goods-list-two">
        <span class="goods-list-three">{{$title}}</span>
        <span class="goods-list-four">{{$subtitle}}</span>
        @if ($is_show === 1)
            <a href="{{URL::asset('/goods/category/index/'.$href)}}" class="goods-list-three" style="float: right">查看更多&nbsp;></a>
        @endif
    </div>
    <div  class="goods-list-five">
        {{$slot}}
    </div>
</div>


