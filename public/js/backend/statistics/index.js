$(function () {

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

    //商品统计导出
    $("#export").click(function () {
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        layer.confirm('请确定该时间段内有数据', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
                location.href = "/statistics/goods/export?info="+obj;
                layer.close(index);
            }, function(){
        });
    });

    //订单发货统计导出
    $("#order_export").click(function () {
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        layer.confirm('请确定该时间段内有数据', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
                location.href = "/statistics/orders/export?info="+obj;
                layer.close(index);
            }, function(){
        });
    });

    //物流对账统计导出
    $("#express_export").click(function () {
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        layer.confirm('请确定该时间段内有数据', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
                location.href = "/statistics/express/export?info="+obj;
                layer.close(index);
            }, function(){
        });
    });

    //利润统计统计导出
    $("#profit_export").click(function () {
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        layer.confirm('请确定该时间段内有数据', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
                location.href = "/statistics/profit/export?info="+obj;
                layer.close(index);
            }, function(){
        });
    });

    //销售成本统计统计导出
    $("#costs_export").click(function () {
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        layer.confirm('请确定该时间段内有数据', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
                location.href = "/statistics/costs/export?info="+obj;
                layer.close(index);
            }, function(){
        });
    });


    //交货率统计导出
    $("body").delegate("#consign_export",'click',function () {
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        layer.confirm('请确定该时间段内有数据', {
                btn: ['确定','取消'] //按钮
            }, function(index){
                var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
                location.href = "/statistics/consignment/export?info="+obj;
                layer.close(index);
            }, function(){
        });
    });

    //模板导出按钮
    $("#temp_export").click(function () {

        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);

        var str = JSON.stringify(obj);
        window.location.href = '/statistics/temp/export/'+str;
    });

    //导入物流成本 start
    $("#express_import").click(function () {
        $("#excel_upload").click();
    });

    $("#excel_upload").change(function () {
        var files = this.files;

        var index = layer.msg('数据导入中...', {
            icon: 16,
            shade: 0.2,
            time: 10000 //10秒关闭
        });

        for(var i = 0;i<files.length;i++){
            fd = new FormData();
            var file = files[i];
            fd.append('file',file);
            fd.append('_token',$("input[name='_token']").val());

            $("#excel_upload").val('');
            $.ajax({
                url: "/statistics/express/import",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function (ret) {
                    layer.close(index);
                    if(ret.status==200 && ret.success=='true'){
                        layer.msg(ret.data, {icon: 6,time: 5000});

                    }else if(ret.status==404 && ret.success=='false'){
                        layer.msg(ret.data, {icon: 5});
                    }
                },
                error:function () {
                    layer.close(index);
                    layer.msg("程序出现错误!", {icon: 5});
                }
            });
        }
    });
    //导入物流成本 end



    //分销管理 推广订单js操作 start
    $("body").delegate("#inviter_export",'click',function () {
        if($("#reservationtime").val()==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        layer.confirm('请确定该时间段内有数据', {
            btn: ['确定','取消'] //按钮
        }, function(index){
            var obj = encodeURIComponent(JSON.stringify(getFormData($("form#search-form"))));
            location.href = "/agent/inviter/export?info="+obj;
            layer.close(index);
        }, function(){
        });
    });

    //分销管理 推广订单js操作 end



});
