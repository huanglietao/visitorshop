/**
 * Created by hlt on 2020/6/8.
 */
$(function(){
    $('#kw').focus();
    doConnect();
    $('#kw').bind('input propertychange', function() {
        check($(this).val());
    });
});
var socket;
var printers;
var defaultPrinter;
var printTaskId;
var taskers;
var waybillPrintStatus;
var waybillNO = '';
var printData;
function doPrint(is_old,type='YTO')
{
    var key = $('#kw').val();
    var ctype = type;
    $.ajax({
        type: 'POST',
        url: '/printscom/get-print-data',

        data: {key:key,ctype:ctype,is_old:is_old},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        beforeSend: function() {
            $('#content .error').hide();
            layer.load(2);
        },
        error:function(){
            layer.load(2);
        },
        complete:function() {
            layer.closeAll('loading');
        },
        success: function(data) {
            var res = $.parseJSON(data);
            if(res.status == '0'){
                socket.send(JSON.stringify(res.content));
            }else if (res.status == '3') {

                //请求打印
                $.ajax({
                    type: 'POST',
                    url: res.content.reqURL,
                    data: res.content.post_json_data,
                    contentType: "application/json",
                    /*headers: {
                     'Content-Type': 'application/json'
                     },*/
                    beforeSend: function() {
                        var index = layer.msg('打印中', {
                            icon: 16,shade: 0.1, time: 40000
                        });
                    },
                    error:function(e){
                        layer.msg('打印失败', {icon: 'none'});
                        layer.closeAll('loading');
                    },
                    success: function(data) {
                        if (data.code=='SYS_CODE_QIAO_0200'){
                            layer.msg('打印成功', {icon: 1});
                            console.log(res.content.taskID);
                            delivery(res.content.taskID);
                        }else{
                            $('#content .error').show().html("顺丰面单打印出错");
                        }
                    },
                });
            }else{
                $('#content .error').show().html(res.msg);
            }
            layer.closeAll('loading');
        },
    });
}
function doConnect()
{
    socket = new WebSocket('ws://127.0.0.1:13528');
    socket.onopen = function(event)
    {
//                 alert("Websocket准备就绪,连接到客户端成功");
    };
    // 监听消息
    socket.onmessage = function(event)
    {
        console.log('Client received a message',event);
        var data = JSON.parse(event.data);
        if ("getPrinters" == data.cmd) {
            alert('打印机列表:' + JSON.stringify(data.printers));

            defaultPrinter = data.defaultPrinter;
            printData.task.printer = defaultPrinter;
            alert('默认打印机为:' + defaultPrinter);
        } else if("notifyPrintResult" == data.cmd){
            if("printed" == data.taskStatus){
                layer.close(index);
                layer.msg('打印成功', {icon: 1});
                delivery(data.taskID);
            }
        }else if("print" == data.cmd){
            var index = layer.msg('打印中', {
                icon: 16,shade: 0.1, time: 40000
            });
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
function submit(){
    var key = $('#kw').val();
    $('#list').html('');
    check(key);
}

function delivery(taskid){
    $.ajax({
        type: 'POST',
        url: '/printscom/delivery',
        data: {taskid:taskid},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        beforeSend: function() {
        },
        error:function(){
        },
        complete:function() {
        },
        success: function(data) {
            var res = $.parseJSON(data);
            $('#kw').val("").focus();
//     	    	submit();
     	    	layer.msg(res.msg);
        }
    });
}


function printLabel(content) {

    var count = LODOP.GET_PRINTER_COUNT();
    //获取打印机名称
    var index = -1;
    for(var i=0; i<count; i++) {
        console.log(LODOP.GET_PRINTER_NAME(i));
        if(LODOP.GET_PRINTER_NAME(i) == 'NPI22580B (HP LaserJet Professional M1216nfh MFP)') {
            index = i;
        }
    }

    if(index != -1) {
        LODOP.SET_PRINTER_INDEX(index);

        LODOP.SET_PRINT_PAGESIZE(1, 0, "", "A4"); //设置纸张
        LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", true); //设置以纸张边缘为基点
        LODOP.SET_PRINT_STYLE("FontSize", 13); //设置字体
        LODOP.SET_PRINT_STYLEA(0,"Horient",2);
        LODOP.SET_PRINT_STYLEA(0,"Vorient",2);

        LODOP.ADD_PRINT_HTM(40, '20%', "100%", "BottomMargin:3mm", content);
        //LODOP.PREVIEW();//打印预览
        LODOP.PRINT();
    }

}

function check(key,stocked = 0){
    var pattern = /^(\d){18}_(\d){1,3}_(\d){1,3}_(\d){1,3}$/;
    var patterntt = /^(\d){16}_(\d){1,3}_(\d){1,3}_(\d){1,3}$/;
    var patternt = /^(\d){13,25}_(\d){1,3}_(\d){1,3}_(\d){1,3}$/;
    var patternttt = /^(\d){13,25}-(\d){1,3}-(\d){1,3}(-)?(\d){0,3}$/;
    var username = $('#username').val();
    //考虑供货商情况
    var sp_id = $("#sp_id").val();

    if(pattern.test(key) || patternt.test(key) || patterntt.test(key)|| patternttt.test(key)){
        $('#reprint_SF').hide();
        $('#reprint_YTO').hide();
        $('#reprint_YUNDA').hide();
        $('#reprint1').hide();
        $('.succ').hide();
        $('.warning').hide();
        $("#order_id").val(key);
        $.ajax({
            type: 'POST',
            url: '/print/check',
            data: {key:key,stocked:stocked,username:username,sp_id:sp_id},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function() {
                $('#content .error').hide();
                layer.load(2);
            },
            error:function(){
                layer.load(2);
            },
            complete:function() {
                layer.closeAll('loading');
            },
            success: function(data) {
                var res = $.parseJSON(data);
                if(res.status == '0'){
                    $('#list').html(res.content.list);

                    if(res.content.print == '1' && res.content.has_print =='0'){
                        if(res.content.can_print == '1'){doPrint(0,res.content.type);$('#reprint_'+res.content.type).show();}else{$('#kw').val("").focus();}
                        $('.succ').show();
                        $('.warning').hide();
                    }
                    if(res.content.print == '0' && res.content.has_print =='1'){
                        if(res.content.can_print == '1'){$('#reprint_'+res.content.type).show();}else{$('#kw').val("").focus();}
                        $('.succ').show();
                        $('.warning').hide();
                    }
                    if(res.content.print == '0' && res.content.has_print =='0'){
                        $('.warning').show();
                        $('.succ').hide();
                        $('#kw').val("").focus();
                    }

                    if(res.content.works_tags != '') {
                        setTimeout("printLabel('"+res.content.works_tags+"')","500");
                    }
                }else if(res.status == '2'){
                    layer.confirm('当前订单需要备货'+ res.msg+'件', {
                        btn: ['确认已备货','取消'] //按钮
                    }, function(){
                        layer.closeAll();
                        check(key,1);
                    }, function(){
                        $('#kw').val("").focus();
                    });
                }else{
                    $('#content .error').show().html(res.msg);
                    $('#list').html('');
                    $('#kw').val("").focus();
                }

                layer.closeAll('loading');

            }
        });
    }else{
        $('#content .error').show().html('请输入正确订单号');
        $('#reprint_SF').hide();
        $('#reprint_YTO').hide();
        $('#reprint_YUNDA').hide();
        $('#reprint1').hide();
        $('.succ').hide();
        $('.warning').hide();


    }
}