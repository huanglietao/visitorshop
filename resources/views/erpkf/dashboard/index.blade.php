@extends('layout.erpkf_iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/goods_crumb',['icon' => 'fa-dashboard', 'title' => '控制台' ])
    @endcomponent
    <!-- 面包屑 end  -->
<div id="main">
    <div style="width:100%;margin-bottom: 20px;">
        <div style="display: flex;justify-content: space-between;">
            <div style="width:100%;">
                <div style="font-size: 12px;color: #797777">
                    <span style="font-size: 18px;font-weight: 400;margin-right: 8px;color: #101010">今天</span>2020-01-06星期一，尊敬的<span style="font-weight: 400;font-size: 14px;color: #101010">&nbsp;软件测试不发印&nbsp;</span>下午好！
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 dashboard-info" style="padding-left:3%;margin-right:5%;height: 254px;border: 1px solid rgb(241, 234, 234);display: flex;flex-direction: column;padding-top: 10px;padding-bottom: 20px;margin-bottom: 10px;margin-top: 10px;">
            <div style="width: 100%;display: flex;">
                <div style="width: 20%;font-size: 12px;text-align:center;">
                    <img src="/images/user_default.jpg" style="width: 90%;margin-top: 15px;margin-bottom: 20px;line-height: 0">
                </div>
                <div style="width: 70%;font-size: 12px;padding-top: 15px;padding-left: 15px;">
                    <div style="color: #101010;font-size: 14px;font-weight: bold;">软件测试不发印</div>
                    <div style="color: #797777;margin-top: 10px">软件测试不发印</div>
                </div>
            </div>
            <div style="width: 100%;display: flex;font-size: 12px;margin-bottom: 20px;">
                <div style="color: #797777;width: 32%;text-align: right;white-space: nowrap;">登录账号:</div>
                <div style="color: #101010;width: 82%;margin-left: 20px">1111119999</div>
            </div>
            <div style="width: 100%;display: flex;font-size: 12px;margin-bottom: 20px;">
                <div style="color: #797777;width: 32%;text-align: right;white-space: nowrap;">登录IP:</div>
                <div style="color: #101010;width: 82%;margin-left: 20px">127.0.0.1</div>
            </div>
            <div style="width: 100%;display: flex;font-size: 12px;margin-bottom: 20px;">
                <div style="color: #797777;width: 32%;text-align: right;">最后登录时间:</div>
                <div style="color: #101010;width: 82%;margin-left: 20px">2019-12-27</div>
            </div>
        </div>
        <div class="col-sm-3  dashboard-info" style="margin-right:5%;height: 254px;border: 1px solid rgb(241, 234, 234); display: flex;flex-direction: column;padding-top: 15px;padding-bottom: 20px;margin-bottom: 10px;margin-top: 10px;">
            <div style="border-bottom: 1px solid #F3570D;display: flex;justify-content: space-between;padding-bottom: 5px;padding-left: 10px;padding-right: 15px">
                <div style="font-weight: bold;font-size: 14px;">账户余额<img class="accounts-show" src="/images/eyes_open.png" style="width: 20px;height: 20px;margin-left: 20px;cursor: pointer"/></div>
                <span style="font-size: 12px;color: #797777;">单位：元</span>
            </div>
            <div style="margin-top: 35px;">
                <div style="color: #797777;font-size: 12px;display: flex;"><span style="width: 26%;text-align: right;padding-left: 10px;white-space: nowrap;">账户余额</span><span style="width: 40%;text-align: right;">可用余额</span></div>
            </div>
            <div style="margin-top: 25px;">
                <div style="color: #101010;font-size: 14px;display: flex;font-weight: bold"><span style="width: 32%;text-align: right;padding-left: 10px;white-space: nowrap;" class="accounts-total">￥0.15</span><span style="width: 40%;text-align: right;" class="accounts-available">￥0.15</span></div>
            </div>
            <div style="margin-top: 39px;display: flex;justify-content: center;font-size: 12px;">
                {{--<div style="width: 85px;background-color: #F3570D;text-align: center;padding-left: 10px; padding-right: 10px; border-radius: 5px;line-height: 25px;margin-right: 10%;">--}}
                    {{--<a style="color: white;" href="{{ url('finance/recharge') }}">立即充值 ></a>--}}
                {{--</div>--}}
                {{--<div style="cursor: pointer;width: 85px;background-color: #484747;color: white;text-align: center;padding-left: 10px; padding-right: 10px; border-radius: 5px;line-height: 25px;" class="refresh_dash">--}}
                    {{--刷新--}}
                {{--</div>--}}
            </div>
        </div>
        <div class="col-sm-3" style="height: 254px;border: 1px solid rgb(241, 234, 234);display: flex;flex-direction: column;padding-top: 15px;padding-bottom: 20px;margin-top: 10px;">
            <div style="margin-bottom:10px;border-bottom: 1px solid #F3570D;display: flex;justify-content: space-between;padding-bottom: 5px;padding-left: 10px;padding-right: 15px">
                <div style="font-weight: bold;font-size: 14px;">最近充值记录</div>
                <a href="{{ url('finance/record') }}"><span style="font-size: 12px;color: #F3570D;">查看更多 ></span></a>
            </div>
                <span style="margin-left: 10px">暂无记录</span>
        </div>
    </div>
    <div class="banner-cotent">
        <img src="/images/erp_banner.png" style="width: 100%;">
    </div>
</div>

@endsection

@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/erpkf/index.js') }}"></script>
    <script src="{{ URL::asset('assets/chart.js/Chart.bundle.min.js')}}"></script>
@endsection
<style>
    @media (max-width: 767px){
        .btn_recharge{
            font-size: 14px !important;
            line-height: 25px !important;
        }
        .col-sm-3{
            width: 100% !important;
            max-width: 100% !important;
        }
        .dashboard-info{
            margin-right: 0 !important;
        }
    }
    @media (min-width: 576px){
        .col-sm-3 {
            flex: 0 0 30% !important;
            max-width: 30% !important;
        }
    }
    .empty_center{
        text-align: center !important;
    }
    .top-content{
        width: 100%;
    }
    .banner-cotent{
        width: 100%;
        margin-top: 20px;
    }
    /*.col-sm-3{*/
        /*flex: 0 0 30% !important;*/
        /*max-width: 30% !important;*/
    /*}*/
</style>
