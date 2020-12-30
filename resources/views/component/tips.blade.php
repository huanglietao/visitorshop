<!-- 通用操作提示框 -->
<div class="explanation" id="explanation" style="max-width: 100%">
    <div class="ex_tit"><span class="ex_icon"></span><h4>操作提示</h4></div>
    <div class="ex_descrition">
        <p>{{$slot}}</p>
    </div>
    <div id="explanationBox" title="收起提示" ></div>
    <i id="explanationZoom"  class="fa fa-minus" aria-hidden="true"></i>
</div>

<style>
    .ex_descrition p{
        margin: 5px 0;
    }
</style>

