<link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">
@extends('layout.iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 财务统计 / 资金账务' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" class="accounts-one">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p>资金账户充值分为，线下入账和即时到账二种方式</p>--}}
            {{--<p>线下入账是提交的线下充值入账申请，需要财务人员后台审核才能入账；即时到账是通过第三方支付渠道进行在线付款，能实时到账。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        {{--资产 start--}}
        <div class="accounts-three">
            <div class="accounts-four">
                <span class="accounts-five">账户总资产（元）</span>
                <i class="fa fa-eye fa-lg accounts-six"></i>
            </div>
            <div class="accounts-four">
                <span class="accounts-five">￥ <span id="total" class="accounts-five-total">{{$balanceInfo['total_balance']}}</span></span>
            </div>
            <div class="accounts-seven">
                <div class="accounts-eight">
                    <span class="accounts-ten">可用金额：</span>
                    <span class="accounts-eleven">￥<span id="now" class="accounts-five-available">{{$balanceInfo['now_balance']}}</span> 元</span>
                    <span class="accounts-twelve">冻结金额：</span>
                    <span class="accounts-eleven">￥<span id="frozen" class="accounts-five-frozen">{{$balanceInfo['frozen_balance']}}</span> 元</span>
                </div>
                <div class="accounts-nine">
                    @if(empty($balanceInfo['is_remind_status']))
                        @component('component/checkbox',['checkbox'=>['是否启用余额提醒'],'name'=>['cb_notice'],'right_distance'=>10])
                        @endcomponent
                        <i class="help-tips fa fa-question-circle-o accounts-fourteen" aria-hidden="true" data-title="余额低于该值，会发短信提醒商家"></i>
                        <span class="accounts-fifteen">余额报警值：</span>
                        <input type="text" id="remind" class="accounts-sixteen" value="0">
                    @else
                        @component('component/checkbox',['checkbox'=>['是否启用余额提醒'],'name'=>['cb_notice'],'right_distance'=>10,'checked'=>0])
                        @endcomponent
                        <i class="help-tips fa fa-question-circle-o accounts-fourteen" aria-hidden="true" data-title="余额低于该值，会发短信提醒商家"></i>
                        <span class="accounts-fifteen">余额报警值：</span>
                        <input type="text" id="remind"  class="accounts-sixteen" value="{{$balanceInfo['remind_balance']}}">
                    @endif
                    <button id="remind_edit" class="btn btn-orange">修改</button>
                </div>
            </div>
            <div class="accounts-seven">
                <a href={{URL::asset('finance/accounts/index')}}><button id="re-btn" class="btn btn-white" style="width: 75px;">刷新</button></a>
                <a href={{URL::asset('finance/recharge/index')}}><button class="btn btn-3F51B5 btn-primary" style="width: 75px;">充值</button></a>
            </div>
        </div>
        {{--资产 end--}}

        <div class="accounts-twenty">
            <img src="/images/19.jpg" class="account-image">
        </div>

        {{--收入支出 start--}}
        <div class="accounts-twenty-one">
            <div class="accounts-twenty-two">
                <i class="fa fa-dot-circle-o fa-lg accounts-twenty-three"></i>
                <span class="accounts-twenty-four">收入支出</span>
            </div>
            {{--左侧 start--}}
            <div class="accounts-twenty-five">
                <div class="father">
                    {{--导航栏组件 start--}}
                    @component('component.navOperateTab',['navlist'=>['收入','支出'],'extendPadding'=>'0 41'])
                        <span class="accounts-twenty-six">最近30天收支情况。单位：元</span>
                    @endcomponent
                    {{--导航栏组件 end--}}

                    {{--导航栏内容 start--}}
                    <div class="bottom">
                        <div name="content" class="income goodsDetail goodsShow">
                            <div class="subNavBox">
                                <div class="subNav currentDd currentDt">
                                    <div class="accounts-twenty-seven"><i class="fa fa-angle-down fa-lg accounts-twenty-eight"></i></div>
                                    <span class="accounts-twenty-nine">账户充值</span>
                                    <span class="accounts-thirty">{{$balanceStatus['recharge_money']}}</span>
                                </div>
                                <ul class="navContent accounts-thirty-three" style="display: block;">
                                    @foreach($balanceStatus['income']['recharge'] as $reck=>$recv)
                                        <li><span class="accounts-thirty-one">账户充值</span><span class="accounts-thirty-two">{{$recv['cus_balance_change']}}</span></li>
                                    @endforeach
                                </ul>

                                <div class="subNav">
                                    <div class="accounts-twenty-seven"><i class="fa fa-angle-right fa-lg accounts-twenty-eight" ></i></div>
                                    <span class="accounts-twenty-nine">退款入账</span>
                                    <span class="accounts-thirty">{{$balanceStatus['refund_money']}}</span>
                                </div>
                                <ul class="navContent accounts-thirty-three">
                                    @foreach($balanceStatus['income']['refund'] as $refk=>$refv)
                                        <li><span class="accounts-thirty-one">退款入账</span><span class="accounts-thirty-two">{{$refv['cus_balance_change']}}</span></li>
                                    @endforeach
                                </ul>

                                <div class="subNav">
                                    <div class="accounts-twenty-seven"><i class="fa fa-angle-right fa-lg accounts-twenty-eight"></i></div>
                                    <span class="accounts-twenty-nine">其他入账</span>
                                    <span class="accounts-thirty">{{$balanceStatus['give_money']}}</span>
                                </div>
                                <ul class="navContent accounts-thirty-three">
                                    @foreach($balanceStatus['income']['give'] as $gik=>$giv)
                                        <li><span class="accounts-thirty-one">其他入账</span><span class="accounts-thirty-two">{{$giv['cus_balance_change']}}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div name="content"  class="expenditure goodsDetail">
                            <div class="subNavBox">
                                <div class="subNav currentDd currentDt">
                                    <div class="accounts-twenty-seven"><i class="fa fa-angle-down fa-lg accounts-twenty-eight"></i></div>
                                    <span class="accounts-twenty-nine">订单支付</span>
                                    <span class="accounts-thirty">{{$balanceStatus['trade_money']}}</span>
                                </div>
                                <ul class="navContent accounts-thirty-three" style="display: block;">
                                    @foreach($balanceStatus['expenditure']['trade'] as $trk=>$trv)
                                        <li><span class="accounts-thirty-one">订单支付</span><span class="accounts-thirty-two">{{$trv['cus_balance_change']}}</span></li>
                                    @endforeach
                                </ul>

                                <div class="subNav">
                                    <div class="accounts-twenty-seven"><i class="fa fa-angle-right fa-lg accounts-twenty-eight"></i></div>
                                    <span class="accounts-twenty-nine">资金冻结</span>
                                    <span class="accounts-thirty">{{$balanceStatus['frozen_money']}}</span>
                                </div>
                                <ul class="navContent accounts-thirty-three">
                                    @foreach($balanceStatus['expenditure']['frozen'] as $frk=>$frv)
                                        <li><span class="accounts-thirty-one">资金冻结</span><span class="accounts-thirty-two">{{$frv['cus_balance_frozen_change']}}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{--导航栏内容 end--}}
                </div>
            </div>
            {{--左侧 end--}}

            {{--右侧 start--}}
            <div class="accounts-thirty-seven">
                <div class="accounts-thirty-four">
                    <span class="accounts-thirty-five">最近30天趋势：</span>
                    @component('component/checkbox',['checkbox'=>['账户充值','消费交易','退款'],'name'=>['cb-recharge-0','cb-trad-1','cb-refund-2']])
                    @endcomponent
                </div>
                <div class="accounts-thirty-six">
                    {{--折线图--}}
                        <canvas id="echart"></canvas>
                    </div>
                </div>
            </div>
            {{--右侧 end--}}
            <div style="clear: both"></div>
        </div>
        {{--收入支出 end--}}
    </div>
@endsection
<script>
    const $chartInfo = {!! $chartInfo !!}
</script>
<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/finance/accounts.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/chart.js/Chart.bundle.min.js')}}"></script>
@endsection

@section("pages-js")

@endsection





