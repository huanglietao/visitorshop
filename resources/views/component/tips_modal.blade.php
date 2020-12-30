<!-- 提示展示框，鼠标移上去显示   -->
<span class="hover-show" >
    {{$slot_ele}}

<div class="tips-modal" style="width:{{isset($width)?$width:300}}px;z-index:1">
    <div class="top-arrow"></div>
    <div class="contents">
        {{$slot}}
    </div>
</div>
</span>