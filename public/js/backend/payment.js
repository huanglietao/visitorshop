var paySelect;
var fileupload;
$(function(){
    'use strict';

    paySelect = function(obj){
        var name = "";
        //OMS系统支付配置
        if($("body").find("#pay_name").length >0){
            //获取选中支付方式的id
            var pay_id =  $(obj).find("option:selected").val();
            //获取选中支付方式的名称
            name =  $("#pay_name").find("option:selected").text();
            //当选择支付方式时，显示官方数据的选择
            if(pay_id!=0){
                $("#names").val(name);
            }
        }
        //CMS系统支付配置
        else{
            //获取选中支付方式的名称
            name = $("#names").val();
        }

        //显示对应支付方式的参数配置
        if(name.indexOf('支付宝')!=-1){
            //对应的参数配置添加必填规则
            $("#pid").attr("data-rule",'合作者身份:required');
            $("#seller_id").attr("data-rule",'卖家账号:required');
            $("#key").attr("data-rule",'KEY:required');
            //不对应的参数配置删除必填规则
            $("#wxpay input").removeAttr("data-rule");
            //显示对应的参数配置
            $("#class_name").val('alipay');
            $('#alipay').show();
            $('#wxpay').hide();
        }
        else if(name.indexOf('微信')!=-1){
            //对应的参数配置添加必填规则
            $("#appid").attr("data-rule",'appid:required');
            $("#mchid").attr("data-rule",'商户号:required');
            $("#wekey").attr("data-rule",'商户支付密钥:required');
            $("#appsecret").attr("data-rule",'公众账号:required');
            //不对应的参数配置删除必填规则
            $("#alipay input").removeAttr("data-rule");
            //显示对应的参数配置
            $("#class_name").val('wxpay');
            $('#alipay').hide();
            $('#wxpay').show();
        }
        else{
            //不对应的参数配置删除必填规则
            $("#alipay input").removeAttr("data-rule");
            $("#wxpay input").removeAttr("data-rule");
            //显示对应的参数配置
            $("#class_name").val('balance');
            $('#alipay').hide();
            $('#wxpay').hide();
        }

    };

    //进入编辑页面时判断该显示的数据
    $("body").delegate('#edit','click',function () {
        //延时执行
        setTimeout(function () {
            //根据页面是否有id值，显示支付方式对应的参数配置
            if($("body").find("#id").length >0 && $("#id").val()){
                var class_name = $("#class_name").val();
                if(class_name=="alipay"){
                    //对应的参数配置添加必填规则
                    $("#pid").attr("data-rule",'合作者身份:required');
                    $("#seller_id").attr("data-rule",'卖家账号:required');
                    $("#key").attr("data-rule",'KEY:required');
                    //显示对应的参数配置
                    $("#alipay").show();
                    $("#wxpay").hide();
                }
                if(class_name=="wxpay"){
                    //对应的参数配置添加必填规则
                    $("#appid").attr("data-rule",'appid:required');
                    $("#mchid").attr("data-rule",'商户号:required');
                    $("#wekey").attr("data-rule",'商户支付密钥:required');
                    $("#appsecret").attr("data-rule",'公众账号:required');
                    //显示对应的参数配置
                    $("#alipay").hide();
                    $("#wxpay").show();
                }
            }
        },300);
    });


    // //进入编辑页面时显示对应数据
    // $('#main').delegate("#edit",'click',function () {
    //     var url = $(this).attr("data-url");
    //     $.ajax({
    //         url : url,
    //         type: 'GET',
    //         dataType : 'JSON',
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //         },
    //         success : function(){
    //             var class_name = $("#class_name").val();
    //             console.log(class_name);
    //             if(class_name==='alipay'){
    //                 $("#pid").attr("data-rule",'PID:required');
    //                 $("#seller_id").attr("data-rule",'卖家账号:required');
    //                 $("#key").attr("data-rule",'key:required');
    //                 $("#appid").removeAttr("data-rule",'appid:required');
    //                 $("#mchid").removeAttr("data-rule",'mchid:required');
    //                 $("#wekey").removeAttr("data-rule",'wekey:required');
    //                 $("#appsecret").removeAttr("data-rule",'appsecret:required');
    //                 $('#alipay').show();
    //                 $('#wxpay').hide();
    //
    //             }
    //             else if(class_name==='wxpay'){
    //                 $("#appid").attr("data-rule",'appid:required');
    //                 $("#mchid").attr("data-rule",'mchid:required');
    //                 $("#wekey").attr("data-rule",'wekey:required');
    //                 $("#appsecret").attr("data-rule",'appsecret:required');
    //                 $("#pid").removeAttr("data-rule",'PID:required');
    //                 $("#seller_id").removeAttr("data-rule",'卖家账号:required');
    //                 $("#key").removeAttr("data-rule",'key:required');
    //                 $('#alipay').hide();
    //                 $('#wxpay').show();
    //
    //             }
    //             else{
    //                 $("#pid").removeAttr("data-rule",'PID:required');
    //                 $("#seller_id").removeAttr("data-rule",'卖家账号:required');
    //                 $("#key").removeAttr("data-rule",'key:required');
    //                 $("#appid").removeAttr("data-rule",'appid:required');
    //                 $("#mchid").removeAttr("data-rule",'mchid:required');
    //                 $("#wekey").removeAttr("data-rule",'wekey:required');
    //                 $("#appsecret").removeAttr("data-rule",'appsecret:required');
    //                 $('#alipay').hide();
    //                 $('#wxpay').hide();
    //             }
    //         }
    //     });
    // });

    //微信支付方式，公钥和密钥文件上传按钮的点击
    $("body").delegate('#upload_file_btn','click',function () {
        var num = $(this).attr("data-value");
        if(num==="1"){
            $("#sslcert_path").click();
        }
        else if(num==="2"){
            $("#sslkey_path").click();
        }
    });

    //微信支付方式，公钥和密钥文件上传
    fileupload=function(obj,num) {
        var files = obj.files;
        for(var i = 0;i<files.length;i++){
            var fd = new FormData();
            var file = files[i];
            fd.append('file',file);

            $.ajax({
                url : "/ajax/upload",
                type: 'POST',
                data:fd,
                contentType:false,
                processData:false,
                dataType : 'text',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success : function(data){
                    if(num==1){
                        $("#sslcert_file").text(file.name);
                        $("#sslcert_file").next().show();
                        $(".sslcert_path").val(data);
                    }
                    if(num==2){
                        $("#sslkey_file").text(file.name);
                        $("#sslkey_file").next().show();
                        $(".sslkey_path").val(data);
                    }
                },
                error : function(){
                    alert('文件上传出错');
                }
            });
        }
    };

    //微信支付方式，公钥和密钥上传的文件删除
    $("body").delegate('#del_file_upload','click',function () {
        var path = $(this).next().val();
        var self = $(this);

        $.ajax({
            url : "/ajax/del",
            type: 'POST',
            data:{
                path:path,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if(data.status == 'error'){
                    alert(data.msg);
                }else{
                    self.prev().text("未选择任何文件");
                    self.next().val("");
                    self.hide();
                }
            },
            error : function(){
                alert('文件删除出错');
            }
        });
    });






});