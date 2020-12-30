$(function () {

    //省市区组件 start
    if($("body").find(".areas-province").length > 0){
        var province_value = $(".areas-province").attr('data-value');
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
                $(".areas-province").append(provinces_html);
            },
            error : function(){
                console.log("error");
            }
        });

        //省份有值则自动填充市和区
        if(province_value != ''){
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
                    $(".areas-city").html('');
                    $(".areas-area").html('<option>区</option>');
                    $(".areas-city").append(city_html);

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
                    $(".areas-area").html('')
                    $(".areas-area").append(areas_html);

                },
                error : function(){
                    console.log("error");
                }
            });
        }
    }

    $("body").delegate(".areas-province","change",function (val){
        var self = $(this);
        var province_id = $(this).val();
        //填充市option
        $.ajax({
            url : '/ajax',
            type: 'POST',
            data:{
                id:province_id,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                var city_html = '<option value="-1">市</option>';
                if(province_id != 0){
                    for(var j=0; j<data.list.length; j++){
                        city_html += "<option value='"+data.list[j]['area_id']+"'>"+data.list[j]['area_name']+"</option>";
                    }
                }
                // $(".areas-city").html('');
                // $(".areas-area").html('<option>区</option>');
                // $(".areas-city").append(city_html);
                self.next().html('');
                self.next().next().html('<option>区</option>');
                self.next().append(city_html);
            },
            error : function(){
                console.log("error");
            }
        });
    })

    $("body").delegate(".areas-city","change",function (){
        var self = $(this);
        //填充区option
        $.ajax({
            url : '/ajax',
            type: 'POST',
            data:{
                id:$(this).val(),
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                var areas_html = '<option>区</option>';
                for(var k=0; k<data.list.length; k++){
                    areas_html += "<option value='"+data.list[k]['area_id']+"'>"+data.list[k]['area_name']+"</option>";
                }
                // $(".areas-area").html('')
                // $(".areas-area").append(areas_html);

                self.next().html('');
                self.next().append(areas_html);
            },
            error : function(){
                console.log("error");
            }
        });
    })




    //是否同意服务条款
    $('body').delegate('#is_check','click',function () {
        if($("#is_check").prop('checked')===false){
            $("#confirm").attr("disabled","disabled");
            $("#confirm").addClass("is_check");
        }else{
            $("#confirm").removeAttr("disabled");
            $("#confirm").removeClass("is_check");
        }
    });

    //验证手机号
    $("body").delegate("#mobile","change",function () {
        var reg = /^1[3|4|5|7|8][0-9]{9}$/; //验证规则
        var mobile = $(this).val();
        if(mobile==""){
            return;
        }
        //手机号验证
        if(!reg.test(mobile)){
            layer.msg("请输入的正确的手机号码");
            return;
        }

        $.ajax({
            type: 'POST',
            url: "/register/checkMobile",
            dataType: "json",
            data: {mobile:mobile},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(res) {
                if(res.status == 404){
                    layer.alert(res.message, {
                        skin: 'layui-layer-lan' //样式类名
                        ,closeBtn: 0
                    });
                }
                if(res.status==200){

                }
            },
            error:function(res){
                layer.msg("手机号校验失败!");
            }

        });


    });

    //验证邮箱
    $("body").delegate("#email","change",function () {
        var reg=/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i;
        var email=$(this).val();
        if(email==""){
            return;
        }
        if(!reg.test(email)){
            layer.msg( '请输入的正确的邮箱');
            return;
        }
    });



    //注册提交
    $("#confirm").click(function () {
        var form = $(this).parent().parent().parent();

        form.trigger("validate");
        //没有出现验证错误情况下才提交
        if(form.find('.form-control').hasClass('n-invalid')) {
            return false;
        }

        var url = form.attr('action');

        var index = layer.open({
            type: 1,
            skin: 'layui-layer-lan', //加上边框
            area: ['350px', '180px'], //宽高
            content: '<p style="padding: 20px;font-size: 12px;">店铺注册信息正在保存中，请勿关闭页面，保存成功时会自动关闭，请稍后...</p>'
        });


        $.ajax({
            type: 'POST',
            url: url,
            dataType: "json",
            data: $("form").serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(res){
                console.log(res);
            },
            success: function(data) {
                if(data.success == 'true'){
                    layer.close(index);
                    $.ajax({
                        url : "/tips_success",
                        type: 'POST',
                        data:{
                            text:'您的店铺已经注册申请成功,我们将于三个工作日内与您取得联系,请保持通讯畅通,祝您生活愉快！',
                            interval:'3',
                            title:'注册申请'
                        },
                        dataType : 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        success : function(data){
                            layer.open({
                                type:1,
                                title:false,
                                closeBtn: 1,
                                btn:false,
                                resize : false,
                                shade:0,
                                area:['600px','130px'],
                                skin:"success-skin",
                                content: data.html,
                                zIndex: layer.zIndex,
                                success: function(layero, index){
                                    layer.setTop(layero);
                                    var time = parseInt($(".mount").attr("data-num"));
                                    setTimeout(function () {
                                        getRandomCode();
                                    }, 1000);
                                    function getRandomCode() {
                                        if (time === 0) {
                                            time = 0;
                                            return;
                                        } else {
                                            time--;
                                            $(".mount").html(time);
                                        }
                                        setTimeout(function () {
                                            getRandomCode();
                                        }, 1000);
                                    }

                                    setTimeout(function () {
                                        layer.close(index);
                                        window.location.href='/index/home';
                                    },3000);
                                }
                            });
                        },
                        error : function(){

                        }
                    });
                    // tip_success('/index/home','您的店铺已经注册申请成功,我们将于三个工作日内与您取得联系,请保持通讯畅通,祝您生活愉快！','注册申请','3');
                }
                else if(data.status == 404){
                    layer.close(index);
                    layer.alert(data.message, {
                        skin: 'layui-layer-lan' //样式类名
                        ,closeBtn: 0
                    });
                }
                else {
                    layer.close(index);
                    layer.alert("数据保存出错了", {
                        skin: 'layui-layer-lan' //样式类名
                        ,closeBtn: 0
                    });
                }
            }
        });
    });


    //确认密码输入框验证提醒
    $("#dms_real_password").on('blur',function(){
       var pwd = $('#dms_adm_password').val();
        if($('#dms_real_password').val()!= pwd){
            $('#dms_real_password').val("");
            layer.msg("两次输入的密码不一致");
        }
    });


});