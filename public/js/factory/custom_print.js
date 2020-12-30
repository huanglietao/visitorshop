var upload;
$(function () {
    var select_arr = [];
    var select_arr_code = [];
    var print_length = 0;
    var totla_length = 0;
    var fail_length = 0;
    var is_new = 0;
    doConnect();
    $(".btn-refresh").click(function () {
        select_all = 0;
        select_arr = [];
        select_arr_code = [];
    });

    //全选
    var select_all = 0;
    $(".btn-all").click(function () {
        if(select_all == 0){
            $("input[type='checkbox'][name='checkedres']").prop("checked", true)
            select_all = 1;
            $("input[type='checkbox'][name='checkedres']:checked").each(function(i){
                select_arr[i] =$(this).attr("data-value");
                select_arr_code[i] =$(this).attr("data-code");
            });
        }else{
            $("input[type='checkbox'][name='checkedres']").prop("checked", false)
            select_all = 0;
            select_arr = [];
            select_arr_code = [];
        }
    })

    //点击checkbox
    $(".tbl-content").delegate(".checkedres","click",function () {
        var value = $(this).attr("data-value")
        var code = $(this).attr("data-code")
        if($(this).prop("checked")){
            select_arr.push(value)
            select_arr_code.push(code)
        }else{
            select_arr.splice($.inArray(value,select_arr),1)
            select_arr_code.splice($.inArray(code,select_arr_code),1)
        }


        if (!$(this).checked) {
            $(".btn-all").prop("checked", false);
        }
        var chsub = $("input[type='checkbox'][name='checkedres']").length; //获取subcheck的个数

        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数

        if (checkedsub == chsub) {
            $(".btn-all").prop("checked", true);
        }else{
            $(".btn-all").prop("checked", false);
        }
    })

    function doConnect()
    {
        socket = new WebSocket('ws://127.0.0.1:13528');
        socket.onopen = function(event)
        {
            // alert("Websocket准备就绪,连接到客户端成功");
        };
        // 监听消息
        socket.onmessage = function(event)
        {
            // console.log('Client received a message',event);
            var data = JSON.parse(event.data);
            if ("getPrinters" == data.cmd) {
                alert('打印机列表:' + JSON.stringify(data.printers));

                defaultPrinter = data.defaultPrinter;
                printData.task.printer = defaultPrinter;
                alert('默认打印机为:' + defaultPrinter);
            } else if("notifyPrintResult" == data.cmd){
                if("printed" == data.taskStatus){
                    // layer.close(index);
                    // layer.msg('打印成功', {icon: 1});
                    console.log(data.taskID);
                    writeBack(data.taskID);
                    // console.log('触发发货')
                }
            }else if("print" == data.cmd){
                // var index = layer.msg('打印中', {
                //     icon: 16,shade: 0.1, time: 40000
                // });
            }else{
                console.log("返回数据:" + JSON.stringify(data));
            }
        };

        // 监听Socket的关闭
        socket.onclose = function(event)
        {
            console.log('Client notified socket has closed',event);
        };

        socket.onerror = function(event) {
            alert('无法连接到:' + printer_address);
        };
    }

    //批量打单
    $(".btn-print-all").click(function () {
        if($(this).hasClass("btn-print-new")){
            is_new = 1;
        }else{
            is_new = 0;
        }
        if(select_arr.length < 1){
            layer.msg("请选择需要打印的订单")
        }else{
            $(this).addClass("btn-disabled")
            $(".btn-print").addClass("btn-disabled")
            $(".btn-clear").addClass("btn-disabled")

            print_length = select_arr.length
            totla_length = print_length

            tips(print_length,0,0,print_length);
            //获取要打的快递
            var express_code = $('.express').val();
            express_code=express_code.toUpperCase()

            for (var index in select_arr){
                printFace(select_arr[index],express_code,is_new)
            }
        }
    });






    //打单tips
    function tips(total,success,fail,surplus) {
        if(surplus == 0){
            // var name = $(".del-btn").parents(".layui-layer").attr("times");
            // //先得到当前iframe层的索引
            // layer.close(name);
            layer.closeAll()
        }
        $.ajax({
            url : "/custom_print/tips",
            type: 'POST',
            data:{
                success:success,
                total:total,
                fail:fail,
                surplus:surplus
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if(data['layre_flag'] != 0){
                    layer.open({
                        type:1,
                        title:false,
                        closeBtn: 0,
                        resize : false,
                        shade:0.1,
                        area:['600px','183px'],
                        skin:"success-skin",
                        content: data.html,
                        zIndex: layer.zIndex,
                        success: function(layero, index){
                            // console.log(layero)
                            // console.log(index)
                        }
                    });
                }else{
                    $(".new-text").html('(共'+data['html']['total']+'，成功'+data['html']['success']+'，失败'+data['html']['fail']+'，剩余'+data['html']['surplus']+')')
                }

            },
        });
    }


    function printFace(print_id,delivery_type,is_new) {
        $.ajax({
            type: 'POST',
            url: '/custom_print/printData',
            dataType : 'json',
            data: {print_id:print_id,delivery_type:delivery_type,is_new:is_new},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function() {
                // var index = layer.msg('打印中...');
            },
            error:function(res){
                console.log(res)
                fail_length++
                print_length--
                // console.log('total:'+totla_length+'--success:'+(totla_length-fail_length-print_length)+'--fail:'+fail_length+'--surplus'+print_length)
                tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
            },
            complete:function() {
                // layer.closeAll('loading');
            },
            success: function(data) {
                // console.log(data)
                // var res = $.parseJSON(data);
                // console.log(data['status'])

                if(data['status'] == '0') {
                    if(delivery_type == 'SF'){
                        //顺丰面单
                        // console.log(data)
                        // console.log(data.content)
                        requestSF(data.msg.reqURL,data.msg.post_json_data,data.msg.taskID)
                    }else{
                        //菜鸟面单
                        socket.send(JSON.stringify(data.msg));
                    }
                }else if(data['status'] == '1'){
                    alert(data.msg);
                    //异常提醒
                    // console.log('异常提醒')
                    fail_length++
                    print_length--
                    // console.log('total:'+totla_length+'--success:'+(totla_length-fail_length-print_length)+'--fail:'+fail_length+'--surplus'+print_length)
                    tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
                }
                layer.closeAll('loading');
            },
        });
    }

    //ajax请求顺丰打单接口
    function requestSF(url,post_data,taskid) {
        $.ajax({
            type: 'POST',
            url: url,
            data: post_data,
            contentType: "application/json",
            error:function(res){
                fail_length++
                print_length--
                tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
            },
            success: function(data) {
                if(data.code == 'SYS_CODE_QIAO_0200'){
                    //打单成功，通知发货
                    writeBack(taskid)
                }else{
                    //面单打印出错
                    fail_length++
                    print_length--
                    tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
                }

            }
        });
    }

    //回写单号跟物流
    function writeBack(taskid){
        $.ajax({
            type: 'POST',
            url: '/custom_print/writeBack',
            data: {taskid:taskid},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function() {
            },
            error:function(res){
                console.log(res)
                fail_length++
                print_length--
                // console.log('total:'+totla_length+'--success:'+(totla_length-fail_length-print_length)+'--fail:'+fail_length+'--surplus'+print_length)
                tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
            },
            complete:function() {
            },
            success: function(data) {
                var res = $.parseJSON(data);
                print_length = print_length-1;
                if(res['status'] == 1){
                    fail_length ++
                }
                tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
            }
        });
    }


    //tips中按钮事件
    $("body").delegate("#sure-comfirm,#del-cancel","click",function () {
        // var name = $("#sure-comfirm").parents(".layui-layer").attr("times");
        // //先得到当前iframe层的索引
        layer.closeAll();
        window.location.reload();//刷新当前页面.

    });

    //导入
    $("body").delegate("#excel_btn",'click',function () {
        $("#excel_upload").click();
    });

    upload = function (obj){
        var files = obj.files;
        layer.alert('数据导入中，请勿关闭', {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
        });
        for(var i = 0;i<files.length;i++){
            var fd = new FormData();
            var file = files[i];
            fd.append('file',file);
            fd.append('_token',$('meta[name="_token"]').attr('content'));

            $("#excel_upload").val('');
            $.ajax({
                url: "/custom_print/import",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function (ret) {
                    if(ret.status==200 && ret.success=='true'){
                        layer.msg(ret.data);
                        setTimeout(function () {
                            window.location.reload();//刷新当前页面.
                        },500);
                    }else if(ret.status==404 && ret.success=='false'){
                        layer.msg(ret.data);
                    }
                },
                error:function () {
                    layer.msg("程序出现错误!");
                }
            });
        }
    };


    //导出
    //订单发货统计导出
    $("body").delegate("#export",'click',function () {
        var finish_time = $("#reservationtime").val();
        if(finish_time==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        var time = finish_time.split(" - ");

        var sDate = new Date(time[0]).getTime();
        var eDate = new Date(time[1]).getTime();

        var thisMothDays = 1000 * 3600 * 24 * 31;

            var obj = JSON.stringify(getFormData($("form#search-form")));
            // var obj = JSON.parse(tj);
            location.href = "/custom_print/print/export?info="+obj;
    });

    //修改收件或寄件信息
    $("body").delegate(".pri_edit",'click',function () {
        console.log($(this).attr("data-value"));
        console.log($(this).attr("data-type"));
        //获取修改的类型



    })
});
