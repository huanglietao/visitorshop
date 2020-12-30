var deleteItem;
var addTR;
var delTR;
var areaText;
var choose;
$(function(){
    'use strict';

    $("body").delegate('.check input[type="checkbox"]','click',function () {

        if($("body").find("#trans_name_list").length > 0){
            var trans_name_list = $("#trans_name_list").val();
            var trans_name_array=[];
            var tans_id = $(this)[0].name;
            if(trans_name_list!=""){
                trans_name_array = trans_name_list.split(",");
            }
            if($(this).prop("checked")){

                $('.first'+tans_id).show();
                // var ids = "#"+id;
                // $(ids).append(html);
                // html.removeClass('first')
                // html.show();
                trans_name_array.splice(trans_name_array.length,0,tans_id);
            }else{
                // var tans_id = $(this)[0].name;
                $('.first'+tans_id).hide();
                $(".first"+tans_id+" input").val("");
                $("input[name='tr_num"+tans_id+"']").val(0);
                trans_name_array.splice($.inArray(tans_id,trans_name_array),1);
            }
            $("#trans_name_list").val(trans_name_array);
        }
        if($("body").find("#del_name_list").length > 0){
            var del_name_list = $("#del_name_list").val();
            var delivery_id = $(this)[0].name;
            var del_name_array=[];
            if(del_name_list!==""){
                del_name_array = del_name_list.split(",");
            }
            if($(this).prop("checked")){
                del_name_array.splice(del_name_array.length,0,delivery_id);
            }else{
                del_name_array.splice($.inArray(delivery_id,del_name_array),1);
            }
            $("#del_name_list").val(del_name_array);
        }


    });

    //删除表格
    deleteItem = function (obj,ids){
       layer.confirm('确定要删除该栏目吗？', {
            btn: ['确定', '取消'],
            title: '提示',
            zIndex: layer.zIndex //重点1
        }, function(index) {
           var id = $("input[name='"+ids+"']")[0].name;
            $("input[name='"+ids+"']")[0].checked = false;
           var val = $("#trans_name_list").val();
           var arr=[];
           if(val!=""){
               arr = val.split(",");
           }
           arr.splice($.inArray(id,arr),1);
           $("#trans_name_list").val(arr);
            $(obj).parent().parent().parent().hide();
           layer.close(index);
        }, function(index) {
            //按钮【按钮二】的回调
           layer.close(index);
        });

    };

    //添加表格行tr
    addTR = function (obj,ids) {
        var trHtml = $('#del_tr'+ids).clone();
        var areaHtml = $('#add_area'+ids).clone();
        var trlength = $("#delivery_table"+ids).find("tr").length/2+1;
        // console.log(trlength);
        var trNum = $("input[name='tr_num"+ids+"']").val();
        if(trNum==0){
            $(obj).parent().parent().parent().next().children().show();
            $("input[name='tr_num"+ids+"']").val(trNum*1+1);
            return;
        }

        $("input[name='tr_num"+ids+"']").val(trlength);
        var Html = "<input type=\"text\" name=\"price"+ids+trlength+"\"  hidden/>";
        var aHtml = "<input type=\"text\" name=\"area"+ids+trlength+"\"  hidden/>";
        $("#delivery_table"+ids).append(Html);
        $("#delivery_table"+ids).append(aHtml);
        $("#delivery_table"+ids).append(trHtml);
        $("#delivery_table"+ids).append(areaHtml);
        $("#delivery_table"+ids).find("tr:last").children(":first-child").html("");
    };

    //删除表格行
    delTR = function (obj,ids) {
        var tr = $(obj).parent().parent();
        var trlength =Math.ceil($(tr)[0].rowIndex/2);
        $("input[name='tr_num"+ids+"']").val(trlength-1);
        if(trlength==1){
            $(obj).parent().parent().next().hide();
            $(obj).parent().parent().hide();
            // $("input[name='price"+ids+trlength+"']").remove();
            $("input[name='area"+ids+trlength+"']").remove();
            return;
        }

        // $("input[name='price"+ids+trlength+"']").remove();
        $("input[name='area"+ids+trlength+"']").remove();
        $(obj).parent().parent().next().remove();
        $(obj).parent().parent().remove();
    };

    //选择地区显示
    areaText = function(obj,ids) {
        var tr = $(obj).parent().parent();
        var trlength =Math.ceil($(tr)[0].rowIndex/2);//tr
        // $("input[name='tr_num"+ids+"']").val(trlength);

        // var first_weight=$(tr).find("input[data-name='area"+ids+"[first_weight]']").val();//首重
        // var first_price=$(tr).find("input[data-name='area"+ids+"[first_price]']").val();//首重费用
        // var continuation_weight=$(tr).find("input[data-name='area"+ids+"[continuation_weight]']").val();//续重
        // var continuation_price=$(tr).find("input[data-name='area"+ids+"[continuation_price]']").val();//续重费用
        // var area_price = first_weight+","+first_price+","+continuation_weight+","+continuation_price;
        // $("input[name='price"+ids+trlength+"']").val(area_price);

        var text = $(obj).parent().prev().children(":first-child").children("select").eq(2).find("option:selected").text();//选中地区的数据
        var area_id = $(obj).parent().prev().children(":first-child").children("select").eq(2).val();//选中地区的id

        if (area_id == '' || area_id == null || area_id == '区' || area_id == 0) {
            text = $(obj).parent().prev().children(":first-child").children("select").eq(1).find("option:selected").text();//选中地区的数据
            area_id = $(obj).parent().prev().children(":first-child").children("select").eq(1).val();//选中地区的id

            if (area_id == '' || area_id == null || area_id == -1 || area_id == 0) {
                text = $(obj).parent().prev().children(":first-child").children("select").eq(0).find("option:selected").text();//选中地区的数据
                area_id = $(obj).parent().prev().children(":first-child").children("select").eq(0).val();//选中地区的id
            }
        }

        //没有选中地区的提示
        if (area_id == '' || area_id == 0 || area_id == -1 || area_id == '区') {
            alert('请选择地区');
            return false;
        }

        //获取选中地区的值
        var exists = $(obj).parent().parent().next().children(":first-child").find('.ys-click').text();
        var is_add = $(obj).parent().parent().next().children(":first-child").find('.ys-no').text();


        if (exists != '' || is_add != '') {
            //是否已经选中
            if (exists.indexOf(text) != -1) {
                alert('该地区已经选择');
                return false;
            }
            //是否已经添加
            if(is_add.indexOf(text) != -1){
                alert("该地区已添加");
                return false;
            }
        }

        var html = "<div class='ys-p  ys-no ys-click' data-id='"+area_id+"'>"+text+"<div style=\"display: inline-block\"><i class='fa fa-trash fa-lg icon' aria-hidden='true' onclick='choose(this,"+ids+")'></i></div></div>";
        $(obj).parent().parent().next().children(":first-child").append(html);

        var arealist = $("input[name='area"+ids+trlength+"']").val();
        if(arealist == ''){
            arealist = area_id;
        }else{
            arealist = arealist+","+area_id;
        }

        $("input[name='area"+ids+trlength+"']").val(arealist);
    };

    choose = function(obj,ids) {
        var tr = $(obj).parent().parent().parent().parent().prev();
        var trlength =Math.ceil($(tr)[0].rowIndex/2);
        var exist_area_id = $("input[name='area"+ids+trlength+"']").val();
        var area_id = $(obj).parent().parent().attr('data-id');
        var arr = exist_area_id.split(",");
        arr.splice($.inArray(area_id,arr),1);
        var n_area_id = arr.join(",")
        $("input[name='area"+ids+trlength+"']").val(n_area_id);
        $(obj).parent().parent().remove();
    };


});
