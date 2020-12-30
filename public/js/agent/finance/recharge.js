$(function(){
    'use strict';

    //Tab切换
    $(".nav_status_btn").on("click",function () {
        if($(this).attr("data-val") == 1) {
            $(".offline").attr("style","display:none");
            $(".immediate").attr("style","display:block");
        }else{
            $(".immediate").attr("style","display:none");
            $(".offline").attr("style","display:block");
        }
    });

    //线下入账 start
    // 线下入账充值方式切换
    $(".recharge-sixteen,.recharge-twenty,.recharge-twenty-two").click(function () {
        if(!$(this).hasClass("on")){
            $(".recharge-sixteen,.recharge-twenty,.recharge-twenty-two").removeClass("on");
            $(".recharge-sixteen,.recharge-twenty,.recharge-twenty-two").removeClass("xianxia");
            $(".recharge-sixteen,.recharge-twenty,.recharge-twenty-two").removeAttr("style");
            $(this).attr("style","border:2px solid rgb(63, 81, 181)");
            $(this).addClass("on");
            $(this).addClass("xianxia");
        }
        $(".recharge-twenty-three,.recharge-twenty-eight,.recharge-twenty-eight-same").attr("style","display:none");
        $(".recharge-fourty-six").attr("style","display:none");

        if($(this).attr("class").indexOf("recharge-sixteen") >-1){
            //银行卡
            $(".recharge-twenty-eight").removeAttr("style");
            $(".recharge-twenty-eight").find(".recharge-fourty-nine").attr("style","background-color:rgb(196,152,113)");
        }else if($(this).attr("class").indexOf("recharge-twenty-two")>-1){
            //微信支付
            $(".recharge-twenty-eight-same").removeAttr("style");
            $(".recharge-twenty-eight-same").find(".recharge-fourty-nine").attr("style","background-color:rgb(45,173,83)");
            $(".recharge-fourty-six").removeAttr("style");
        }else{
            // 支付宝
            $(".recharge-twenty-three").removeAttr("style");
            $(".recharge-fourty-six").removeAttr("style");
        }
    });

    //入账
    $(".pay").click(function () {
        var amount = 0;
        var rule_id = 0;
        if($('body').find("#accounts").length>0){
            amount = $("#accounts").val();
            if (amount=='')
            {
                layer.msg('请输入充值数目');
                return;
            }else if(isNaN(amount)) {
                layer.msg('请输入正确的充值金额');
                return;
            }else if(!isNaN(amount)&&amount<0.01) {
                layer.msg('充值金额最小为0.01');
                return;
            }
        }else{
            rule_id = $("#rule_ids").val();
            if (rule_id=='')
            {
                layer.msg('请选择充值金额');
                return;
            }
        }
        //获取支付方式 1:支付宝 2:微信
        var pay_type = $(".xianxia").attr('data-value'); //
        //转账凭证号
        var trade_no = $("#trade_no").val();
        //获取上传凭证
        var images = $("input[name='images']").val();
        //获取备注
        var note  = $(".notes").val();

        $.ajax({
            url : '/finance/recharge/offline_pay',
            type: 'POST',
            data:{
                'amount':status,
                'rule_id':rule_id,
                'pay_type':pay_type,
                'trade_no':trade_no,
                'images':images,
                'note':note
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (data) {
                if(data.status===200){
                    layer.confirm('操作成功,请等待财务人员后台审核入账？', {
                        btn: ['知道了'] //按钮
                    }, function(){
                        window.location.href = "/finance/recharge/index";
                    });
                }else{
                    tip_note('操作失败');
                }
            },
            error:function () {
                layer.msg("程序出错了");
            }
        });

    });




    //线下入账 end

    //即时入账 start
    //即时入账充值方式切换
    $(".recharge-fifty-two,.recharge-fifty-three").click(function () {
        if(!$(this).hasClass("on")){
            $(".recharge-fifty-two,.recharge-fifty-three").removeClass("on");
            $(".recharge-fifty-two,.recharge-fifty-three").removeClass("jishi");
            $(".recharge-fifty-two,.recharge-fifty-three").removeAttr("style");
            $(this).attr("style","border:2px solid rgb(63, 81, 181)");
            $(this).addClass("on");
            $(this).addClass("jishi");
        }
        var pay_type = $(this).attr("data-value");
        if(pay_type == 1){
            //支付宝
            $(".pay_name").html("支付宝")
            $(".alipay_poundage").removeClass("pay_display");
        }else{
            //招行聚合支付
            $(".pay_name").html("微信支付")
            $(".alipay_poundage").addClass("pay_display");
        }

        var amount = 0;
        if($('body').find("#account").length>0){
            amount = parseInt($('#account').val().replace(/\s+/g,""));
        }else{
            amount = $("select option:selected").attr('data-value');
        }
    });


    $(".to_pay").click(function () {
        var amount = 0;
        var rule_id = 0;
        if($('body').find("#account").length>0){
            amount = $("input[name='account']").val();
            if (amount=='')
            {
                layer.msg('请输入充值数目');
                return;
            }else if(isNaN(amount)) {
                layer.msg('请输入正确的充值金额');
                return;
            }else if(!isNaN(amount)&&amount<0.01) {
                layer.msg('充值金额最小为0.01');
                return;
            }
        }else{
            rule_id = $("#rule_id").val();
            if (rule_id=='')
            {
                layer.msg('请选择充值金额');
                return;
            }
        }
        //获取备注
        var note  = $(".note").val();
        //获取支付方式 1:支付宝 2:微信
        var pay_type = $(".jishi").attr('data-value'); //
        parent.window.open("/finance/recharge/pay?amount="+amount+"&rule_id="+rule_id+"&type="+pay_type+"&note="+note);
        return false;
    });




    //即时入账 end


    //图片上传预览
    // var upImg = document.getElementById('upImg')
    // var show_img = document.getElementById('show_img')
    // var imgArr = [];//存储图片file;
    // upImg.onchange = function(){
    //
    //     var files = upImg.files;
    //     if($("#show_img").children().length >= 2 || files.length >= 3){
    //         alert("图片数量超出限制");
    //         return false;
    //     }
    //
    //     for (let i = 0; i < files.length; i++) {
    //         if(typeof FileReader === 'undefined'){
    //             alert('您的浏览器不支持图片上传，请升级您的浏览器');
    //             return false;
    //         }
    //
    //         if(!/.(jpg)$/.test(files[i].name)){
    //             alert("仅允许JPG格式");
    //             return false;
    //         }
    //
    //         var reader = new FileReader();
    //         imgArr.push(files[i])
    //         reader.readAsDataURL(files[i]);
    //         reader.onload = function(e){
    //             var div =  document.createElement('div')
    //             var img =  document.createElement('img')
    //             var span =  document.createElement('span')
    //             span.dataset.filename = imgArr[i].name
    //             img.src = e.target.result;
    //             div.appendChild(img);
    //             div.appendChild(span);
    //             show_img.appendChild(div);
    //             show_img.style.display = "block";
    //         };
    //     };
    //     upImg.value = '';//清空value值,因为如果下一次传的一样的onchange不会触发
    // }
    // //动态创建的标签使用事件委托找到标签
    // show_img.onclick = function(e){
    //     var event = e || window.event
    //     var target = event.target || event.srcElement;
    //     if(target.nodeName.toLowerCase() == 'span'){
    //         target.parentNode.remove();
    //         for(var i = 0; i<imgArr.length;i++){
    //             if(target.getAttribute('data-filename') == imgArr[i].name){
    //                 imgArr.splice(i,1)//删除数组中对应的img
    //             }
    //         }
    //     }
    //     if($("#show_img").children().length == 0){
    //         show_img.style.display = "none";
    //     }
    // }
    //
    // $(".up").click(function () {
    //    console.log(imgArr)
    // })

})