@extends('layout.erp_iframe')
@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/goods_crumb',['icon' => 'fa-dashboard', 'title' => '资金管理 / 账户充值' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">
                注意：正常线下冲款时间为周一至周六早8:00到午16:00（会计工作时间正常入账）为方便客户非正常工作时间付款，特建立应急付款线上通道，此线上付款为应急充付款通道，会产生相应通道的服务费用，由付款方自理。
            </p>
            <p style="margin:5px 0 ;padding:0">即时到账功能有可能会受第三方支付公司网络延迟影响入账进度，请间隔时间刷新账户余额。</p>
            <p style="margin:5px 0;padding:0">紧急情况联系：022-26881958。</p>
        @endcomponent
        <!--  提示组件 end -->

        <div class="row" style="width: 100%;display: flex;flex-direction: column;padding-top: 50px;font-size: 12px;color: #797777;padding-left: 30px;">
            <form method="post" action="recharge/pay" onsubmit="return false">
                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;"><span style="color: red;margin-right: 2px">*</span>充值金额:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="account" id="account" placeholder="请输入金额" />
                        <span style="margin-top: 10px;">
                            根据<span class="pay_name">支付宝</span>手续费收取规则，您需支付手续费
                            <span style="color: red;margin-right: 15px;">￥<span id="service_amount">0</span></span>
                            {{--<a class="alipay_poundage" href="https://b.alipay.com/signing/productDetailV2.htm?productId=I1011000290000001000" style="color: blue;" target="_blank">支付宝手续费收取标准</a>--}}
                        </span>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12" style="width: 100%;height: 111px;flex-direction: row;display: flex;margin-top: 20px">
                    <div style="height:100px;margin-right: 20px;text-align: right;line-height: 108px;"><span style="color: red;margin-right: 2px">*</span>充值金额:</div>
                    <div class="pay_content" style="width: 50%;display: flex; align-items: center">
                        <div class="pay_type select_pay" data-value="1" style="width: 175px;height: 75px;border: 1px solid #bbb;display: flex;justify-content: center;align-items: center;cursor: pointer;">
                            <div style="width: 85%;height: 80%;display: flex;justify-content: center;align-items: center;">
                                <div style="width: 30%;"><img style="width: 100%;height: auto" src="/images/22.jpg"></div>
                                <div style="width: 60%;padding-left: 10%;color: #101010">支付宝支付</div>
                            </div>
                        </div>
                        @if($show_cmb == 1)
                            <div class="pay_type" data-value="2" style="width: 175px;height: 75px;border: 1px solid #bbb;display: flex;justify-content: center;align-items: center;margin-left: 5%;">
                                <div style="width: 85%;height: 80%;display: flex;justify-content: center;align-items: center;cursor: pointer">
                                    <div style="width: 30%;">
                                        <img style="width: 100%;height: auto" src="/images/23.jpg">
                                    </div>
                                    <div style="width: 60%;padding-left: 10%;color: #101010">微信支付</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-top: 20px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 130px;">充值备注:</div>
                    <div style="width: 50%;">
                        <textarea style="width: 100%;resize: none;height: 140px;" class="note"></textarea>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12" style="width: 100%;height: 80px;flex-direction: row;display: flex">
                    <div style="width:50px;margin-right: 20px;text-align: right;line-height: 130px;"></div>
                    <div style="width: 50%;margin-top: 50px;"><button type="button" class="btn btn-3F51B5 btn-primary to_pay up" style="cursor:pointer;">确定充值</button></div>
                </div>
            </form>

        </div>

    </div>
    <script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
    <script>
        var aliapy_poundage = {{$aliapy_poundage}};
        var zhjh_poundage = {{$zhjh_poundage}};
        var poundage = aliapy_poundage;
        var pay_type = 1;

        $(".to_pay").click(function () {
            var amount = $("input[name='account']").val();
            if (amount=='')
            {
                layer.msg('请输入充值数目');
                return;
            }else if(isNaN(amount)) {
                layer.msg('请输入正确的充值金额');
                return;
            }else if(!isNaN(amount)&&amount<0.01) {
                layer.msg('充值金额最小为0.01');
                return;
            }

            //获取备注
            var note  = $(".note").val();

            pay_type = 2; //统一使用招行聚合支付

            parent.window.location.href = "/finance/pay?amount="+amount+"&type="+pay_type+"&note="+note;
            return false;
        })

        $('#account').bind('input propertychange', function() {
            getPoundage(poundage)
        });

        //支付方式选择
        $(".pay_type").click(function () {
            pay_type = $(this).attr("data-value")
            $(".pay_content").find(".pay_type").each(function (i,v) {
                $(this).removeClass("select_pay")
            });
            $(this).addClass("select_pay")
            if(pay_type == 1){
                //支付宝
                poundage = aliapy_poundage
                $(".pay_name").html("支付宝")
                $(".alipay_poundage").removeClass("pay_display")
            }else{
                //招行聚合支付
                poundage = zhjh_poundage
                $(".pay_name").html("微信支付")
                $(".alipay_poundage").addClass("pay_display")
            }
            getPoundage(poundage)
        })

        //手续费计算
        function getPoundage(poundage) {
            var amount = parseInt($('#account').val().replace(/\s+/g,""));
            if(!isNaN(amount)){
                var service = amount * poundage
                service = Number(service) <= 0 ? 0 : service
                $('#service_amount').html(service.toFixed(2))
            }else{
                $('#service_amount').html(0)
            }
        }
    </script>
@endsection
<style>
    .select_pay{
        border:2px solid #E83A36 !important;
    }
    .pay_display{
        display:none;
    }
</style>


