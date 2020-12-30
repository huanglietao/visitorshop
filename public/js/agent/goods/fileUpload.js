$(function () {


    //表单提交处理
    $("#comfirm").click(function () {

        if(!$("#works_name").val() || ($("body").find('#file_path3').length>0 && !$("#file_path3").val())){
            layer.msg("请填写作品名称或请上传文件");
            return;
        }

        if($("body").find("input[value='error']").length > 0){
            layer.msg("存在文件不符合要求，请重新整理上传");
            return;
        }

        $.ajax({
            type: 'POST',
            url: '/goods/filesave',
            dataType: "json",
            data: $("form").serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(res){
                layer.msg(res.message);
            },
            success: function(data) {
                if(data.status==200 && data.success == 'true'){
                    layer.confirm("作品提交成功！",{btn:['确定']},
                        function () {
                            location.reload();
                        });
                }
            }
        });
    });


    //加入购物车
    $("#addCart").click(function () {

        if(!$("#works_name").val() || ($("body").find('#file_path3').length>0 && !$("#file_path3").val())){
            layer.msg("请填写作品名称或请上传文件");
            return;
        }

        if($("body").find("input[value='error']").length > 0){
            layer.msg("存在文件不符合要求，请重新整理上传");
            return;
        }


        $.ajax({
            type: 'POST',
            url: '/goods/shoppingCart',
            dataType: "json",
            data: $("form").serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(res){
                layer.msg(res.message);
            },
            success: function(res) {
                if(res.data.message==""){
                    layer.confirm("订购的作品已经加入到购物车,请移步到购物车查看！",{btn:['确定']},
                        function () {
                            location.reload();
                        });
                    return;
                }else{
                    layer.msg(res.data.message);
                    return;
                }
            }
        });
    });



    //立即订购
    $("#orderSave").click(function () {

        if(!$("#works_name").val() || ($("body").find('#file_path3').length>0 && !$("#file_path3").val())){
            layer.msg("请填写作品名称或请上传文件");
            return;
        }

        if($("body").find("input[value='error']").length > 0){
            layer.msg("存在文件不符合要求，请重新整理上传");
            return;
        }

        $.ajax({
            type: 'POST',
            url: '/goods/orderCreate',
            dataType: "json",
            data: $("form").serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(res){
                layer.msg(res.message);
            },
            success: function(res) {
                if(res.success=='true'){
                    location.href = "/orders/create?cart_id="+res.data.cart_id+"&is_fast=1";
                }else{
                    layer.msg(res.data.message);
                    return;
                }
            }
        });
    });



    $("body").delegate("#collect",'click',function () {
        var prod_id = $(this).attr("data-value");
        var flag = $(this).children(":first-child").attr("data-value");
        var that = $(this);
        $.ajax({
            url :'/goods/collect',
            type: 'POST',
            data : {
                flag:flag,
                prod_id:prod_id
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function(res) {
                if(res.success=='true'){
                    if(flag=="0"){
                        that.html("<span style='color: red' data-value='1'><i class='fa fa-heart fa-lg' style='margin-right: 5px'></i>已收藏</span>");
                    }else{
                        that.html("<span style='color: black' data-value='0'><i class='fa fa-heart-o fa-lg' style='margin-right: 5px'></i>收藏</span>");
                    }
                }else{
                    alert("程序出错了");
                }
            }
        });
        return false;
    });

});