{{--引入CSS样式--}}
<link rel="stylesheet" href="{{URL::asset('css/agent/index.css')}}">
@extends('layout.iframe')

@section("main-content")

<!-- 面包屑 start  -->
@component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台' ])
@endcomponent
<!-- 面包屑 end  -->

<div  id="main">
    <!-- 卡片 start  -->
    <div style="width:100%;display: flex;justify-content: space-between">
        @component('component/card',['class'=>'left_card'])
            <div class="l-card" style="margin-bottom: 30px;">
                <div style="padding-bottom: 3%;display: flex;justify-content: flex-start">
                    <div style="margin-right: 7%">
                        <img style="width:60px;height: 60px;border-radius: 100%;border: 1px solid #bbb;" src="@if(!empty($account_info['dms_adm_avatar'])){{$account_info['dms_adm_avatar']}} @else /images/defaultHead.png @endif" onerror="this.src='/images/defaultHead.png'"/>
                    </div>
                    <div style=" margin-top:2%;">
                        <div style="color: rgb(16, 16, 16);font-size: 14px;margin-bottom: 10px;letter-spacing: 1px"><span>{{$account_info['info']['agent_name']}}</span></div>
                        <div style="color: rgb(121, 119, 119)"><span>{{$account_info['info']['agent_desc']}}</span></div>
                    </div>
                </div>

                <div style="padding-bottom: 3%">
                    <span style="color: rgba(121, 119, 119, 1);margin-right: 6%">登录账号：</span>
                    <span style="color: rgb(16, 16, 16)">{{$account_info['dms_adm_username']}}</span>
                </div>
                <div style="padding-bottom: 3%">
                    <span style="color: rgba(121, 119, 119, 1);margin-right: 6%">管理权限：</span>
                    <span style="color: rgb(16, 16, 16)">{{$account_info['dms_adm_group_name']}}</span>
                </div>
                <div style="padding-bottom: 3%">
                    <span style="color: rgba(121, 119, 119, 1);margin-right: 6%">店铺等级：</span>
                    <span style="color: rgb(16, 16, 16)">{{$account_info['level_name']}}</span>
                    <div style="display: inline-block;color: rgb(121, 119, 119);margin:0 10px;font-size: 16px">
                        @component('component/help_tips',['title'=>'如何提升等级'])@endcomponent
                    </div>
                    <a href="" style="color: rgb(63, 81, 181)">如何提升等级</a>
                </div>
                <div style="padding-bottom: 3%">
                    <span style="color: rgba(121, 119, 119, 1);margin-right: 6%">最后登录：</span>
                    <span style="color: rgb(16, 16, 16)">{{date('Y-m-d H:i:s',$account_info['dms_adm_logintime'])}}</span>
                </div>
            </div>
            <div style="text-align: center;position: absolute;bottom:15px;left: 0;right: 0;margin: 0 auto;">
                <button class="btn  btn-primary btn-sm btn-3F51B5" >
                    <a href="/auth/admin" style="color: white">账号管理</a>
                </button>
            </div>
        @endcomponent

        @component('component/card',['class'=>'center_card'])
            <div style="padding: 5% 6%;">
                <p>
                    <i style="color:#5677fc" class="fa fa-dot-circle-o" aria-hidden="true"></i>&nbsp;店铺余额&nbsp;
                    <i style="color:#797777;font-size:16px;cursor: pointer;" class="fa fa-eye dashboard-money" aria-hidden="true"></i>
                </p>
                <div>
                    <span style="color: rgba(121, 119, 119, 1);">账户可用余额:</span>
                </div>
                <div class="money">
                    <span class="money-text-available">￥{{$account_info['info']['agent_balance']}}元</span>
                </div>
                <div style="margin-top: 15px;">
                    <span style="color: rgba(121, 119, 119, 1);">冻结资金:</span>
                </div>
                <div class="money">
                    <span class="money-text-frozen">￥0.00元 </span>
                    <div style="display: inline-block;color: rgb(121, 119, 119);margin:0 10px;font-size: 16px">
                        @component('component/help_tips',['title'=>'冻结账户是指店铺账户中暂时不能使用的部分金额'])@endcomponent
                    </div>
                </div>
            </div>
            <div style="text-align: center;position: absolute;bottom:15px;left: 0;right: 0;margin: 0 auto">
                <button class="btn  btn-primary btn-sm btn-3F51B5 btn_small" style="padding-left: 22px;padding-right: 22px;margin-right: 10px">
                    <a href="/finance/recharge/index" style="color: white;">充值</a>
                </button>

                <button class="btn  btn-primary btn-sm btn-3F51B5 btn_small">
                    <a href="/finance/fund" style="color: white">资金管理</a>
                </button>
            </div>
        @endcomponent

        @component('component/card',['class'=>'right_card'])
        <div style="padding: 4.2% 6%;">
            <p>
                <i style="color:rgb(63, 81, 181)" class="fa fa-volume-down" aria-hidden="true"></i>&nbsp;平台公告
                <a href="/news" class="see-more">查看更多</a>
            </p>
            @foreach($account_info['news'] as $k=>$v)
                <div>
                    <div class="notice">
                        <a class="notice-title">{{$v['art_title']}}</a>
                        @if($loop->first)
                            <div class="new">NEW</div>
                        @endif
                        <div class="time">{{date('m-d',$v['created_at'])}}</div>
                    </div>
                </div>
            @endforeach
        </div>
        @endcomponent
    </div>
    <!-- 卡片 end  -->

    {{--广告图 start--}}
    <div class="poster">
        <img src="../../../../images/qixi.jpg" style="width: 100%;height:250px;"/>
    </div>
    {{--广告图 end--}}

    {{--可选标题 start--}}
    <div style="margin:20px 0">
        @component('component.navOperateTab',['navlist'=>['1'=>'昨日交易']])

        @endcomponent
    </div>

    {{--可选标题 end--}}

    {{--数据展示 start--}}
    <div id="business">
        <div class="detail">
            <div style="display: inline-block;">
                <span>昨日成交金额（元）</span>
                <div>￥{{$sales_info['total_amount']}}</div>
            </div>
            <div style="display: inline-block;">
                <span>昨日成交订单数（笔）</span>
                <div>{{$sales_info['total_order']}}</div>
            </div>

            <div style="display: inline-block;">
                <span>昨日作品数（个）</span>
                <div>{{$sales_info['total_work']}}</div>
            </div>
        </div>

        <div  style="width: 85%;margin: 0 auto;padding-bottom: 25px">
            <div class="check choices">
                <span style="color: rgb(121, 119, 119);">昨日24小时趋势：</span>
                @component('component/checkbox',['checkbox'=>['交易金额','成交订单数','作品数'],'name'=>['cb-money-0','cb-order-1','cb-work-2'],'checked'=>[0,1,2]])
                @endcomponent
            </div>
        </div>
        {{--折线图--}}
        <div  style=";width:90%;margin: 0 auto">
            <canvas id="schart" width="1000" height="400"></canvas>
        </div>
    </div>

    <div id="expenses" class="chart" style="display: none;">
        <div class="detail">
            <div style="display: inline-block;float: left">
                <span>成交金额（元）</span>
                <div>￥20000.00</div>
            </div>
            <div style="display: inline-block">
                <span>成交订单数（笔）</span>
                <div>1000</div>
            </div>
            <div style="display: inline-block;float: right">
                <span>总作品数（个）</span>
                <div>800</div>
            </div>
        </div>

        <div  style="width: 85%;margin: 0px auto">
            <div class="check">
                <span style="color: rgb(121, 119, 119);">最近30天趋势：</span>
                <input type="checkbox" id="交易金额1" class="btn-checkbox">
                <label for="交易金额1"></label>
                <span class="save">交易金额</span>

                <input type="checkbox" id="成交订单数1" class="btn-checkbox">
                <label for="成交订单数1"></label>
                <span class="save">成交订单数</span>

                <input type="checkbox" id="作品数1" class="btn-checkbox">
                <label for="作品数1"></label>
                <span class="save">作品数</span>
            </div>
        </div>
    </div>

    <div id="works" class="chart" style="display: none;">
        <div class="detail">
            <div style="display: inline-block;float: left">
                <span>成交金额（元）</span>
                <div>￥1000.00</div>
            </div>
            <div style="display: inline-block">
                <span>成交订单数（笔）</span>
                <div>500</div>
            </div>
            <div style="display: inline-block;float: right">
                <span>总作品数（个）</span>
                <div>350</div>
            </div>
        </div>
        <div  style="width: 85%;margin: 0px auto">
            <div class="check choices">
                <span style="color: rgb(121, 119, 119);">最近30天趋势：</span>
                <input type="checkbox" id="money1" class="btn-checkbox">
                <label for="money1"></label>
                <span class="save">交易金额</span>

                <input type="checkbox" id="orders1" class="btn-checkbox">
                <label for="orders1"></label>
                <span class="save">成交订单数</span>

                <input type="checkbox" id="workes1" class="btn-checkbox">
                <label for="workes1"></label>
                <span class="save">作品数</span>
            </div>
        </div>
    </div>
    {{--数据展示 end--}}

</div>

@endsection

<!---  引入当前页需使用的js  -->
@section("pages-js")
@endsection
@section("js-file")
    <script src="{{ URL::asset('js/agent/index.js')}}"></script>
    <script src="{{ URL::asset('assets/chart.js/Chart.bundle.min.js')}}"></script>
@endsection
