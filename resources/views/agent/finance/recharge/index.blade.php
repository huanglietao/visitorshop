<!DOCTYPE html>
<link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">
@extends('layout.iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 财务统计 / 账户充值' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" class="accounts-one">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p class="accounts-two">资金账户充值分为，线下入账和即时到账二种方式</p>--}}
            {{--<p class="accounts-two">线下入账是提交的线下充值入账申请，需要财务人员后台审核才能入账；即时到账是通过第三方支付渠道进行在线付款，能实时到账。</p>--}}
            <p class="accounts-two">即时到账是通过第三方支付渠道进行在线付款，能实时到账。</p>
            <p class="accounts-two">即时到账功能有可能会受第三方支付公司网络延迟影响入账进度，请间隔时间刷新账户余额。</p>
            <p class="accounts-two">紧急情况联系：13610500434</p>
        @endcomponent
        <!--  提示组件 end -->
        <div class="recharge-one">
            {{--导航栏组件 start--}}
            {{--@component('component.navOperateTab',['navlist'=>['线下入账','即时到账'],'extendPadding'=>'0 27'])--}}

            {{--@endcomponent--}}
            {{--导航栏组件 end--}}

            {{--导航栏内容 start--}}
            {{--<div class="recharge-bottom" >--}}
                {{--线下入账 start--}}
                {{--<div name="recharge-content" class="offline recharge-goodsDetail recharge-goodsShow recharge-two">--}}
                    {{--顶部tips start--}}
                    {{--@component('component/recharge_tips')--}}
                        {{--@slot('tips_one')--}}
                            {{--1、请先按下列充值方式进行对应金额充值（完成转帐动作后，请务必保留转帐凭证，手机转帐可截图）。--}}
                        {{--@endslot--}}
                        {{--@slot('tips_two')--}}
                            {{--2、线下入账方式充值，需要财务人员线下审核查账，请务必填写好转帐凭证号及上传转帐凭证，加快入账进度！--}}
                        {{--@endslot--}}
                        {{--@slot('tips_three')--}}
                            {{--3、紧急情况联系：13800138000--}}
                        {{--@endslot--}}
                    {{--@endcomponent--}}
                    {{--顶部tips end--}}
                    {{--<div class="recharge-six">--}}
                        {{--<div class="recharge-seven">--}}
                            {{--充值金额 start--}}
                            {{--<div class="recharge-eight">--}}
                                {{--<div class="recharge-nine">--}}
                                    {{--<span class="recharge-ten">*</span>充值金额：--}}
                                {{--</div>--}}
                                {{--<div class="recharge-eleven">--}}
                                    {{--@if(!empty($rule))--}}
                                        {{--<select style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  id="rule_ids">--}}
                                            {{--<option value="" data-value="">请选择</option>--}}
                                            {{--@foreach($rule as $key=>$val)--}}
                                                {{--<option value="{{$val['rec_rule_id']}}" data-value="{{$val['recharge_fee']}}">{{$val['rec_rule_name']}}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--@else--}}
                                        {{--<input class="form-control" style="width: 100%;margin-bottom: 5px;" name="account" id="accounts" placeholder="请输入金额" />--}}
                                    {{--@endif--}}
                                    {{--<select class="form-control recharge-twelve">--}}
                                        {{--<option>无折扣：充值500元，到账500，无优惠</option>--}}
                                        {{--<option>95折：充值5000元，到账5100元，赠送100元</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--充值金额 end--}}

                            {{--充值方式 start--}}
                            {{--<div class="recharge-thirteen">--}}
                                {{--<div class="recharge-fourteen">--}}
                                    {{--<span class="recharge-ten">*</span>充值方式：--}}
                                {{--</div>--}}
                                {{--<div class="recharge-fifteen">--}}
                                    {{--<div class="recharge-sixteen" data-value="3">--}}
                                        {{--<div class="recharge-fifty-four">--}}
                                            {{--<div class="recharge-fifty-five">--}}
                                                {{--<img class="recharge-eighteen" src="/images/21.jpg" />--}}
                                            {{--</div>--}}
                                            {{--<div class="recharge-fifty-six">银行卡支付</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="recharge-twenty on xianxia" data-value="1" style="border:2px solid rgb(63, 81, 181);display: flex;justify-content: center;align-items: center">--}}
                                        {{--<div class="recharge-fifty-four">--}}
                                            {{--<div class="recharge-fifty-five">--}}
                                                {{--<img class="recharge-eighteen" src="/images/22.jpg" />--}}
                                            {{--</div>--}}
                                            {{--<div class="recharge-fifty-six">支付宝支付</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="recharge-twenty-two" data-value="2" style="display: flex;justify-content: center;align-items: center">--}}
                                        {{--<div class="recharge-fifty-four">--}}
                                            {{--<div class="recharge-fifty-five">--}}
                                                {{--<img class="recharge-eighteen" src="/images/23.jpg" />--}}
                                            {{--</div>--}}
                                            {{--<div class="recharge-fifty-six">微信支付</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="recharge-twenty-three">--}}
                                {{--<div class="recharge-twenty-four"></div>--}}
                                {{--<div class="recharge-twenty-five">--}}
                                    {{--<div class="recharge-fourty-nine">--}}
                                        {{--<span class="recharge-twenty-six">支付宝认证实名：赵肸</span>--}}
                                        {{--<span class="recharge-twenty-seven">支付宝账号：358972022@qq.com</span>--}}
                                        {{--<i></i>--}}
                                        {{--<i></i>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="recharge-twenty-eight" style="display: none">--}}
                                {{--<div class="recharge-twenty-four"></div>--}}
                                {{--<div class="recharge-twenty-five">--}}
                                    {{--<div class="recharge-fourty-nine">--}}
                                        {{--<span class="recharge-twenty-six">开户银行名称：中国银行广州工业园支行</span>--}}
                                        {{--<span class="recharge-twenty-seven">银行账号：6217 8570 0003 1327 578</span>--}}
                                        {{--<i></i>--}}
                                        {{--<i></i>--}}
                                        {{--<span class="recharge-twenty-seven">银行户名：天津长荣云印刷科技有限公司</span>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="recharge-twenty-eight-same" style="display: none">--}}
                                {{--<div class="recharge-twenty-four"></div>--}}
                                {{--<div class="recharge-twenty-five">--}}
                                    {{--<div class="recharge-fourty-nine">--}}
                                        {{--<span class="recharge-twenty-six">微信认证实名：赵肸</span>--}}
                                        {{--<span class="recharge-twenty-seven">微信账号：18665039069</span>--}}
                                        {{--<i></i>--}}
                                        {{--<i></i>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--充值方式 end--}}

                            {{--转账凭证号 start--}}
                            {{--<div class="recharge-thirty-five">--}}
                                {{--<div class="recharge-nine">--}}
                                    {{--转账凭证号：--}}
                                {{--</div>--}}
                                {{--<div class="recharge-thirty-six">--}}
                                    {{--<input id="trade_no" class="form-control recharge-twelve" placeholder="一般为银行给出的转帐单号或交易流水号"/>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--转账凭证号 end--}}

                            {{--转账凭证 start--}}
                            {{--<div class="recharge-thirty-seven">--}}
                                {{--<div class="recharge-thirty-eight">--}}
                                    {{--转账凭证：--}}
                                {{--</div>--}}
                                {{--<div class="recharge-thirty-nine">--}}
                                    {{--@component('component/image_upload',['name'=>'images','direction'=>0,'browse_btn'=>'test','content_class'=>'upload_list','img_format'=>'jpg','num'=>2  ,'img_size'=>'200kb','uploader'=>'uploader','value'=>""])--}}
                                    {{--@endcomponent--}}
                                    {{--<div class="recharge-fourty-four">最多上传2张，可以是手机截图。仅允许JPG格式，大小200K以内。</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--转账凭证 end--}}

                            {{--备注 start--}}
                            {{--<div class="recharge-sixty">--}}
                                {{--<div class="recharge-fifty-seven">--}}
                                    {{--充值备注：--}}
                                {{--</div>--}}
                                {{--<div class="recharge-thirty-six" style="height: auto">--}}
                                    {{--<textarea style="width: 100%;resize: none;height: 140px;" class="notes"></textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--备注 end--}}

                            {{--按钮 start--}}
                            {{--<div class="recharge-twenty-three-flag">--}}
                                {{--<div class="recharge-twenty-four"></div>--}}
                                {{--<div class="recharge-twenty-five" style="margin-top: 50px">--}}
                                    {{--<button class="btn btn-3F51B5 btn-primary up pay" style="cursor:pointer;">充值入账申请</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--按钮 end--}}
                        {{--</div>--}}

                        {{--右侧图片 start--}}
                        {{--<div class="recharge-fourty-six">--}}
                            {{--<div class="recharge-fourty-seven">--}}
                                {{--<img src="/images/20.jpg" class="recharge-eighteen">--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--右侧图片 end--}}
                        {{--<div class="recharge-fourty-eight"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--线下入账 end--}}

                {{--即时到账 start--}}
                <div name="recharge-content"  class="immediate recharge-goodsDetail recharge-two" style="display: block;padding-top: 10px;">
                    {{--顶部tips start--}}
                    {{--@component('component/recharge_tips')--}}
                        {{--@slot('tips_one')--}}
                            {{--1、即时到账功能有可能会受第三方支付公司网络延迟影响入账进度，请间隔时间刷新账户余额。--}}
                        {{--@endslot--}}
                        {{--@slot('tips_two')--}}
                            {{--2、紧急情况联系：13800138000--}}
                        {{--@endslot--}}
                        {{--@slot('tips_three')--}}

                        {{--@endslot--}}
                    {{--@endcomponent--}}
                    {{--顶部tips end--}}
                    <div class="recharge-six">
                        {{--<form method="post" action="/finance/recharge/pay" onsubmit="return false">--}}

                        {{--</form>--}}
                        <div class="recharge-seven">
                            {{--充值金额 start--}}
                            <div class="recharge-eight">
                                <div class="recharge-nine">
                                    <span class="recharge-ten">*</span>充值金额：
                                </div>
                                <div class="recharge-eleven">
                                    @if(!empty($rule))
                                        <select style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  id="rule_id">
                                            <option value="" data-value="">请选择</option>
                                            @foreach($rule as $key=>$val)
                                                <option value="{{$val['rec_rule_id']}}" data-value="{{$val['recharge_fee']}}">{{$val['rec_rule_name']}}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="account" id="account" placeholder="请输入金额" />
                                    @endif
                                </div>
                            </div>
                            {{--充值金额 end--}}

                            {{--充值方式 start--}}
                            <div class="recharge-fifty">
                                <div class="recharge-fourteen">
                                    <span class="recharge-ten">*</span>充值方式：
                                </div>
                                <div class="recharge-fifty-one">
                                    <div class="recharge-fifty-two on jishi" data-value="1" style="border:2px solid rgb(63, 81, 181);">
                                        <div class="recharge-fifty-four">
                                            <div class="recharge-fifty-five">
                                                <img class="recharge-eighteen" src="/images/22.jpg" />
                                            </div>
                                            <div class="recharge-fifty-six">支付宝支付</div>
                                        </div>
                                    </div>
                                    <div class="recharge-fifty-three" data-value="2">
                                        <div class="recharge-fifty-four">
                                            <div class="recharge-fifty-five">
                                                <img class="recharge-eighteen" src="/images/23.jpg" />
                                            </div>
                                            <div class="recharge-fifty-six">微信支付</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--充值方式 end--}}

                            {{--备注 start--}}
                            <div class="recharge-fifty-eight">
                                <div class="recharge-fifty-seven">
                                    充值备注：
                                </div>
                                <div class="recharge-fifty-nine">
                                    <textarea class="note" style="width: 100%;resize: none;height: 140px;"></textarea>
                                </div>
                            </div>
                            {{--备注 end--}}

                            {{--按钮 start--}}
                            <div class="recharge-twenty-three-flag">
                                <div class="recharge-twenty-four"></div>
                                <div class="recharge-twenty-five" style="margin-top: 40px">
                                    {{--<div class="recharge-fourty-five">立即充值</div>--}}
                                    <button class="btn btn-3F51B5 btn-primary to_pay" style="cursor:pointer;">立即充值</button>
                                </div>
                            </div>
                            {{--按钮 end--}}

                        </div>
                        <div class="recharge-fourty-eight"></div>
                    </div>
                </div>
                {{--即时到账 end--}}
            </div>
            {{--导航栏内容 end--}}

        </div>
    </div>
@endsection

<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/finance/recharge.js') }}"></script>
@endsection

@section("pages-js")

@endsection





