<div style="width:100%;margin: 20px 0;display: flex;justify-content: center">
    <div style="width:95%;height:30px;border-bottom:1px solid rgb(223, 223, 223);display: flex;justify-content: space-between">
        <span style="font-size: 14px">销售额统计</span>
        <div>
            <div style="width: 14px;height: 14px;background-color: rgb(183, 181, 181);display: inline-block;vertical-align: text-bottom;margin-right: 10px"></div><span style="margin-right: 40px">去年销售额</span>
            <div style="width: 14px;height: 14px;background-color: rgb(63, 81, 181);display: inline-block;vertical-align: text-bottom;margin-right: 10px"></div><span>今年销售额</span>
        </div>
        <span style="color: rgba(183, 181, 181, 1);">单位：万元</span>
    </div>
</div>

<div  style=";width:90%;margin: 20px auto">
    <canvas id="zchart" width="1000" height="400"></canvas>
</div>


{{--<div style="margin:20px 0">--}}
    {{--@component('component.navOperateTab',['navlist'=>['1'=>'昨日交易','2'=>'昨日收支','3'=>'昨日作品']])--}}

    {{--@endcomponent--}}
{{--</div>--}}

{{--数据展示 start--}}
<div style="width:100%;margin: 50px 0 20px 0;display: flex;justify-content: center">
    <div style="width:95%;height:30px;border-bottom:1px solid rgb(223, 223, 223);display: flex;justify-content: space-between">
        <span style="font-size: 14px">交易金额/成交订单数/作品数统计</span>
        <div class="detail">
            <div style="display: inline-block;">
                <div style="width: 14px;height: 14px;background-color: rgb(63, 81, 181);display: inline-block;vertical-align: text-bottom;"></div>
                <span>成交金额（元）</span>
                <div>￥{{$staData['realTotals']}}</div>
            </div>
            <div style="display: inline-block;">
                <div style="width: 14px;height: 14px;background-color: rgb(114, 204, 66);display: inline-block;vertical-align: text-bottom;"></div>
                <span>成交订单数（笔）</span>
                <div>{{$staData['orderNums']}}</div>
            </div>
            <div style="display: inline-block;">
                <div style="width: 14px;height: 14px;background-color: rgb(112, 33, 33);display: inline-block;vertical-align: text-bottom;"></div>
                <span>总作品数（个）</span>
                <div>{{$staData['worksNums']}}</div>
            </div>
        </div>
    </div>
</div>


<div id="business">
    <div  style="width: 85%;margin: 0 auto;padding-bottom: 25px">
        <div class="check choices">
            <span style="color: rgb(121, 119, 119);">最近30天趋势：</span>
            @component('component/checkbox',['checkbox'=>['交易金额','成交订单数','作品数'],'name'=>['money-0','orders-1','workes-2']])
            @endcomponent
        </div>
    </div>
    {{--折线图--}}
    <div  style=";width:90%;margin: 0 auto">
        <canvas id="cchart" width="1000" height="400"></canvas>
    </div>
</div>
<script>
    const $data = {!! $ordersData !!};
    const $info = {!! $chartInfo !!};
</script>
<script type="text/javascript" src="{{ URL::asset('js/agent/finance/salesAnalysic.js') }}"></script>
<script src="{{ URL::asset('assets/chart.js/Chart.bundle.min.js')}}"></script>