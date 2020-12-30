var add_category;
var is_checked;
var check_value;
$(function () {

    $('#main').delegate(".btn-dialog",'click',function () {
        setTimeout(function(){
            if ( $("#form-save").find("#reservationtime"))
            {
                rangedatapicker();
            }
        },200);

    });
    $('#main').delegate(".btn-edit",'click',function () {
        setTimeout(function(){
            if ( $("#form-save").find("#reservationtime"))
            {
                rangedatapicker();
            }
        },200);

    });

    $(document).on('click','[type="radio"]',function () {
       var input_name = $(this)[0].name;
       var input_value = $(this).val();
       //派发方式：当派发方式为4(积分兑换)时，需填入兑换时所需要的最低积分
       if(input_name=="cou_distribution_method"){
           if(input_value==4){
               $("#cou_score").attr("data-rule",'兑换时所需积分:required');
               $("#input_score").show();
           }else{
               $("#cou_score").removeAttr("data-rule");
               $("#cou_score").val("");
               $("#input_score").hide();
           }
       }
        //使用规则:如果使用规则选择为2(满减)时，需填入使用优惠券时，订单最低消费的金额
        if(input_name=="cou_use_rule"){
            if(input_value==2){
                $("#cou_min_consumption").attr("data-rule",'最低消费金额:required');
                $("#input_min_consumption").show();
            }else{
                $("#cou_min_consumption").removeAttr("data-rule");
                $("#cou_min_consumption").val("");
                $("#input_min_consumption").hide();
            }
        }
        //使用范围:如果选择为2(指定商品)或者3(指定分类)，需要选择商品或者商品分类
        if(input_name=='cou_use_limits'){
           if(input_value==1){
               $("#goods").hide();
               $("#input_goods").hide();
               $("#input_category").hide();
               $(".checkbox_items").html('');
               $(".goods_category_id").val('');
               $(".goods_id").val('');
               $(".exist_ids").val('');
           }else if(input_value==2){
               $("#input_category").hide();
               $("#goods").show();
               $("#input_goods").show();
               $(".checkbox_items").html('');
               $(".goods_category_id").val('');
               $(".goods_id").val('');
               $(".exist_ids").val('');
           }else if(input_value==3){
               $("#input_goods").hide();
               $("#goods").show();
               $("#input_category").show();
               $(".checkbox_items").html('');
               $(".goods_category_id").val('');
               $(".goods_id").val('');
               $(".exist_ids").val('');
           }
        }
    });

    add_category = function(obj) {
        var cou_use_limits = $("input[name='cou_use_limits']:checked").val();
        console.log(cou_use_limits);
        var exist_ids = $(".exist_ids").val();
        var goods_id = $(".goods_id").val();
        var goods_category_id = $(".goods_category_id").val();
        if(cou_use_limits==2){
            var text = $(".goods :checked").text();
            var id = $(".goods").val();

        }
        else if(cou_use_limits==3){
             text = $(".category :checked").text();
             id = $(".category").val();
        }


        if(exist_ids == ''){
            var ids = id;
            $(".exist_ids").val(ids);
            if(cou_use_limits==2){
                var html = $(".checkbox_items").html();
                $(".goods_id").val(ids);
            }
            else if(cou_use_limits==3){
                $(".goods_category_id").val(ids);
            }
            var html = $(".checkbox_items").html();

            $(".checkbox_items").html('');
            html += '<div class="checkbox_item"><input  type="checkbox" class="ui-checkbox" checked="checked"><label style="font-weight: normal;" data-type="'+cou_use_limits+'" class="ui-label" data-id="'+id+'" onclick="is_checked(this)">'+text+'</label></div>';
            $(".checkbox_items").append(html);
        }else{

            if(exist_ids.indexOf(id) > -1){
                alert('分类已经存在');
            }else{
                if(goods_category_id.length !=0){
                    var ids = goods_category_id+","+id;
                }else{
                    var ids = id;
                }

                var e_ids = exist_ids+","+id;
                var html = $(".checkbox_items").html();
                $(".goods_category_id").val(ids)
                $(".exist_ids").val(e_ids)
                $(".checkbox_items").html('');
                html += '<div class="checkbox_item"><input type="checkbox" class="ui-checkbox" checked="checked"><label style="font-weight: normal;" data-type="3" class="ui-label" data-id="'+id+'" onclick="is_checked(this)">'+text+'</label></div>';
                $(".checkbox_items").append(html);
            }
        }
    };


    is_checked = function(obj) {
        $(obj).prev().removeAttr("checked");
        if($(obj).prev().hasClass("ck")){
            $(obj).prev().removeClass("ck");
            $(obj).prev().prop('checked', true);
        }else{
            $(obj).prev().addClass("ck");
            $(obj).prev().prop('checked', false);
        }
        var ids = '';
        var flag = 0;
        var type = $(obj).attr("data-type");//3指定分类，2指定商品
        if(type == 3){
            $(".ui-checkbox").each(function () {
                if(!$(this).hasClass("ck")){
                    if(flag == 0){
                        ids += $(this).next().attr("data-id");
                        flag = 1;
                    }else{
                        ids += ","+$(this).next().attr("data-id");
                    }

                }
            })
            $(".goods_category_id").val(ids);
        }else{
            $(".ui-checkbox").each(function () {
                if(!$(this).hasClass("ck")){
                    if(flag == 0){
                        ids += $(this).next().attr("data-id");
                        flag = 1;
                    }else{
                        ids += ","+$(this).next().attr("data-id");
                    }

                }
            })
            $(".goods_ids").val(ids);
        }
    };


    //新页面弹窗功能
    $('#main').delegate(".btn-edit",'click',function () {

        var url = $(this).attr("data-url");
        var title = $(this).attr("data-title");
        var params = {
            area : eval($(this).attr('data-area'))
        }
        var re_index = layer.commonopen('',title,params);
        $.ajax({
            url : url,
            type: 'GET',
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                $("#layui-layer"+re_index).find(".layui-layer-content").html(res['data'].html);
                layer.layerfooter($("#layui-layer"+re_index),re_index);

                //派发方式：当派发方式为4(积分兑换)时，需填入兑换时所需要的最低积分
                var distribution_value = $("input[name='cou_distribution_method']:checked").val();
                    if(distribution_value==4){
                        $("#cou_score").attr("data-rule",'兑换时所需积分:required');
                        $("#input_score").show();
                    }else{
                        $("#cou_score").removeAttr("data-rule");
                        $("#cou_score").val("");
                        $("#input_score").hide();
                    }
                //使用规则:如果使用规则选择为2(满减)时，需填入使用优惠券时，订单最低消费的金额
                var use_value = $("input[name='cou_use_rule']:checked").val();

                if(use_value==2){
                    $("#cou_min_consumption").attr("data-rule",'最低消费金额:required');
                    $("#input_min_consumption").show();
                }else{
                    $("#cou_min_consumption").removeAttr("data-rule");
                    $("#cou_min_consumption").val("");
                    $("#input_min_consumption").hide();
                }

                //使用范围:如果选择为2(指定商品)或者3(指定分类)，需要选择商品或者商品分类
                var limits_value = $("input[name='cou_use_limits']:checked").val();
                    if(limits_value==1){
                        $("#goods").hide();
                        $("#input_goods").hide();
                        $("#input_category").hide();
                    }else if(limits_value==2){
                        $("#input_category").hide();
                        $("#goods").show();
                        $("#input_goods").show();

                    }else if(limits_value==3){
                        $("#input_goods").hide();
                        $("#goods").show();
                        $("#input_category").show();
                    }

            },
            error : function(){

            }
        });
    });

    check_value = function (obj,num) {
        if(num){
            var value = obj.value;
            if(value<num){
                $("#cou_nums").addClass('n-invalid');
                $("#cou_nums").parent().next().text("填写的数量不能小于上次的数量");
            }else{
                $("#cou_nums").removeClass('n-invalid');
                $("#cou_nums").parent().next().text("");
            }
        }
    };


});