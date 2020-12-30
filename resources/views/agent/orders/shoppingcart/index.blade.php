<!doctype html>
@extends('layout.iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/agent/orders/orders.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 购物车列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" class="shopping_cart" style="padding:25px">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">商品购物车列出所有加入购物车的商品信息。</p>
            <p style="margin:5px 0;padding:0">购物车中商品设置有优惠劵的会出现在顶部，购物车中内容可批量操作，利用全选功能后选择对应功能操作。</p>
            <p style="margin:5px 0;padding:0"> </p>
        @endcomponent
        <!--  提示组件 end -->


        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table s_table" data-url="/orders/shopping_cart">
                <thead>
                <tr class="s_header_tr"></tr>
                <tr class="table-head">
                    <td>
                        <div class="s_checkall_d">
                                <input type="checkbox" class="checkall checkall1 all-checkall s_f_checkall checkbox" id="all" data-id="1">
                                <label for="all" style="margin-right: 10px;left: 12px" class="checkbox-label"></label>
                            <span class="s_checkall-text">全选</span>
                        </div>
                        <span>商品信息</span>
                    </td>
                    <td>商品属性</td>
                    <td>单价</td>
                    <td>数量</td>
                    <td>金额</td>
                    <td>操作</td>
                </tr>
                <tr class="s_header_tr"></tr>

                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>

        </div>
        <!-- 列表end    -->

        <!-- 合计start  -->
        <div class="s_footer">
            <div class="cart_goods_list_d" style="display:none;">
                <div class="trigger-bar  hidden" style="display: none;">
                    <a href="javascript:void(0);" title="上一页" class="prev">上一页</a>
                    <a href="javascript:void(0);" title="下一页" class="next">下一页</a>
                </div>

                {{--所选择的商品列表展示--}}
                <div class="cart_goods_list_item">


                </div>





                <div class="cart_in_triangle">
                    <img src="/images/in_triangle.png" alt="">
                </div>
            </div>




            <div class="s_checkall_f">
                <input type="checkbox" class="checkall all-checkall checkall3 checkbox" id="all2" data-id="3">
                <label for="all2" style="margin-right: 10px" class="checkbox-label"></label>
                <span class="s_checkall-text">全选</span>
            </div>
            <div class="sf_del sf_batch_del">
                <div class="sd_main">
                    <img class="s_trash" src="/images/trash.png" alt="">

                    <span style="margin-left: 12px;margin-bottom: 2px;">删除</span>
                </div>
            </div>
            <div class="sf_collect sf_batch_collect">
                <div class="sfc_main">
                    <img class="s_coll" src="/images/gratipay.png" alt="">
                    <span  style="margin-left: 12px;margin-bottom: 2px;">移入收藏</span>
                </div>
            </div>
            <div class="sf_goods_num">
                已选商品  <span class="sf_goods_num_val">0</span>  件
                <img src="/images/up.png" data-action="hide" class="data-img" alt="">
            </div>
            <div class="sf_goods_amout">
                <span>合计 （不计运费） :  ￥</span><span class="creat-order-price" style="color: #101010;font-size: 20px;font-weight: bold;">0.00</span>
            </div>
            <div class="s_settlement">
                    结&nbsp算
            </div>

        </div>

        <!-- 合计end  -->
    </div>
@endsection
@section("js-file")
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('js/agent/orders/orders.js')}}"></script>

@endsection






