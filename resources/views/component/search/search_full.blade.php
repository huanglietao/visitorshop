<!--主搜索组件(带完整样式)-->
<form name="search-form" id="search-form" method="post" onsubmit="return false">
<div class="main-search">
        <!-- 主搜索框  -->
        <div  class="search-line">
                 {{$slot_main}}

            <div style="display:  inline-block" class="btn-search">
                <button class="btn  btn-primary btn-sm btn-3F51B5">搜索</button>
                &nbsp;&nbsp;
                <button type="reset" class="btn  btn-default btn-sm table-rest" style=>重置</button>
                @if(isset($has_more) && $has_more==true)
                    &nbsp;&nbsp;
                    <span class="search-more search-more-color">
                    更多搜索条件
                    &nbsp;
                    <i class="fa fa-chevron-down"></i>
                    </span>
                @endif
            </div>
        </div>

</div>


<!-- 隐藏搜索框  -->
@if(isset($has_more) && $has_more==true)
    <div class="search-open" style="padding-top:20px">
        <div class="more search-line" style="display:block">
                {{$slot_hide}}
        </div>
        <div class="open-search-btn">
            <button class="btn  btn-primary btn-sm btn-3F51B5" style="padding:3px 15px;font-size: 12px">搜索</button>
            &nbsp;&nbsp;
            <button type="reset" class="btn  btn-default btn-sm table-rest" style="padding:3px 15px;font-size: 12px">重置</button>

            &nbsp;&nbsp;
            <span class="search-more-hide search-more-color">
                    收起
                    &nbsp;
                    <i class="fa fa-chevron-up"></i>
                </span>
        </div>

    </div>
@endif
</form>
<!--主搜索组件(带完整样式)end-->