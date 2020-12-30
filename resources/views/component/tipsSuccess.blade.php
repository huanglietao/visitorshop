
<div class="vmiddle">
    <div class="icon">
        <span>
            <img src="{{URL::asset('images/border-success.png')}}" alt="">
        </span>
    </div>
    <div  class="rich_text">
            <p style="line-height:0; margin-bottom:5px;">
                <span class="mb-text">@if ($data['title'] != null){{$data['title']}}@else操作成功@endif</span>
            </p>
        <p style="line-height:0; margin-bottom:5px;">
            @if ($data['html'] != null)
            <span class="new-text">

                {{$data['html']}}

            </span>
            @endif
        </p>
        <p style="line-height:0; margin-bottom:5px;">
            <span class="new-text"><span class="mount" data-num = "{{$data['interval']}}">{{$data['interval']}}</span>s后系统自动返回</span>
        </p>
    </div>

</div>

<style>
    .vmiddle{
        width: 580px;
        height: 112px;
        margin-left: 10px;
        margin-top: 9px;
        z-index: 2;
        background-color: rgb(255, 255, 255);
        box-shadow: rgb(170, 170, 170) 0px 0px 5px 0px;
        border-radius: 5px;
        font-size: 14px;
        padding: 0px;
        border-width: 0px;
        border-style: solid;
        text-align: center;
        line-height: 20px;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        display: flex;
    }
    .icon{
        width: 60px;
        height: 60px;
        margin-left: 54px;
        padding-top: 25px;
        z-index: 3;
        color: rgb(37, 155, 36);
        border-width: 0px;
        border-style: solid;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
    }
    .rich_text{
        z-index: 4;
        font-size: 14px;
        text-align: left;
        line-height: 0px;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        margin: auto 0 auto 42px;
    }
    .mb-text{
        font-weight:700;
        font-size:16px;
        color:rgb(16, 16, 16);
        font-style:normal;
        letter-spacing:0px;
        line-height:24px;
        text-decoration:none;
    }
    .new-text{
        color:rgba(0, 0, 0, 0.88);
        font-style:normal;
        letter-spacing:0px;
        line-height:18px;
        text-decoration:none;
        font-weight:400;
        font-size:12px;
    }
    .user-text{
        font-weight:700;
        font-size:12px;
        color:rgb(63, 81, 181);
        font-style:normal;
        letter-spacing:0px;
        line-height:18px;
        text-decoration:none;
    }
    .icon img  {
        width: 100%;
    }
</style>
