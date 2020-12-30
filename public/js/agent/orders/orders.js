/**
 * 订单列表的js
 * Created by hlt on 2019/8/8
 */

$(function(){
    'use strict';

    // $(".tbl-content").delegate(".data-img","click",function () {
    //     var action = $(this).attr("data-action");
    //     if (action=="show")
    //     {
    //         $(this).attr("src","/images/up.png");
    //         $(".o_list_tr_hide").slideDown(500);
    //         $(this).attr("data-action","hide");
    //     }else{
    //         $(this).attr("src","/images/down.png")
    //         $(".o_list_tr_hide").slideUp(500);
    //         $(this).attr("data-action","show");
    //     }
    // });

    $(".tbl-content").delegate(".data-img","click",function () {
        var action = $(this).attr("data-action");
        var salerowspan = $(this).parent().parent().parent().next().find(".o_attr_sale");
        var logisticsrowspan = $(this).parent().parent().parent().next().find(".o_attr_logistics");
        var payrowspan = $(this).parent().parent().parent().next().find(".o_attr_pay");
        var tagrowspan = $(this).parent().parent().parent().next().find(".o_attr_tags");
        var operaterowspan = $(this).parent().parent().parent().next().find(".o_attr_operate");
        if (action=="show")
        {
            $(this).attr("src","/images/up.png");
            $(".o_list_tr_hide").css("display","table-row");
            $(this).attr("data-action","hide");
            salerowspan.attr('rowspan',$(this).attr("data-val"))
            logisticsrowspan.attr('rowspan',$(this).attr("data-val"))
            payrowspan.attr('rowspan',$(this).attr("data-val"))
            tagrowspan.attr('rowspan',$(this).attr("data-val"))
            operaterowspan.attr('rowspan',$(this).attr("data-val"))
        }else{
            $(this).attr("src","/images/down.png")
            $(".o_list_tr_hide").css("display","none");
            $(this).attr("data-action","show");
            salerowspan.attr('rowspan','3')
            logisticsrowspan.attr('rowspan','3')
            payrowspan.attr('rowspan','3')
            tagrowspan.attr('rowspan','3')
            operaterowspan.attr('rowspan','3')
        }
    });

    //全选
    $("body").delegate('.checkall','click',function () {
        //有多个全选框的情况
        var c_id = $(this).attr("data-id")?$(this).attr("data-id"):"";
        var c_name = "";
        var local_val = "";
        if (c_id!="")
        {
            c_name = "checkall"+c_id;
        }else{
            c_name = "checkall";
        }
        //判断是否为局部全选还是全部全选
        if ($(this).attr("data-value")){
            //点击的为局部全选
            if ($("."+c_name).prop("checked")) {
                $(".checkedres"+c_id).prop("checked",true);//全选

            } else {
                $(".checkedres"+c_id).prop("checked",false);  //取消全选
            }
        }else{
            //点击的为全部全选
            if ($("."+c_name).prop("checked")) {
                $("input[type='checkbox'][name='checkedres']").prop("checked",true);//全选

            } else {
                $("input[type='checkbox'][name='checkedres']").prop("checked",false);  //取消全选
                $("."+c_name).prop("checked",false)
                $(".local-checkall").prop("checked",false);

            }
        }

        var chsub = $("input[type='checkbox'][name='checkedres']").length; //获取subcheck的个数

        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数

        if (checkedsub == chsub) {
            //全部全选按钮
            $(".checkall").prop("checked",true);
        }else{
            $(".all-checkall").prop("checked",false);
        }

        //针对购物车页面
        if($(".sf_goods_num")){
            $(".sf_goods_num").find(".sf_goods_num_val").html(checkedsub);
        }
        getgoodscheck();

    });

    //批量标记
    $('.btn-batch-sign').click(function () {
        var arr = new Array();
        $("input[name='checkedres']:checked").each(function(i){
            arr[i] = parseInt($(this).val());
        });
        if(arr.length == 0){
            tip_note('请选择需要标记的订单','false')
            return;
        }
        $('.btn-sign').attr('data-url','/order/list/tag/'+JSON.stringify(arr));
        $('.btn-sign').click()
    })

    //文件下载处理
    $(".downloadFile").click(function () {
        $.ajax({
            url : "/order/list/download_check?ord_prod_id="+$(this).attr('data-value'),
            type : 'POST',
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data['success'] == 'false'){
                    layer.msg('',{
                        title: false,
                        content: data.message,
                        closeBtn:0,
                        offset: 'auto',
                        icon:5,
                        zIndex: layer.zIndex, //重点1
                        success: function(layero){
                            layer.setTop(layero); //重点2
                        }
                    });
                }else{
                    //开始下载
                    var arr = data.data;
                    for (var i = 0; i < arr.length; i++) {
                        window.open('/order/list/download?url='+arr[i])
                    }
                }
            }
        });
    })

    //单击单选框
    $("body").delegate(".checkedres","click",function () {
        var pid ="";
        var num = 0;
        var check_num = 0;
        if (!$(this).checked) {
            $(".all-checkall").prop("checked", false);
        }
        var chsub = $("input[type='checkbox'][name='checkedres']").length; //获取subcheck的个数

        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数

        if (checkedsub == chsub) {
            $(".checkall").prop("checked", true);
        }else{
            $(".all-checkall").prop("checked", false);
            //判断局部订单是否全部选中
            pid = $(this).attr("data-pid");
            num = $(".checkall"+pid).attr("data-num");
            check_num = $(".checkedres"+pid+":checked").length; //获取该订单选中的subcheck的个数
            if (num==check_num){
                $(".checkall"+pid).prop("checked", true);
            }else{
                $(".checkall"+pid).prop("checked", false);
            }


        }

        //针对购物车页面
        if($(".sf_goods_num")){
            $(".sf_goods_num").find(".sf_goods_num_val").html(checkedsub);

        }
        getgoodscheck();


    });

/*    //取消订单警告弹窗
    $("body").delegate(".btn-del","click",function () {
        tip_warn('','是否确定取消订单','取消订单');
    });*/


    //订单详情js start
    // $(".double-d").click(function () {
    //     var action = $(this).find("img").attr("data-action");
    //     if (action == "hide")
    //     {
    //         $(this).find("img").attr("src","/images/chevron-double-down.png");
    //         $(this).find("img").attr("data-action","show");
    //         $(".d_transition").slideUp(500);
    //     }else{
    //         $(this).find("img").attr("src","/images/chevron-double-up.png")
    //         $(this).find("img").attr("data-action","hide");
    //         $(".d_transition").slideDown(500);
    //
    //     }
    // });

    //确认收货
    $('.btn-receiver').click(function () {
        $.ajax({
            url : '/order/receiver',
            type : 'POST',
            data : {'order_id':$(this).attr('data-value')},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data.success == 'true'){
                    tip_success('',data.data,'',2)
                }else{
                    layer.msg('',{
                        title: false,
                        content: data.message,
                        closeBtn:0,
                        offset: 'auto',
                        icon:5,
                        zIndex: layer.zIndex, //重点1
                        success: function(layero){
                            layer.setTop(layero); //重点2
                        }
                    });
                }
            }

        });
    })

    $("body").delegate(".btn-warn","click",function () {
        tip_warn();
    });
    $("body").delegate(".btn-suc-tip","click",function () {
        tip_success("该商品已移进收藏")
    });

    //返回订单列表
    $(".od_return_list").click(function () {
       location.href = "/order/list"
    });
    //订单详情js end

    //购物车js start

    //优惠券列表点击
    $(".tbl-content").delegate(".s_z_coupon","click",function () {
        if($(this).find(".s_chevron").hasClass("fa-chevron-up"))
        {
            //收起优惠券表
            $(this).siblings(".s_coupon_list").hide();
            //图标向上
            $(this).find(".s_chevron").removeClass("fa-chevron-up").addClass("fa-chevron-down")
        }else{
            //展开优惠券表
            $(this).siblings(".s_coupon_list").show();
            //图标向下
            $(this).find(".s_chevron").removeClass("fa-chevron-down").addClass("fa-chevron-up")
        }
    });




    //优惠券关闭
    $(".tbl-content").delegate(".sc_img","click",function () {
        //展开优惠券表
        $(".s_coupon_list").hide();
        //图标向上
        $(".s_chevron").removeClass("fa-chevron-up").addClass("fa-chevron-down")
    });

    //领取优惠券
    $(".tbl-content").delegate(".s_receive","click",function () {
        $(this).html("领取成功");
        $(this).removeClass("s_receive").addClass("s_d_receive");

    })

    //全选按钮以统一方法处理（在list页面的js中）

    //商品数量加减点击
    $(".tbl-content").delegate(".s_reduce,.s_addition","click",function () {
       var action = $(this).attr("data-value");
       var num = 0;
        num = $(this).siblings(".s_num_d").find(".s_num_input").val();
        if (action == "reduce")
        {
            //减数操作
            num = parseInt(num) - 1;
            if (num<=0)
            {
                num = 1;
            }
            $(this).siblings(".s_num_d").find(".s_num_input").val(num)
        }else {
            //加数操作
            num = parseInt(num) + 1;
            if (num<=0)
            {
                num = 1;
            }
            $(this).siblings(".s_num_d").find(".s_num_input").val(num)
        }

        //获取单价
        var price = $(this).parents(".s_works_num").siblings(".s_works_price").find("input[type='hidden']").val();
        price = parseFloat(price);
        num = parseFloat(num);
        var total_price = parseFloat(price*num);
        $(this).parents(".s_works_num").siblings(".s_works_amount").find("p").text("￥"+total_price.toFixed(2));
        $(this).parents(".s_works_num").siblings(".s_works_amount").find("input[type='hidden']").val(total_price.toFixed(2));
        getgoodspriceamount();
        var project_id = $(this).parents(".s_works_num").find(".project_id").val();
        var cart_id = $(this).parents(".s_works_num").find(".cart_id").val();
        var sku_id = $(this).parents(".s_works_num").find(".sku_id").val();
        changecartgoodsnum(cart_id,sku_id,num);

    })

    //商品数量输入验证
    $(".tbl-content").delegate(".s_num_input","blur",function () {
        var num = $(this).val();

        var re = new RegExp("^[0-9]*[1-9][0-9]*$");
        if (!re.test(num)) {
            $(this).val("1")
            num = 1;
        }
        //获取单价
        var price = $(this).parents(".s_works_num").siblings(".s_works_price").find("input[type='hidden']").val();
        price = parseFloat(price);
        num = parseFloat(num);
        var total_price = parseFloat(price*num);
        $(this).parents(".s_works_num").siblings(".s_works_amount").find("p").text("￥"+total_price.toFixed(2))
        $(this).parents(".s_works_num").siblings(".s_works_amount").find("input[type='hidden']").val(total_price.toFixed(2));
        getgoodspriceamount();
        var project_id = $(this).parents(".s_works_num").find(".project_id").val();
        var cart_id = $(this).parents(".s_works_num").find(".cart_id").val();
        var sku_id = $(this).parents(".s_works_num").find(".sku_id").val();

        changecartgoodsnum(cart_id,sku_id,num);

    })

    function changecartgoodsnum(cart_id,sku_id,num) {
        //更改购物车商品数量
        $.ajax({
            url : "/orders/shopping_cart_num",
            type: 'POST',
            data:{cart_id:cart_id,sku_id:sku_id,num:num},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){

            },
            error : function(){

            }
        });
    }

    $("body").delegate(".btn-to-collect","click",function () {
        var sku_id = $(this).attr("data-sku-id");
        var cart_id = $(this).attr("data-cart-id");
        collectcartgoods(sku_id,cart_id);

    })
    //批量移入收藏
    $(".sf_batch_collect").click(function () {
        var  sku_id = [];
        var cart_id = 0;
        if ($(".g_checkedres:checked").length == 0){
            tip_note("请选择要收藏的商品")
            return;
        }
        $(".g_checkedres:checked").each(function () {
            sku_id.push($(this).attr("data-sku-id"));
            cart_id = $(this).attr("data-cart-id");
        })
        var sku_str = sku_id.join(",");

        collectcartgoods(sku_str,cart_id);
    })


    //加入收藏
    function collectcartgoods(sku_str,cart_id) {
        $.ajax({
            url : "/orders/collect_cart_goods",
            type: 'POST',
            data:{sku_id:sku_str,cart_id:cart_id},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if (data.success == 'false')
                {
                    tip_note("收藏失败");
                }else{
                    tip_note("收藏成功",'success')
                }
            },
            error : function(){

            }
        });
    }

    //批量删除购物车数据
    $(".sf_batch_del").click(function () {
        var  cart_arr = {};
        if ($(".g_checkedres:checked").length == 0){
            tip_note("请选择要删除的商品")
            return;
        }
        $(".g_checkedres:checked").each(function (index,value) {
            var cart_id = $(this).attr("data-cart-id");
            if (!cart_arr[cart_id]){
                cart_arr[cart_id] = {}
            }
            cart_arr[cart_id][index] = $(this).attr("data-sku-id");
        });
        var cart_str = JSON.stringify(cart_arr);
        var option ={};
        option['url'] = "/orders/batch_del_cart_goods?data="+cart_str;
        tip_warn(option);
    })


    //结算跳转页面
    $(".s_settlement").click(function () {
        if ($(".g_checkedres:checked").length == 0){
            tip_note("请选择要结算的商品")
            return;
        }
        var cart_arr = {};
        $(".g_checkedres:checked").each(function (index,value) {
            var cart_id = $(this).attr("data-cart-id");
            if (!cart_arr[cart_id]){
                cart_arr[cart_id] = {}
            }
           if (!cart_arr[cart_id]['sku_id'])
           {
               cart_arr[cart_id]['sku_id'] = {}
           }
            cart_arr[cart_id]['sku_id'][index] =  $(this).attr("data-sku-id");
            if (!cart_arr[cart_id]['proj_id'])
            {
                cart_arr[cart_id]['proj_id'] = {}
            }
            cart_arr[cart_id]['proj_id'][index] =  $(this).attr("data-project-id");


        });
        var cart_str = JSON.stringify(cart_arr);
       location.href="/orders/create?cart_id="+cart_str;
    })

    //购物车页面点击除checkbox外隐藏商品件数按钮
    $(".shopping_cart").click(function(e){
        //获取鼠标所点击的区域
        var now_target = e.target;

        //判断区域是否在cart_goods_list_d范围内
        var cg_length = $(".cart_goods_list_d").find(now_target).length;
        //当点击checkbox时触发了两次，一次是label点击一次是checkbox点击，需两个同时判断
        //当出现以下任一情况时不做隐藏cart_goods_list_d的操作
        /*1.点击checkbox时;
        2.与checkbox相连的label时;
        3.点击cart_goods_list_d内元素时;
        4.点击cart_goods_list_d时;
        5.点击商品件数的显隐按钮时;
        6.点击cart_goods_list_d的取消选择时*/
        if ($(now_target).hasClass("checkbox")||$(now_target).hasClass("checkbox-label")||cg_length!="0"||$(now_target).hasClass("data-img")||$(now_target).hasClass("cart_item_child_cancel")||$(".cart_goods_list_d").is(now_target)){
            return;
        }else{
            $(".s_footer").find(".data-img").attr("src","/images/up.png");
            $(".cart_goods_list_d").css("display","none");
            $(".s_footer").find(".data-img").attr("data-action","hide");
        }


    });
    //获取选中商品价格
    function getgoodspriceamount() {
        var total_price = 0
        $(".g_checkedres:checked").each(function () {
            //计算总价 将价格加到总价上去
            var this_price = $(this).parents(".o_attr_goods").siblings(".s_works_amount").find("input[type='hidden']").val();
            this_price = parseFloat(this_price);
            total_price = parseFloat(total_price);
            total_price = parseFloat(total_price + this_price);

        })
        $(".creat-order-price").text(total_price.toFixed(2));
    }


    //购物车获取所选中的商品
    function getgoodscheck() {
        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数
        if (checkedsub=="0"){
            $(".s_footer").find(".data-img").attr("src","/images/up.png");
            $(".cart_goods_list_d").css("display","none");
            $(".s_footer").find(".data-img").attr("data-action","hide");
        }
        $(".cart_goods_list_item").html("");
        $(".g_checkedres:checked").each(function () {
            var goods_id = $(this).attr("data-id");
            var img_src = $(this).parents(".s_check").siblings(".sc_works_img").find("img").attr("src");
            var html = ' <div class="cart_goods_list_item_child"> ' +
                '<span class="cart_item_child_cancel" data-id = "' + goods_id + '">取消选择</span> ' +
                '<img src="' + img_src + '" alt=""> ' +
                '</div>';
            $(".cart_goods_list_item").append(html);
        })

        //判断是否出现左右滑块
        var g_sub = $(".g_checkedres:checked").length;
        //因为未显示时宽度为0，所以计算显示后的宽度
        var width = $(".s_footer").width() - 110;//底部的宽度减去左右padding
        //每个商品的图片所占的宽度为80px+30px=110px
        var goods_width = 110*g_sub;
        if (goods_width>width||width-goods_width<=80){
         $(".cart_goods_list_d").find(".hidden").show();
        }else{
            $(".cart_goods_list_d").find(".hidden").hide();
        }
        getgoodspriceamount()
    }


    //选中商品的取消选择
    $(".s_footer").delegate(".cart_item_child_cancel","click",function () {
       var good_id = $(this).attr("data-id");
        $(".g_checkedres[data-id="+good_id+"]").trigger("click");
    });

    //商品列表左右按钮div移动
    $(".prev,.next").click(function () {
        var g_sub = $(".g_checkedres:checked").length;
        var width = $(".cart_goods_list_item").width();
        //去除除图片外剩余的宽度
        var r_width = width%110;
        //保证每次移动时最左侧只有一张图片
        var an_width = width - 110 - r_width;
        //获取图片的总长度
        var g_width = g_sub*110;

        //判断平移方向
        if($(this).hasClass("prev")) {
            //向左
            var left = $(".cart_goods_list_item_child").css("left");
            if (left!="0px")
            {
                $(".cart_goods_list_item_child").animate({left:"+="+an_width+"px"});
                left = $(".cart_goods_list_item_child").css("left");
            }

        }else{
            //向右
            var right = $(".cart_goods_list_item_child").css("left");
            //将移动的宽度读出
            var int_right = parseInt(right);
            if (int_right<0){
                //宽度算法,使得不会右滑出现空白页的情况
                int_right = (-int_right)+an_width;
            }
            if (g_width>width && g_width>int_right)
            {
                if (right!="0px"){

                    $(".cart_goods_list_item_child").animate({left:"-="+an_width+"px"});

                }else{

                    $(".cart_goods_list_item_child").animate({left:'-'+an_width+'px'});

                }
            }
            left = $(".cart_goods_list_item_child").css("left");

        }

    })

    //商品件数点击按钮

    $(".s_footer").delegate(".data-img","click",function () {
        if($(".sf_goods_num_val").text()>0)
        {
            var action = $(this).attr("data-action");
            if (action=="show")
            {
                $(this).attr("src","/images/up.png");
                $(".cart_goods_list_d").css("display","none");
                $(this).attr("data-action","hide");
            }else{
                $(this).attr("src","/images/down.png")
                $(".cart_goods_list_d").css("display","flex");
                $(this).attr("data-action","show");
            }
        }
    });

    //购物车js end

    //创建订单js start

    //显隐收货地址
    $(".c_show_add,.c_angle-double-down").click(function () {
        var action = $(".c_show_add").attr("data-action");
        if (action=="show")
        {
            $(".c_show_add").html("收起全部地址");
            $(".c_angle-double-down").removeClass("fa-angle-double-down").addClass("fa-angle-double-up");
            $(".c_show_add").attr("data-action","hide")
            $(".c_address_hide").removeClass("c_hide")
        }else{
            $(".c_show_add").html("显示全部地址");
            $(".c_angle-double-down").removeClass("fa-angle-double-up").addClass("fa-angle-double-down");
            $(".c_show_add").attr("data-action","show")
            $(".c_address_hide").addClass("c_hide")
        }

    });

    //获取创建订单数据
    function get_cart_data() {
            var cart_id = $(".cart_id").val();
            var cartArr = {};

            $.each($(".doc_work_detail"),function (index,value) {
                cartArr[index]={};
                cartArr[index]['proj_id'] = $(this).find(".proj_id").val();
                cartArr[index]['sku_id'] = $(this).find(".sku_id").val();
                cartArr[index]['cart_id'] = cart_id;
            })
        return cartArr;
    }



    //选择收货地址
    $(".c_address").click(function () {

        $(".c_address").removeClass("c_add_active");
        $(".c_quarter").addClass("c_hide");
        $(".c_check_img").addClass("c_hide");
        $(this).addClass("c_add_active");
        $(this).find(".c_quarter").removeClass("c_hide");
        $(this).find(".c_check_img").removeClass("c_hide");

       //获取此时的省市区id
        var province_id = $(this).find(".province_id").val();
        var city_id = $(this).find(".city_id").val();
        var district_id = $(this).find(".district_id").val();

        var is_new_address = check_new_address();
        if (is_new_address){

        } else{
            //组织收货地址
            $(".doc_user_address_prov").text($(this).find(".prov-name").attr("data-value")+" ");
            $(".doc_user_address_city").text($(this).find(".city-name").attr("data-value")+" ");
            $(".doc_user_address_area").text($(this).find(".area-name").attr("data-value")+" ");
            $(".doc_user_address_detail").text($(this).find(".caa_address").text()+" ");
            $(".doc_rcv_username").text($(this).find(".rcv-user").attr("data-value"));
            $(".doc_rcv_mobile").text($(this).find(".caa_phone").attr("data-value"));

            if ($(".areas-area").val() == "" || $(".areas-area").val() == 0 )
            {
                get_price(province_id,city_id,district_id)
            }

        }




    });



    //监听新地址中区的变化
    $("body").delegate(".areas-area","change",function (){

        //获取此时的省市区id
        var province_id = $(this).siblings(".areas-province").val();
        var city_id = $(this).siblings(".areas-city").val();
        var district_id = $(this).val();
        get_price(province_id,city_id,district_id);
        var check_input = check_new_address();
        if (!check_input){
            //如果新地址都被清空了，则使用收货地址列表的收货地址
            if ($(".doc_new_landline").val() == "" && $(".doc_new_code").val() == "" && $(".doc_new_consignee").val() == "" && $(".doc_new_consignee_phone").val() == "" && ($(".areas-area").val() == "" || $(".areas-area").val() == 0 ||$(".areas-area").val() == "区") && $(".doc_new_detail_address").val() == "") {
                if ($(".c_add_active")){
                    $(".c_add_active").trigger('click');
                }
            };
        }
    });
    //获取快递方式跟运费
    function get_price(province_id,city_id,district_id) {
        //省id
        var pro_id = province_id;
        //市id
        var city_id = city_id;
        //区id
        var area_id = district_id;
        //获取购物车数组
        var cartArr = get_cart_data();
        //总重量
        var total_weight =0;
        var prod_ids =[];
        $.each($(".doc_work_detail"),function (index,value) {
            var weight = $(this).parents(".d_o_attr_goods").siblings(".d_o_attr_weight").find(".sku_weight").val();
            var num = $(this).parents(".d_o_attr_goods").siblings(".d_o_attr_num").find(".prod_num").val();
            total_weight += weight*num;
            prod_ids.push($(this).find(".prod_id").val());

        })
        //模板id
        var temp_ids = "";
        $(".temp_id").each(function () {
            if(temp_ids==""){
                temp_ids = $(this).val();
            }else{
                temp_ids = temp_ids+","+$(this).val();
            }
        });
        //商品id
        var prod_id =prod_ids.join(",");
        //获取快递
        $.ajax({
            url : '/create/get_create_delivery_price',
            type: 'POST',
            data:{
                cart_arr:cartArr,
                pro_id:pro_id,
                city_id:city_id,
                area_id:area_id,
                total_weight:total_weight,
                temp_id:temp_ids,
                prod_id:prod_id
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if(data.status==404){
                    tip_note(data.message);
                    return;
                }
                $(".doc_delivery_select").html("");
                $(".doc_delivery_select").html(data.data);


            },
            error : function(e){
                tip_note("程序出现错误");
            }
        });
    };
    $("body").delegate("input[type = 'radio'][name = delivery_id]",'click',function () {
        var delivery_amount = $(this).parents(".c_d_radio").siblings(".c_d_price").find(".deli_price").val();
        $(".doc_delivery_amount").text(delivery_amount);
        var now_good_amount = $(".doc_goods_total_amount").attr("data-value");
        var order_amount = parseFloat(delivery_amount)+parseFloat(now_good_amount);
        $(".create_order_amount").val(order_amount)
        $(".doc_order_amount").text(RetainedDecimalPlaces(order_amount,2))

    })
    //判断新地址是否有填
    function check_new_address() {
        //验证输入
        if ($(".doc_new_consignee").val()=="" || $(".doc_new_consignee_phone").val()=="" || ($(".areas-area").val()=="" || $(".areas-area").val()==0 || $(".areas-area").val() == "区") || $(".doc_new_detail_address").val()==""){
            //验证不通过
        /*    //判断是否选择了收货地址
            if ($(".c_add_active")){
                $(".c_add_active").trigger('click');
            }*/
            return false;
        }else{
            //获取收货地址
            var prov_name = $(".areas-province").find("option:selected").text();
            var city_name = $(".areas-city").find("option:selected").text();
            var area_name = $(".areas-area").find("option:selected").text();
            var address = $(".doc_new_detail_address").val();
            //收货人与电话
            var consignee = $(".doc_new_consignee").val();
            var tel = $(".doc_new_consignee_phone").val();
            //填充

            //组织收货地址
            $(".doc_user_address_prov").text(prov_name+" ");
            $(".doc_user_address_city").text(city_name+" ");
            $(".doc_user_address_area").text(area_name+" ");
            $(".doc_user_address_detail").text(address+" ");
            $(".doc_rcv_username").text(consignee);
            $(".doc_rcv_mobile").text(tel);
            return true;
        }
    }
    //收货人输入验证
    $("body").delegate(".doc_new_consignee",'blur',function () {
        var check_input = check_new_address();
        console.log(check_input);
        console.log($(".areas-area").val());
        if (!check_input){
            //如果新地址都被清空了，则使用收货地址列表的收货地址
            if ($(".doc_new_landline").val() == "" && $(".doc_new_code").val() == "" && $(".doc_new_consignee").val() == "" && $(".doc_new_consignee_phone").val() == "" && ($(".areas-area").val() == "" || $(".areas-area").val() == 0 || $(".areas-area").val() == "区") && $(".doc_new_detail_address").val() == "") {
                console.log(123);

                if ($(".c_add_active")){
                    console.log(654);
                    $(".c_add_active").trigger('click');
                }
            };
        }

    })
    //收货人手机验证
    $("body").delegate(".doc_new_consignee_phone",'blur',function () {
        var check_input = check_new_address();
        if (!check_input){
            //如果新地址都被清空了，则使用收货地址列表的收货地址
            if ($(".doc_new_landline").val() == "" && $(".doc_new_code").val() == "" && $(".doc_new_consignee").val() == "" && $(".doc_new_consignee_phone").val() == "" && ($(".areas-area").val() == "" || $(".areas-area").val() == 0 || $(".areas-area").val() == "区") && $(".doc_new_detail_address").val() == "") {
                if ($(".c_add_active")){
                    $(".c_add_active").trigger('click');
                }
            };
        }
    })
    //详细地址验证
    $("body").delegate(".doc_new_detail_address",'blur',function () {
        var check_input = check_new_address();
        if (!check_input){
            //如果新地址都被清空了，则使用收货地址列表的收货地址
            if ($(".doc_new_landline").val() == "" && $(".doc_new_code").val() == "" && $(".doc_new_consignee").val() == "" && $(".doc_new_consignee_phone").val() == "" && ($(".areas-area").val() == "" || $(".areas-area").val() == 0 || $(".areas-area").val() == "区") && $(".doc_new_detail_address").val() == "") {
                if ($(".c_add_active")){
                    $(".c_add_active").trigger('click');
                }
            };
        }

    })
    //提交订单
    $("body").delegate(".c_button-wrapper-tj",'click',function () {
        //验证是否有填写新地址
        var address = {};
        if ($(".doc_new_landline").val()!=""|| $(".doc_new_code").val()!="" ||$(".doc_new_consignee").val()!="" || $(".doc_new_consignee_phone").val()!="" || ($(".areas-area").val()!="" && $(".areas-area").val()!=0 && $(".areas-area").val() != "区") || $(".doc_new_detail_address").val()!="")
        {
            //填写了新地址，进行验证
           var check_input = check_new_address();
           if (!check_input){
               if ($(".doc_new_consignee").val()==""){
                   tip_note("请填写收货人");
                   return;
               }
               if ($(".doc_new_consignee_phone").val()==""){
                   tip_note("请填写收货人手机");
                   return;
               }
               if ($(".areas-area").val()=="" || $(".areas-area").val()==0)
               {
                   tip_note("请选择收货地址");
                   return;
               }
               if ( $(".doc_new_detail_address").val()=="")
               {
                   tip_note("请填写收货详细地址");
                   return;
               }
               if ($(".areas-area").val()=="" || $(".areas-area").val()==0 || $(".areas-area").val() == "区")
               {
                   tip_note("请选择收货地址");
                   return;
               }
           }else{
               //获取收货地址信息
               address['consignee']       = $(".doc_new_consignee").val();
               address['ship_mobile']     = $(".doc_new_consignee_phone").val();
               address['province_code']   = $(".areas-province").find("option:selected").val();
               address['city_code']       = $(".areas-city").find("option:selected").val();
               address['district_code']   = $(".areas-area").find("option:selected").val();
               address['ship_addr']       = $(".doc_new_detail_address").val();
               address['ship_tel']        = $(".doc_new_landline").val();
               address['ship_zip']        = $(".doc_new_code").val();
               address['type']            = 1
           }
        }else{
            if ($(".c_add_active").length == 0)
            {
                tip_note("请选择收货地址");
                return;
            }
            //获取收货地址信息
            address['consignee']       = $(".c_add_active").find(".rcv-user").attr("data-value");
            address['ship_mobile']     = $(".c_add_active").find(".caa_phone").attr("data-value");
            address['province_code']   = $(".c_add_active").find(".province_id").val();
            address['city_code']       = $(".c_add_active").find(".city_id").val();
            address['district_code']   = $(".c_add_active").find(".district_id").val();
            address['ship_addr']       = $(".c_add_active").find(".caa_address").text();
            address['ship_tel']        = $(".c_add_active").find(".ship_tel").val();
            address['ship_zip']        = $(".c_add_active").find(".ship_zip").val();
            address['type']            = 0
        }

        //判断是否选择快递方式
        if (!$("input[type = 'radio'][name = delivery_id]:checked").val())
        {
            tip_note("请选择快递方式");
            return;
        }

        //获取快递id与快递模板id
        var shipping_id = $("input[type = 'radio'][name = delivery_id]:checked").val();
        var shipping_temp_id = $("input[type = 'radio'][name = delivery_id]:checked").parents(".c_d_info").find(".del_temp_id").val();

        //获取物流价
        var post_fee = $("input[type = 'radio'][name = delivery_id]:checked").parents(".c_d_info").find(".deli_price").val();
        //获取支付方式
        var pay_id = $(".c_active_dad").find(".pay_id").val();
        var pay_class_name = $(".c_active_dad").find(".pay_classname").val();
        if (pay_class_name != 'balance'){
            tip_note("暂时只支持余额支付");
            return;
        }
        //判断是否为快速购买渠道进来的
        var isFast = $(".is_fast").val();

        //获取购物车数组
        var cartArr = get_cart_data();

        //获取快递
        $.ajax({
            url : '/create/carete_order',
            type: 'POST',
            data:{
                cart_arr:cartArr,
                address:address,
                shipping_id:shipping_id,
                shipping_temp_id:shipping_temp_id,
                post_fee:post_fee,
                pay_id:pay_id,
                is_fast:isFast,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if(data.status==404){
                    tip_note(data.message);
                    return;
                }
                tip_note("订单创建成功",'success')
                setTimeout(function () {
                    location.href='/order/list'
                },2000)
            },
            error : function(e){
                tip_note("程序出现错误");
            }
        });



    })
    
    
    //添加千位分隔符
    function RetainedDecimalPlaces(num, del) //值：num 小数位：del
    {
        if (del != 0)
        {
            num = parseFloat(num).toFixed(del); //天花板函数保留小数并四舍五入
        }
        var source = String(num).split(".");//按小数点分成2部分
        source[0] = source[0].replace(new RegExp('(\\d)(?=(\\d{3})+$)', 'ig'), "$1,");//只将整数部分进行都好分割
        return source.join(".");//再将小数部分合并进来
    };

    //选择支付方式
    $(".c_dad ").click(function () {
       $(".c_dad").removeClass("c_active_dad");
       $(this).addClass("c_active_dad");

    });

    //选择开具发票
    $("#c_ll").click(function () {
        if($(this).prop("checked")){
            $(".c_invoice_item").slideDown(500);
        }else{
            $(".c_invoice_item").slideUp(500);
        }

    });

    //创建订单js end

    //售后订单 start
    $("body").delegate(".a_add_goods","click",function () {
        //获取父级layer的index
        var father_index = $(this).parents(".layui-layer").attr("times");
        if ($("#layui-layer"+father_index+"").attr("child_index")){
            alert("选择商品窗口已打开");
            return;
        }

        $.ajax({
            url : "/order/order_goods",
            type: 'POST',
            data:{},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                layer.open({
                    type:1,
                    title:"订单信息",
                    closeBtn: 1,
                    resize : false,
                    shade:0,
                    area:['60%','450px'],
                    skin:"order-skin",
                    content: data.data.html,
                    zIndex: layer.zIndex,
                    success: function(layero, index){
                       $("#layui-layer"+father_index+"").attr("child_index",index);
                    },
                    end:function (index) {
                        $("#layui-layer"+father_index+"").removeAttr("child_index");
                    }
                });
            },
            error : function(){

            }
        });

    });

    $("body").delegate(".og-cancel","click",function () {
        var index = $(this).parents(".layui-layer").attr("times");
        layer.close(index);

    });

    $("body").delegate(".layui-layer-close","click",function () {
        //获取父级layer的index
        var father_index = $(this).parents(".layui-layer").attr("times");
        var index=$("#layui-layer"+father_index+"").attr("child_index");
        if (index){
          layer.close(index);
        }
    });

    //服务类型
    $("body").delegate(".ao_service_type","click",function () {
       if ($(this).hasClass("refund"))
       {
           $(".ao_refund_address").show();
       }else{
           $(".ao_refund_address").hide();
       }
    })

    //添加售后单
    $("body").delegate(".form_order_no","blur",function(){
        if($(this).val() != ''){
            $.ajax({
                url : '/order/service/get_amount',
                type : 'POST',
                data : {'order_no':$(this).val()},
                dataType : 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success : function (data) {
                    if(data.success == 'true'){
                        $(".order_real_total").html(data.data['order_real_total'])
                        $(".order_exp_fee").html(data.data['order_exp_fee'])
                        $("#refund_money").val(data.data['order_real_total'])
                    }else{
                        $(".order_real_total").html('0.00')
                        $(".order_exp_fee").html('0.00')
                        $("#refund_money").val('')
                        layer.msg('',{
                            title: false,
                            content: data.message,
                            closeBtn:0,
                            offset: 'auto',
                            icon:5,
                            zIndex: layer.zIndex, //重点1
                            success: function(layero){
                                layer.setTop(layero); //重点2
                            }
                        });
                    }
                }
            });
        }

    });
    
    //售后订单 end

    //管理收货地址 start
    $(".tbl-content").delegate(".oa_to_default","click",function () {
        $(".oa_address_default").text("设为默认");
        $(".oa_address_default").removeClass("oa_address_default").addClass("oa_to_default");
        $(this).text("默认地址");
       $(this).removeClass("oa_to_default").addClass("oa_address_default");
    });


   //管理收货地址 end


});