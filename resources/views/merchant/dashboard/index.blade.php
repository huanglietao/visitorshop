@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台' ])
    @endcomponent
    <!-- 面包屑 end  -->
<div id="main">
    <div style="width:100%;">
        <div style="display: flex;justify-content: space-between;">
            <div style="width:62%;">
                <div style="font-weight: 400;font-size: 18px;">今天</div>
                <div style="font-size: 12px;">
                    {{$greet['week']}}，尊敬的
                    <span style="font-weight: 400;font-size: 18px;color: rgb(121, 119, 119);">{{$userInfo['oms_adm_username']}}</span>
                    {{$greet['greet']}}
                    <span style="margin-left: 10px;">欢迎您回来控制台。对我们有话说</span>
                    <a href="#" style="color: rgb(63, 81, 181);">请点击</a>
                </div>
            </div>
            <div style="width:38%;display: flex;justify-content: space-around;padding-top: 5px">
                {{--<div style="margin-left: auto">
                    <div style="width: 50px;height: 50px;border: 1px solid #9b9b9b;border-radius: 50%;
                                background: url(../images/cube.png) no-repeat center center;display: inline-block;float: left;background-size: 50%">
                    </div>
                    <div style="display: inline-block;float: left;text-align: center">
                        <span style="font-weight: 700;font-size: 18px;color: rgb(121, 119, 119);">0</span>
                        <div style="font-weight: 700;font-size: 18px;color: rgb(121, 119, 119);">待付款</div>
                    </div>
                </div>
                <div style="margin-left: auto">
                    <div style="width: 50px;height: 50px;border: 1px solid #9b9b9b;border-radius: 50%;
                    display: inline-block;float: left; background: url(../images/order.png) no-repeat center center;background-size: 50%">
                    </div>
                    <div style="display: inline-block;float: left;text-align: center">
                        <span style="font-weight: 700;font-size: 18px;color: rgb(121, 119, 119);">0</span>
                        <div style="font-weight: 700;font-size: 18px;color: rgb(121, 119, 119);">待支付</div>
                    </div>
                </div>
                <div style="margin-left: auto">
                    <div style="width: 50px;height: 50px;border: 1px solid #9b9b9b;border-radius: 50%;
                                background: url(../images/wait.png) no-repeat center 7px;display: inline-block;float: left;background-size: 50%">
                    </div>
                    <div style="display: inline-block;float: left;text-align: center">
                        <span style="font-weight: 700;font-size: 18px;color: rgb(121, 119, 119);">0</span>
                        <div style="font-weight: 700;font-size: 18px;color: rgb(121, 119, 119);">待办工单</div>
                    </div>
                </div>--}}


            </div>
        </div>
    </div>
    <div style="display: flex;justify-content: space-between;padding-top: 10px;">
        <div style="width:73.5%;position: relative">
            <div style="height: 80px;display: flex;justify-content: space-around;">
                <a style="cursor:pointer;width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;">
                    <img style="width:40px;height: 40px;margin-right: 5%" src="../images/wallet.png" />
                    <div style="display: inline-block;">
                        <span style="font-weight: 400;font-size: 14px;color: rgb(16, 16, 16);">￥<span class="today_amount">0.00</span> </span>
                        <div style="font-weight: 400;font-size: 12px;color: rgb(16, 16, 16);">今日销售总额</div>
                    </div>
                </a>
                <a href="/#/order/list" target="_blank" style="width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;">
                    <img style="width:40px;height: 40px;margin-right: 5%" src="../images/orders.png" />
                    <div style="display: inline-block;">
                        <span class="today_order_count" style="font-weight: 400;font-size: 14px;color: rgb(16, 16, 16);">0 </span>
                        <div style="font-weight: 400;font-size: 12px;color: rgb(16, 16, 16);">今日总订单数</div>
                    </div>
                </a>
                <a style="cursor: pointer; width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;">
                    <img style="width:40px;height: 40px;margin-right: 5%" src="../images/note.png" />
                    <div style="display: inline-block;">
                        <span class="today_order_comment" style="font-weight: 400;font-size: 14px;color: rgb(16, 16, 16);">0 </span>
                        <div style="font-weight: 400;font-size: 12px;color: rgb(16, 16, 16);">今日评论数</div>
                    </div>
                </a>
                <a href="/#/agent/info" target="_blank" style="cursor: pointer; width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;">
                    <img style="width:40px;height: 40px;margin-right: 5%" src="../images/pos.png" />
                    <div style="display: inline-block;">
                        <span style="font-weight: 400;font-size: 14px;color: rgb(16, 16, 16);" class="merchant_count">0 </span>
                        <div style="font-weight: 400;font-size: 12px;color: rgb(16, 16, 16);">{{--今日--}}总商家数</div>
                    </div>
                </a>
                <a href="/#/user/user" target="_blank" style="cursor: pointer; width: 20%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;">
                    <img style="width:40px;height: 40px;margin-right: 5%" src="../images/group.png" />
                    <div style="display: inline-block;">
                        <span class="user_count" style="font-weight: 400;font-size: 14px;color: rgb(16, 16, 16);">0 </span>
                        <div style="font-weight: 400;font-size: 12px;color: rgb(16, 16, 16);">总会员数</div>
                    </div>
                </a>
            </div>

            <div style="border: 1px solid rgb(170, 170, 170);margin-top: 20px">
                <div style="width:100%;padding: 20px 0;display: flex;justify-content: center">
                    <div style="width:95%;height:30px;border-bottom:1px solid rgb(223, 223, 223);display: flex;justify-content: space-between">
                        <span style="font-size: 14px">销售额统计</span>
                        <div>
                            <div style="width: 14px;height: 14px;background-color: rgb(183, 181, 181);display: inline-block;vertical-align: text-bottom;margin-right: 10px"></div><span style="margin-right: 40px">去年销售额</span>
                            <div style="width: 14px;height: 14px;background-color: rgb(63, 81, 181);display: inline-block;vertical-align: text-bottom;margin-right: 10px"></div><span>今年销售额</span>
                        </div>
                        <span>单位：万元</span>
                    </div>
                </div>
                <div  style=";width:90%;margin: 20px auto">
                    <canvas id="zchart" width="1000" height="250"></canvas>
                </div>
            </div>

            <div style="display: flex;justify-content: space-between;padding-top: 20px;">
                <div style="width: 32%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;padding: 20px 20px;position: relative">
                    <div style="display: flex;flex-direction: column;width: 100%;">
                        <div style="height:30px;border-bottom:1px solid rgb(223, 223, 223);display: flex;justify-content: space-between">
                            <span style="font-weight: 700;font-size: 14px">账户余额</span>
                            <span>单位：元</span>
                        </div>
                        <div style="display: flex;justify-content: space-between;margin-bottom: 20px">
                            <div style="width: 50%;padding-top: 10px">
                                <p style="padding-left:10%">可用余额</p>
                                <p style="padding-left:20%">￥ 0.00</p>
                            </div>
                            <div style="width: 50%;padding-top: 10px">
                                <p style="padding-left:10%">冻结余额</p>
                                <p style="padding-left:20%">￥ 0.00</p>
                            </div>
                        </div>
                        {{--<div style="text-align: center;position: absolute;bottom:15px;left: 0;right: 0;margin: 0 auto">
                            <button class="btn  btn-primary btn-sm btn-3F51B5 btn_small">&nbsp;&nbsp;充值&nbsp;&nbsp;></button>
                            &nbsp;&nbsp;
                            <button class="btn  btn-primary btn-sm btn-3F51B5 btn_small">资金管理</button>
                        </div>--}}
                    </div>
                </div>
                <div style="width: 32%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;padding: 20px 20px;">
                    <div style="display: flex;flex-direction: column;width: 100%">
                        <div style="height:30px;border-bottom:1px solid rgb(223, 223, 223);display: flex;justify-content: space-between">
                            <span style="font-weight: 700;font-size: 14px">商品概览</span>
                            <span>单位：件</span>
                        </div>
                        <div style="display: flex;justify-content: space-between;padding-top: 20px">
                            <div style="width: 47%;background-color: rgb(240, 242, 245);text-align: center;padding-top: 10px">
                                <p style="font-weight: 700;">标准商品</p>
                                <p style="font-size: 12px">
                                    <span style="padding-right:2%">
                                        印品:
                                        <span style="color:#FF6A01;padding-left: 1px" class="standard_print">0</span>
                                    </span>
                                    <span style="padding-right:2%">
                                        实物:
                                        <span style="color:#FF6A01;padding-left: 1px" class="standard_entity">0</span>
                                    </span>
                                </p>
                            </div>
                            <div style="width: 47%;background-color: rgb(240, 242, 245);text-align: center;padding-top: 10px">
                                <p style="font-weight: 700;">自定义商品</p>
                                <p style="font-size: 12px">
                                    <span style="padding-right:10px">
                                        印品:
                                        <span style="color:#FF6A01;padding-left: 1px" class="custom_print">0</span>
                                    </span>
                                    <span style="padding-right:10px">
                                        实物:
                                        <span style="color:#FF6A01;padding-left: 1px" class="custom_entity">0</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width: 32%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;padding: 20px 20px">
                    <div style="display: flex;flex-direction: column;width: 100%">
                        <div style="height:30px;border-bottom:1px solid rgb(223, 223, 223);display: flex;justify-content: space-between">
                            <span style="font-weight: 700;font-size: 14px">存储概览</span>
                            <span>单位：G</span>
                        </div>
                        <div style="display: flex;justify-content: space-around;padding-top: 10px;text-align: center">
                            <div style="display: flex;flex-direction: column;justify-content: center;align-items: center">
                                <img style="width:32px;height: 32px;float: left;margin-right: 5%" src="../images/file.png" />
                                <p style="font-size: 12px;padding-top: 15px">
                                    <span style="padding-right:10px">
                                        OSS:
                                        <span style="padding-left: 1px">1.5</span>
                                    </span>
                                </p>
                            </div>
                            <div style="display: flex;flex-direction: column;justify-content: center;align-items: center">
                                <img style="width:32px;height: 32px;float: left;margin-right: 5%" src="../images/data.png" />
                                <p style="font-size: 12px;padding-top: 15px">
                                    <span style="padding-right:10px">
                                        数据库:
                                        <span style="padding-left: 1px">0.5</span>
                                    </span>
                                </p>
                            </div>
                            <div style="display: flex;flex-direction: column;justify-content: center;align-items: center">
                                <img style="width:32px;height: 32px;float: left;margin-right: 5%" src="../images/picture.png" />
                                <p style="font-size: 12px;padding-top: 15px">
                                    <span style="padding-right:10px">
                                        文件:
                                        <span style="padding-left: 1px">1.8</span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="height: 100px;display: flex;justify-content: space-around;padding-top: 20px;">
                <a href="/#/order/list?order_status={{ORDER_STATUS_WAIT_CONFIRM}}" target="_blank" style="width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;cursor: pointer">
                    <span style="font-weight: 400;font-size: 14px;color: rgb(121, 119, 119)">未确认订单 (<span class="wait_confirm_count">0</span> )</span>
                </a>
                <a href="/#/order/list?order_status={{ORDER_STATUS_WAIT_PAY}}" target="_blank" style="width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;cursor: pointer;">
                    <span style="font-weight: 400;font-size: 14px;color: rgb(121, 119, 119)">待支付订单(<span class="wait_pay_count">0</span>)</span>
                </a>
                <a href="/#/order/list?order_status={{ORDER_STATUS_WAIT_PRODUCE}}" target="_blank" style="width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;cursor: pointer">
                    <span style="font-weight: 400;font-size: 14px;color: rgb(121, 119, 119)">待生产订单(<span class="order_wait_produce">0</span>)</span>
                </a>
                <a href="/#/order/list?order_status={{ORDER_STATUS_WAIT_DELIVERY}}" target="_blank" style="width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;cursor: pointer">
                    <span style="font-weight: 400;font-size: 14px;color: rgb(121, 119, 119)">待发货订单(<span class="wait_delivery_count">0</span>)</span>
                </a>
                <a href="/#/order/list?order_status={{ORDER_STATUS_WAIT_RECEIVE}}" target="_blank" style="width: 20%;margin-right: 2%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;cursor: pointer">
                    <span style="font-weight: 400;font-size: 14px;color: rgb(121, 119, 119)">待确认收货订单(<span class="wait_receive_count">0</span>)</span>
                </a>
                <a href="/#/order/list?order_evaluate_status={{ORDER_UNEVALUATE}}" target="_blank" style="width: 20%;border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;cursor: pointer">
                    <span style="font-weight: 400;font-size: 14px;color: rgb(121, 119, 119)">待评价订单(<span class="wait_evaluate_count">0</span>)</span>
                </a>
            </div>

            <div style="display: flex;flex-direction: column;margin-top: 20px;border: 1px solid rgb(170, 170, 170);padding:15px 10px 0 10px">
                <div style="border-bottom: 1px solid rgb(170,170,170);padding-bottom: 5px">
                    <span style="width:50%;float: left;font-weight: 700;font-size: 14px;">系统导航</span>
                    <span style="width:50%;float: left;font-weight: 700;font-size: 14px;">客户服务</span>
                </div>
                <div style="display: flex;justify-content: space-around;padding: 5px 0">
                    <div style="width:50%;border-right: 1px solid rgb(170,170,170);padding:10px 30px 0 30px;font-size: 12px">
                        <p style="margin-bottom: 0">OMS运营管理：<a href="" class="merchant_url" target="_blank"></a></p>
                        <p style="margin-bottom: 0">DMS运营管理：<a href="" class="agent_url" target="_blank"></a></p>
                        <p style="margin-bottom: 0">MES运营管理：<a href="" class="scm_url" target="_blank"></a></p>
                    </div>
                    <div style="width:50%;padding:10px 30px 0 30px;font-size: 12px">
                        <div style="display: inline-block;float: left;padding-right: 20px">
                            <p style="margin-bottom: 0">技术经理电话：<a href="">{{config('common.technology_mobile.tech_mobile')}}</a></p>
                            <p style="margin-bottom: 0">产品经理电话：<a href="">{{config('common.technology_mobile.prod_mobile')}}</a></p>
                        </div>
                        {{--<div style="display: inline-block;float: left;text-align: center;">
                            <div style="width: 75px;height: 75px;display: table-cell;display: -webkit-flex;justify-content: center;align-items: center;
                                    background: linear-gradient(rgb(170,170,170), rgb(170,170,170)) left top,
                                                linear-gradient(rgb(170,170,170), rgb(170,170,170)) left top,
                                                linear-gradient(rgb(170,170,170), rgb(170,170,170)) right top,
                                                linear-gradient(rgb(170,170,170), rgb(170,170,170)) right top,
                                                linear-gradient(rgb(170,170,170), rgb(170,170,170)) right bottom,
                                                linear-gradient(rgb(170,170,170), rgb(170,170,170)) right bottom,
                                                linear-gradient(rgb(170,170,170), rgb(170,170,170)) left bottom,
                                                linear-gradient(rgb(170,170,170), rgb(170,170,170)) left bottom;
                                                background-repeat: no-repeat;
                                                background-size: 2px 20px, 20px 2px;">
                                <img style="width:60px;height: 60px;margin-right: 5%" src="../images/ma.png" />
                            </div>
                            <p style="padding-top:10px;margin-bottom: 0">VIP微信群</p>
                        </div>--}}

                    </div>
                </div>
            </div>
        </div>
        <div style="width:25%;position: relative">
            <div style="border: 1px solid rgb(170, 170, 170);border-radius: 5px;display: table-cell;
                            display: -webkit-flex;justify-content: center;align-items: center;padding: 20px 20px;position: relative">
                <div style="display: flex;flex-direction: column;width: 100%;">
                    <div style="height:30px;border-bottom:1px solid rgb(223, 223, 223);display: flex;justify-content: space-between">
                        <p><i style="color:#FF6A01;padding-right: 5px" class="fa fa-volume-down fa-lg" aria-hidden="true"></i>&nbsp;平台公告</p>
                        <a href="/news?art_sign={{GOODS_MAIN_CATEGORY_ANNOUNCE}}" class="see-more">查看更多</a>
                    </div>
                <div class="article-main">

                </div>
                </div>
            </div>

            <div style="padding-top:20px">
                {{--@component('component.navOperateTab',['navlist'=>['1'=>'商品','2'=>'软件','3'=>'资源']])
                @endcomponent--}}
                @component('component.navOperateTab',['navlist'=>['1'=>'商品']])
                @endcomponent
                <div  class="product-main" style="border: 1px solid rgb(170,170,170);padding: 0 20px 20px 20px">

                </div>
            </div>

            {{--<div style="border: 1px solid rgb(170,170,170);padding: 20px 20px;position: absolute;bottom: 0">
                <div>
                    <p style="font-size: 16px;font-weight: 700;margin-bottom: 0">云大使 - 推荐奖励</p>
                    <p style="font-size: 12px;color:rgb(170,170,170);margin-bottom: 0;padding-top: 4px">奖励最高 66.6%  （100云气=1元）</p>
                </div>
                <div style="display: flex;justify-content: space-around ;padding-top: 20px;text-align: center">
                    <div style="width:49%;display: flex;flex-direction: column;justify-content: center;align-items: center;position: relative">
                        <img style="width:52px;height: 52px;float: left;position: absolute;top:0;left: 0;right: 0;margin: 0 auto" src="../images/test2.png" />
                        <div style="font-size: 12px;padding-top: 60px;">
                            <p style="margin-bottom: 0">0 成本高回报</p>
                            <p style="color: #888;padding-top: 8px;margin-bottom: 0;text-align: left">推荐好友即得奖励，享现金、代金券、专属内测等权益</p>
                        </div>
                    </div>
                    <div style="width:49%;display: flex;flex-direction: column;justify-content: center;align-items: center;position: relative">
                        <img style="width:52px;height: 52px;float: left;position: absolute;top:0;left: 0;right: 0;margin: 0 auto" src="../images/test1.png" />
                        <div style="font-size: 12px;padding-top: 60px;">
                            <p style="margin-bottom: 0">与 50 万布道者同行</p>
                            <p style="color: #888;padding-top: 8px;margin-bottom: 0">参加云计算推广获专属身份，帮助千万中小企业上云</p>
                        </div>
                    </div>
                </div>
                <div style="height: 30px;margin-top:20px;border:1px solid deepskyblue;text-align: center;line-height: 30px">
                    <span style="color:deepskyblue;letter-spacing: 2px;">立即加入</span>
                </div>
            </div>--}}
        </div>
    </div>
</div>

@endsection

@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/merchant/index.js') }}"></script>
    <script src="{{ URL::asset('assets/chart.js/Chart.bundle.min.js')}}"></script>
@endsection
