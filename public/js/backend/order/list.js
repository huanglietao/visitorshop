$(function(){
    'use strict'

    //tab 订单状态标签切换数据
    $(".works_tab").click(function () {
        var status = $(this).attr("data-val");
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        $(".tab_val").val(status)

        obj.limit = $("#pages-limit").val();
        obj.status = status;
        loadTable(obj);
    });

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

    //导出按钮
    $(".btn-order-export").click(function () {
        var status = $('.tab_val').val();
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);

        obj.status = status;
        if(obj['created_at'] == ''){
            layerMessage('请在搜索框中创建时间项筛选订单导出');
            return false
        }

        var create_time = obj['created_at'].split(" - ")
        var start_time = create_time[0]
        var end_time = create_time[1]
        var differ_date = Math.floor((new Date(end_time).getTime() - new Date(start_time).getTime())/(24*3600*1000))
        if(differ_date < 0){
            layerMessage('请选择正确的时间区间')
            return false
        }else if(differ_date > 30)
        {
            layerMessage('只能导出一个月内的订单')
            return false
        }

        delete(obj['chanel'])
        delete(obj['delivery_code'])
        delete(obj['order_no'])
        delete(obj['order_rcv_phone'])
        delete(obj['order_rcv_user'])
        delete(obj['order_relation_no'])

        var str = JSON.stringify(obj);
        window.location.href = '/order/list/export/'+str
    })

    function layerMessage($msg) {
        layer.msg('',{
            title: false,
            content: $msg,
            closeBtn:0,
            offset: 'auto',
            icon:5,
            zIndex: layer.zIndex, //重点1
            success: function(layero){
                layer.setTop(layero); //重点2
            }
        });
    }

    //订单详情js start

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

    //订单详情js end


    //订单售后 start

    //服务类型
    $("body").delegate(".ao_service_type","click",function () {
        if ($(this).hasClass("refund"))
        {
            $(".ao_refund_address").show();
        }else{
            $(".ao_refund_address").hide();
        }
    })

    //返回售后列表
    $(".od_return_service").click(function () {
        location.href = "/order/service"
    });

    //选择换货按钮触发
    $('input:radio[name="handle_type"]').click(function(){
        switch ($(this).val()){
            case '1':
                //协商优惠
                $(".amount_inp").addClass('handle_type')
                $(".return_inp").addClass('handle_type')
                $(".discount_inp").removeClass("handle_type")
                $(".other").addClass("handle_type")
                $(".btn-handle").removeAttr("style")
                $(".btn-review").removeAttr("disabled")

                break;
            case '2':
                //仅退款
                $(".discount_inp").addClass('handle_type')
                $(".return_inp").addClass('handle_type')
                $(".amount_inp").removeClass("handle_type")
                $(".other").addClass("handle_type")
                $(".amount_inp").val($(".refund_amount").val())
                $(".btn-handle").removeAttr("style")
                $(".btn-review").removeAttr("disabled")

                break;
            case '3':
                //退货退款
                $(".discount_inp").addClass('handle_type')
                $(".amount_inp").addClass('handle_type')
                $(".return_inp").removeClass("handle_type")
                $(".other").addClass("handle_type")
                $(".btn-handle").removeAttr("style")
                $(".btn-review").removeAttr("disabled")

                break;
            case '4':
                //换货
                $(".discount_inp").addClass('handle_type')
                $(".amount_inp").addClass('handle_type')
                $(".return_inp").addClass("handle_type")
                $(".other").addClass("handle_type")
                $(".btn-handle").attr("style","display:none")
                $(".btn-review").attr("disabled","disabled")
                $(".add_exchange").click();

                break;
            case '5':
                //其它
                $(".discount_inp").addClass('handle_type')
                $(".amount_inp").addClass('handle_type')
                $(".return_inp").addClass("handle_type")
                $(".other").removeClass("handle_type")
                $(".btn-handle").removeAttr("style")
                $(".btn-review").removeAttr("disabled")

                break;
        }
    });

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

    //售后处理form提交
    $(".btn-handle").click(function () {
        var job_responsibility = $('.responsibility option:selected') .val()
        var job_handle = $('input[name="handle_type"]:checked').val()
        var job_handel_voucher = $("input[name='job_handel_voucher']").val()
        var job_id = $(".job_id").val()
        var input = ''

        if(job_handle == 1){
            input = $(".discount_inp").val()
        }else if(job_handle == 2){
            input = $(".amount_inp").val()
        }else if(job_handle == 3){
            input = $(".return_inp").val()
        }else if(job_handle == 4){
            return false;
        }else{
            input = $(".job_remarks").val()
        }

        $.ajax({
            url : '/order/service/handle/'+job_id,
            type : 'POST',
            data : {
                'job_responsibility':job_responsibility,
                'job_handle':job_handle,
                'job_handel_voucher':job_handel_voucher,
                'input':input,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data.success == 'true'){
                    tip_success('/order/service',data.data,'',2)
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

    //审核归档
    $(".btn-review").click(function () {
        var job_responsibility = $('.responsibility option:selected') .val()
        var job_handle = $('input[name="handle_type"]:checked').val()
        var job_handel_voucher = $("input[name='job_handel_voucher']").val()
        var job_id = $(".job_id").val()
        var input = ''

        if(job_responsibility == '' || job_responsibility == undefined){
            //售后单详情页归档时
            job_responsibility = $("#job_responsibility").val()
        }

        if(job_handle == '' || job_handle == undefined){
            //售后单详情页归档时
            job_handle = $("#job_handle").val()
        }

        if(job_handle == 1){
            input = $(".discount_inp").val()
        }else if(job_handle == 2){
            input = $(".amount_inp").val()
        }else if(job_handle == 3){
            input = $(".return_inp").val()
        }else if(job_handle == 4){
            return false;
        }else{
            input = $(".job_remarks").val()
        }

        $.ajax({
            url : '/order/service/review',
            type : 'POST',
            data : {
                'job_id':job_id,
                'job_responsibility':job_responsibility,
                'job_handel_voucher':job_handel_voucher,
                'job_handle':job_handle,
                'input':input,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data.success == 'true'){
                    tip_success('/order/service',data.data,'',2)
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

    //订单售后 end

})