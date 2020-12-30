<!-- 通用面包屑 -->

<div class="crumb">
    <div>
        <i class="fa {{isset($icon)?$icon:''}}"></i>&nbsp; <span id="bcrumb">{{isset($title)?$title:''}}</span>
        <span class="back" style="border-radius: 0" onclick="history.go(-1)">
            <img src="/images/back.png" style="width:15px;position:relative;top:-2px">
            <span style="color: black;margin-left: 6px">返回上一页</span>
        </span>
        {{$slot}}
    </div>
</div>


