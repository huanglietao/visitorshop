<!-- 商品模块面包屑-->

<div class="crumb">
    <div>
        <i class="fa {{isset($icon)?$icon:''}}"></i>&nbsp; {{isset($title)?$title:''}}
        {{$slot}}
    </div>
</div>


