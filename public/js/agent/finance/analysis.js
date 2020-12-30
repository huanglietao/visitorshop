$(function(){
    'use strict';

    $(document).ready(function () {
        //加入加载标示
        loadtable('1');

    });

    $(".s_analy_tab").click(function () {
        var cate_id = $(this).attr("data-val");
        loadtable(cate_id);
    });
    function loadtable(cate_id,search) {
        $('.statistics_loading').html("");
        var html = '<div class="new_loading" style="text-align: center;margin-top:10px;position: absolute;z-index: 2;left: 0;right: 0;margin-left: auto;margin-right: auto;"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>';
        $('.statistics_loading').append(html);
        var url ="";
        var str = "";
        //对应模块视图地址
        switch (cate_id) {
            case '1' :
                url = "/finance/sales_analysis/sales";
                break;
            case '2' :
                url = "/finance/order/index";
                break;
            case '3' :
                url = "/finance/goods/index";
                break;
            case '4' :
                url = "/finance/areas/index";
                break;
            case '5' :
                url = "/finance/logistics/index";
                break;
            case '6' :
                url = "/finance/logisticsDetail/index";
                break;
            default : url = "/finance/sales_analysis/sales";break;

        }
        $.ajax({
            url : url,
            type: 'GET',
            data : search,
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if (data.status==200){
                    $(".new_loading").hide();
                    $("#statistics-view").html(data.html);
                    switch (cate_id) {
                        case '1' :
                        case '2' :
                            str = '<p class="accounts-two">销售分析包括：订单统计、商品统计、地区统计、物流统计</p> ' +
                                '<p class="accounts-two">销售总额是所选时间范围内所有订单状态下商品售价总额+运费总额；异常订单指的是所选统计条件下，已取消+已退货+无效的订单。</p> ' +
                                '<p class="accounts-two">平均客单价：指的是销售总额÷成交总笔数（成交顾客的转化率）</p>';
                            break;
                        case '3' :
                        case '4' :
                        case '5' :
                            str = '<p class="accounts-two">销售分析包括：订单统计、商品统计、地区统计、物流统计</p> ' +
                                '<p class="accounts-two">销售总额是所选时间范围内所有订单状态下商品售价总额+运费总额；异常订单指的是所选统计条件下，已取消+已退货+无效的订单。</p> ' +
                                '<p class="accounts-two"></p>';
                            break;
                        default : str = "";break;
                    }
                 //修改tip数据
                    if (str!="")
                    {
                        $(".ex_descrition").html(str);
                    }
                    //渲染分页数据
                    $("#list-total").html(data.total); //总数

                    var limit = parseInt($("#pages-limit").val());
                    if(isNaN(limit)) {
                        limit = 10;
                    }

                    //判断是否有时间组件
                    if ( $(".main-search").find("#reservationtime"))
                    {
                        rangedatapicker();
                    }
                    //页数
                    var pages = Math.round(data.total/limit);
                    $("#limit_pages").text(pages);// 分多少页数.d
                    $("#total_pages").val(pages);

                    //当前页
                    var  currentPage = 1;
                    if(search !='' && typeof (search) !='undefined') {
                        if(typeof (search.page) == "undefined") {
                            currentPage = 1;
                        } else {
                            currentPage = search.page;
                        }
                    }

                    if(currentPage == 1) { //第1页时上一页和首页不能操作
                        $(".pages-first").addClass('disabled');
                        $(".pages-prev").addClass('disabled');
                    }else if(currentPage >=pages) {
                        $(".pages-next").addClass('disabled');
                        $(".pages-last").addClass('disabled');
                    } else {
                        $(".pages-first").removeClass('disabled');
                        $(".pages-prev").removeClass('disabled');
                        $(".pages-next").removeClass('disabled');
                        $(".pages-last").removeClass('disabled');
                    }


                    var option = "";
                    var selected  = '';
                    for (var i=1; i<=pages; i++) {
                        if(currentPage == i) {
                            selected = "selected"
                        } else {
                            selected = ""
                        }
                        option += "<option "+selected+" value='"+i+"'>第"+i+"页</option>";
                    }

                    $("#pages-item").html(option);
                }
            },
            error : function(){

            }
        });
    }
    //全选
    // $("body").delegate('.checkall','click',function () {
    //     if ($(".checkall").prop("checked")) {
    //         $("input[type='checkbox'][name='checkbox[]']").prop("checked",true);//全选
    //         $(".checkall").prop("checked",true);//全选
    //     } else {
    //         $("input[type='checkbox'][name='checkbox[]']").prop("checked",false);  //取消全选
    //         $(".checkall").prop("checked",false);  //取消全选
    //     }
    // });
    // //单击单选框
    // $("body").delegate(".checkedres","click",function () {
    //     var sumcheckbox = $("input[type='checkbox'][name='checkbox[]']").length; //获取所有复选框的总个数
    //     var sumcheckedbox = $("input[type='checkbox'][name='checkbox[]']:checked").length; //获取选中的总个数
    //     //对比是否相等就全部选中，否则非全选
    //     if (sumcheckbox == sumcheckedbox) {
    //         $(".checkall").prop("checked",true);
    //         $(".checkall").prop("checked",true);//全选
    //     }else{
    //         $(".checkall").prop("checked",false);
    //         $(".checkall").prop("checked",false);//取消全选
    //     }
    // });


    $("body").delegate("#logistics_detail",'click',function () {
       var id = $(this).attr('data-value');
       var obj = {express_id:id};
       var tj = JSON.stringify(obj);
        obj = JSON.parse(tj);
       loadtable('6',obj);
    });

    $("body").delegate(".btn-back",'click',function () {
        loadtable('5');
    });



    $("body").delegate('.check input[type="checkbox"]','click',function () {
        var status = $(this).val();
        var order_status = $(this).parent().prev().val();

        var status_list =[];
        if(order_status!==""){
            status_list = order_status.split(",");
        }
        if($(this).prop("checked")){
            status_list.splice(status_list.length,0,status);
        }else{
            status_list.splice($.inArray(status,status_list),1);
        }
        $(this).parent().prev().val(status_list);
    });

    //单选
    $("body").delegate(".checkedres",'click',function () {
        //得到tr下的所有td
        var tds = $(this).parent().nextAll();
        var orderInfo = [];
        //如果被选中
        if($(this).prop("checked")){
            //遍历得到的td
            $.each(tds, function(key,value){
                //把每一个td的值放入数组中
                orderInfo.push(value.innerText);
            });
            //用";"将数组转换为字符串，并存入自己对应的input中
            orderInfo = orderInfo.join(";");
            $(this).parent().parent().prev().val(orderInfo);
        }else{
            //清空隐藏的input的值
            $(this).parent().parent().prev().val("");
        }
    });


    //全选
    $("body").delegate(".checkall",'click',function () {
        //全选选中
        if($(this).prop("checked")) {
            //下面所有的复选框选中
            $(".checkedres").prop("checked", true);//全选
            //遍历所有的复选框
            $.each($(".checkedres"),function () {
                //得到tr下的所有td
                var tds = $(this).parent().nextAll();
                var orderInfo = [];
                //遍历得到的td
                $.each(tds, function(key,value){
                    //把每一个td的值放入数组中
                    orderInfo.push(value.innerText);
                });
                //用";"将数组转换为字符串，并存入自己对应的input中
                orderInfo = orderInfo.join(";");
                $(this).parent().parent().prev().val(orderInfo);
            });
        }
        //取消全选
        else{
            //下面所有的复选框取消选中
            $(".checkedres").prop("checked", false);//全选
            //遍历所有的复选框
            $.each($(".checkedres"),function () {
                //清空隐藏的input的值
                $(this).parent().parent().prev().val("");
            });
        }
    });

    //订单发货统计导出
    $("body").delegate("#export",'click',function () {
        // var orderInfos = [];
        // var cate = $(this).attr('data-value');
        // $(".export").each(function () {
        //     if($(this).val()!=""){
        //         var orderInfo = $(this).val().split(";");
        //         orderInfos.push(orderInfo);
        //     }
        // });
        // if(orderInfos.length==0){
        //     layer.msg('请选择要导出的数据');
        //     return;
        // }
        // var cate = $(this).attr('data-value');
        // console.log(cate);return;
        var that = $(this);
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }

        layer.confirm('请确定该时间段内有数据', {
            btn: ['确定','取消'] //按钮
        }, function(index){
            var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
            var cate = that.attr('data-value');
            switch (cate) {
                case 'orders' :
                    location.href = "/finance/orders/ordersExport?info="+obj;
                    layer.close(index);
                    break;
                case 'goods' :
                    location.href = "/finance/goods/goodsExport?info="+obj;
                    layer.close(index);
                    break;
                case 'areas' :
                    location.href = "/finance/areas/areasExport?info="+obj;
                    layer.close(index);
                    break;
                case 'logistics' :
                    location.href = "/finance/logistics/logisticsExport?info="+obj;
                    layer.close(index);
                    break;
                case 'logisticsDetail' :
                    location.href = "/finance/logisticsDetail/logisticsDetailExport?info="+obj;
                    layer.close(index);
                    break;
                default : layer.msg('点击无效');break;

            }

            // location.href = "/statistics/goods/export?info="+obj;
            // layer.close(index);
        }, function(){
        });



    });



})