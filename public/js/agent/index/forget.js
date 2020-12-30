$(function () {

    var agt_name;

    //下一步
    $(".first_next").click(function () {
        agt_name = $('#agent_name').val();

        if(agt_name == '' || agt_name == null || agt_name == undefined){
            layer.msg("请输入分销账号")
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "/index/check",
            dataType: "json",
            data: {agent_name:agt_name},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(res) {
                if(res['status'] == 200 && res['success']){
                    $(".forget").attr('style','display:none')
                    $(".reset").removeAttr('style')
                    $(".mobile").html(res['data'])
                }else{
                    layer.msg(res['message'])
                }
            },
            error:function(res){
                layer.msg("操作失败，请稍后重试!");
            }
        });
    })

    //获取验证码
    $(".code").click(function () {

        if(agt_name == '' || agt_name == null || agt_name == undefined){
            layer.msg("出错了，请稍后重试")
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "/index/code",
            dataType: "json",
            data: {agent_name:agt_name},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(res) {
                if(res['status'] == 200 && res['success']){
                    layer.msg(res['data'])
                }else{
                    layer.msg(res['message'])
                }
            },
            error:function(res){
                layer.msg("操作失败，请稍后重试!");
            }

        });
    })

    //校验并更改密码
    $(".second_next").click(function () {
        var code = $('#verification').val()
        var password = $('#password').val()
        var confirm = $('#confirm').val()

        if(code == '' || code == null || code == undefined){
            layer.msg("请输入验证码")
            return false;
        }

        if(password == '' || password == null || password == undefined){
            layer.msg("请输入密码")
            return false;
        }

        if(confirm == '' || confirm == null || confirm == undefined){
            layer.msg("请再次输入密码")
            return false;
        }

        if(password !== confirm){
            layer.msg("两次密码输入不一致")
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "/index/verification",
            dataType: "json",
            data: {
                agent_name:agt_name,
                password:password,
                code:code
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(res) {

                if(res['status'] == 200 && res['success']){
                    tip_success('/login',res['data'])
                }else{
                    layer.msg(res['message'])
                }
            },
            error:function(res){
                layer.msg("操作失败，请稍后重试!");
            }

        });
    })

    //操作成功提示
    tip_success = function (url,text,title,interval) {
        title = title?title:"";
        interval = interval?interval:2;
        text = text?text:"";
        url = url?url:"";
        $.ajax({
            url : "/tips_success",
            type: 'POST',
            data:{
                text:text,
                interval:interval,
                title:title
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
                            window.location.href=url;
                        },interval*1000);
                    }
                });
            },
            error : function(){

            }
        });
    };

});