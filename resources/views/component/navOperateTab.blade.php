<!-- 通用状态导航栏操作按钮 -->

<div id="works-status-box" class="nav_operate_tab {{isset($url)?'needCount':''}}" data-action = {{isset($url)?$url:''}}>
    @foreach ($navlist as $key=>$v)
        <div class="workss_status_btn nav_status_btn {{isset($extendClass)?$extendClass:''}}
             @if (isset($defaut_key) && $defaut_key==$key) nav_status_current @elseif(!isset($defaut_key) && $loop->first) nav_status_current @else not_status @endif"
             @if ($loop->first) style="padding:{{isset($extendPadding)?$extendPadding:'0 16'}}px;@if(count($navlist) == 1)border-right: 1px solid rgb(220, 223, 230);border-radius:5px 5px 0; @else border-radius:5px 0 0; @endif" @endif
             @if ($loop->last) style="border-right:1px solid rgb(220, 223, 230);border-radius:0 5px 0 0;padding:{{isset($extendPadding)?$extendPadding:'0 16'}}px" @endif
        data-val="{{$key}}" style="padding:{{isset($extendPadding)?$extendPadding:'0 16'}}px; ">
            {{$v}}
        </div>
    @endforeach

    <div class="nav_custom_button">{{$slot}}</div>
</div>
