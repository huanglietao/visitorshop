$(function(){
    'use strict';

    $(document).ready(function () {
        //加入加载标示
        loadtable('1');

    });

    $(".nav_operate_tab div").click(function () {
        var cate_id = $(this).attr("data-val");
        loadtable(cate_id);
    });

    function loadtable(cate_id,search) {
        $('.statistics_loading').html("");
        var html = '<div class="new_loading" style="text-align: center;margin-top:10px;position: absolute;z-index: 2;left: 0;right: 0;margin-left: auto;margin-right: auto;"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>';
        $('.statistics_loading').append(html);
        var url ="";
        //对应模块视图地址
        switch (cate_id) {
            case '1' :
                url = "/system/basic_info";
                break;
            case '2' :
                url = "/system/pwd_management";
                break;

            default : url = "/system/basic_info";break;

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

                    //判断弹窗中是否含有省市区组件
                    if($("body").find(".areas-province").length > 0){
                        var self = $("select[name='province']");
                        var province_value = $(".areas-province").attr('data-value')

                        //填充省份option
                        $.ajax({
                            url : '/ajax',
                            type: 'POST',
                            data:{
                                id:0,
                            },
                            dataType : 'JSON',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success : function(data){
                                var provinces_html = '';
                                for(var i=0; i<data.list.length; i++){
                                    if(province_value == data.list[i]['area_id']){
                                        provinces_html += "<option selected value='"+data.list[i]['area_id']+"'>"+data.list[i]['area_name']+"</option>";
                                    }else{
                                        provinces_html += "<option value='"+data.list[i]['area_id']+"'>"+data.list[i]['area_name']+"</option>";
                                    }
                                }
                                self.append(provinces_html);
                            },
                            error : function(){
                                console.log("error");
                            }
                        });

                        //省份有值则自动填充市和区
                        if(province_value != ''&& province_value != 0){
                            var city_value = $(".areas-city").attr("data-value")

                            //填充市option
                            $.ajax({
                                url : '/ajax',
                                type: 'POST',
                                data:{
                                    id:province_value,
                                },
                                dataType : 'JSON',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                },
                                success : function(data){
                                    var city_html = '<option value="-1">市</option>';
                                    if(province_value != 0){
                                        for(var j=0; j<data.list.length; j++){
                                            if(city_value == data.list[j]['area_id']){
                                                city_html += "<option selected value='"+data.list[j]['area_id']+"'>"+data.list[j]['area_name']+"</option>";
                                            }else{
                                                city_html += "<option value='"+data.list[j]['area_id']+"'>"+data.list[j]['area_name']+"</option>";
                                            }
                                        }
                                    }

                                    self.next().html('');
                                    self.next().append(city_html);
                                },
                                error : function(){
                                    console.log("error");
                                }
                            });

                            //填充区option
                            $.ajax({
                                url : '/ajax',
                                type: 'POST',
                                data:{
                                    id:city_value,
                                },
                                dataType : 'JSON',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                },
                                success : function(data){
                                    var areas_html = '<option>区</option>';
                                    var areas_value = $(".areas-area").attr("data-value")

                                    for(var k=0; k<data.list.length; k++){
                                        if(areas_value == data.list[k]['area_id']){
                                            areas_html += "<option selected value='"+data.list[k]['area_id']+"'>"+data.list[k]['area_name']+"</option>";
                                        }else{
                                            areas_html += "<option value='"+data.list[k]['area_id']+"'>"+data.list[k]['area_name']+"</option>";
                                        }
                                    }

                                    self.next().next().html('');
                                    self.next().next().append(areas_html);
                                },
                                error : function(){
                                    console.log("error");
                                }
                            });
                        }
                    }
                }
            },
            error : function(){

            }
        });
    }

    //基本信息表单提交处理
    $("body").delegate("#btn-base",'click',function () {
        var form = $(this).parent().parent().parent();
        form.trigger("validate");
        //没有出现验证错误情况下才提交
        if(form.find('.form-control').hasClass('n-invalid')) {
            return false;
        }
        var that = $(this);
        that.attr("disabled","disabled");

        $.ajax({
            type: 'POST',
            url: '/system/baseSave',
            dataType: "json",
            data: $("#form-base").serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(res){
                console.log(res);
            },
            success: function(res) {
                if(res.success == 'true'){
                    layer.msg('',{
                        title: false,
                        content: '修改信息成功',
                        closeBtn:0,
                        offset: 'auto',
                        icon:1,
                        zIndex: layer.zIndex, //重点1
                        success: function(layero){
                            layer.setTop(layero); //重点2
                            setTimeout(function () {
                                location.reload();
                            },3000);
                        }
                    });
                }else {
                    layer.msg('数据保存出错了，请联系工作人员查看');
                }
            }
        });
    });

    //新登录密码和重复密码验证
    $("body").delegate("#rep_pwd",'blur',function () {
        var rep = $(this).val();

        if(rep!=$("#new_pwd").val()){
            $('#rep_pwd').val("");
            layer.msg("输入的密码与新登录密码不一致");
            return;
        }

    });


    //是否启用支付密码
    $("body").delegate('input[type="radio"][name="payword"]','click',function () {
        $("#old_payword").slideUp(500);
        $("#old_pay_pwd").val("");
        $("#new_pay_pwd").val("");
        $("#new_rep_pwd").val("");
        var radio_val = $(this).val();
        var is_first = $("#first_open").val();
        //已经选中的支付密码状态
        var open_status = $("#open_status").val();
        //开启
        if(radio_val==2){
            $(".pay_isopen").slideDown(500);
            if(is_first!=''){
                $("#old_pay_pwd").attr('data-rule','旧支付密码:required');
                $("#new_pay_pwd").attr('data-rule','新支付密码:required');
                $("#new_rep_pwd").attr('data-rule','重复密码:required');
            }else{
                $("#new_pay_pwd").attr('data-rule','新支付密码:required');
                $("#new_rep_pwd").attr('data-rule','重复密码:required');
            }
        }
        else if(radio_val!=open_status && radio_val==0)
        {
            $(".pay_isopen").slideUp(500);
            $("#new_pay_pwd").removeAttr("data-rule");
            $("#new_rep_pwd").removeAttr("data-rule");
            setTimeout(function () {
                    $("#old_payword").slideDown(200);
                },100
            );

        }
        else{
            $(".pay_isopen").slideUp(500);
            $("#old_pay_pwd").removeAttr("data-rule");
            $("#new_pay_pwd").removeAttr("data-rule");
            $("#new_rep_pwd").removeAttr("data-rule");
        }
    });


    //新支付密码和重复密码验证
    $("body").delegate("#new_rep_pwd",'blur',function () {
        var rep = $(this).val();

        if(rep!=$("#new_pay_pwd").val()){
            $('#new_rep_pwd').val("");
            layer.msg("输入的密码与新支付密码不一致");
            return;
        }

    });

    //获取验证码
    $("body").delegate("#catch_code",'click',function () {
        var mobile = $("#mobile").val();
        $.ajax({
            type: 'POST',
            url: "/system/getCode",
            dataType: "json",
            data:{
                mobile:mobile
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(res) {
                if(res['status'] == 200 && res['success']){
                    $("#sms_code").val(res.data.code);
                    $("#sms_time").val(res.data.time);
                    layer.msg("验证码发送成功，一分钟内有效");
                }else{
                    $("#sms_code").val(res.message.code);
                    $("#sms_time").val(res.message.time);
                    layer.msg(res.message.msg);
                }
            },
            error:function(res){
                layer.msg("操作失败，请稍后重试!");
            }

        });
    });

    //支付验证码确认
    $("body").delegate("#check_sure",'click',function () {
        var code = $("#chCode").val();
        var sms_code = $("#sms_code").val();
        var sms_time = $("#sms_time").val();
        if(code==''){
            layer.msg("请输入验证码");
            return;
        }
        if(code!=sms_code){
            layer.msg("验证码错误,请重新输入");
            return;
        }
        var timeStamp = Date.parse(new Date());
        var seconds = (timeStamp/1000)-sms_time;
        if(seconds>60){
            layer.msg("验证码已过期，请重新获取");
            return;
        }
        $("#yanzheng").val('true');
        $("#chCode").val('');
        layer.closeAll();
        layer.msg('验证通过');
    });

    //获取验证码页面取消按钮
    $("body").delegate("#check_cancle",'click',function () {
        layer.closeAll();
    });


    //密码管理表单提交处理
    $("body").delegate("#btn-pwd",'click',function () {
        //是否开启支付密码的值
        var rad_val = $("input[name='payword']:checked").val();
        //已经选中的支付密码状态
        var open_status = $("#open_status").val();

        //判断是否填写了信息
        if($("#old_pwd").val()=='' && $("#new_pwd").val()=='' && rad_val==open_status){
            layer.msg('请选择要修改的信息');
            return;
        }
        //判断是否需要更改密码
        if($("#old_pwd").val()!='' || $("#new_pwd").val()!=''){
            if($("#old_pwd").val()==''){
                layer.msg('请输入旧登录密码');
                return;
            }
            if($("#new_pwd").val()==''){
                layer.msg('请输入新的登录密码');
                return;
            }
            if($("#rep_pwd").val()==''){
                layer.msg('请输入重复密码');
                return;
            }
        }

        //判断是否是开启过，然后要关闭
        if(rad_val!=open_status && rad_val==0 && $("#old_pay_pwds").val()==''){
            layer.msg('请输入旧支付密码');
            return;
        }

        var form = $(this).parent().parent().parent();
        form.trigger("validate");
        //没有出现验证错误情况下才提交
        if(form.find('.form-control').hasClass('n-invalid')) {
            return false;
        }
        var that = $(this);

        //当前选中的支付密码的状态
        var status = $("input[name='payword']:checked").val();

        if($("#yanzheng").val()=='false'){
            //判断是否更改了支付密码的设置
            if(status!=open_status){
                layer.open({
                    type: 1,
                    skin: 'layui-layer-rim', //加上边框
                    area: ['420px', '240px'], //宽高
                    title:"手机号验证",
                    content: $("#checkeds")
                });
                return;
            }

        }
        that.attr("disabled","disabled");

        $.ajax({
            type: 'POST',
            url: '/system/pwdSave',
            dataType: "json",
            data: $("#form-pwd").serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(res){
                console.log(res);
            },
            success: function(res) {
                if(res.success == 'true'){
                    layer.msg('',{
                        title: false,
                        content: '修改信息成功',
                        closeBtn:0,
                        offset: 'auto',
                        icon:1,
                        zIndex: layer.zIndex, //重点1
                        success: function(layero){
                            layer.setTop(layero); //重点2
                            setTimeout(function () {
                                location.reload();
                            },3000);
                        }
                    });
                }
                else if(res.success == 'false'){
                    layer.msg(res.message);
                    that.removeAttr("disabled");
                }
                else {
                    layer.msg('数据保存出错了，请联系工作人员查看');
                }
            }
        });
    });

});