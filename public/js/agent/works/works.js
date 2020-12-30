/**
 * 作品列表
 * Created by daiyd on 2019/8/8
 */
var batch;
var review;
$(function(){
    'use strict'

    // 全选/反选
    $("body").delegate('.checkall-1','click',function () {
        $(".prj_ids").val("");
        if ($(this).prop("checked")) {
            $("input[type='checkbox'][name='checkworks']").prop("checked",true);//全选
            $(".checkall").prop("checked",true);//全选

            $.each($(".checkedres"),function () {
                var status = $(this).attr('data-value');
                var prj_ids = $(".prj_ids").val();

                var ids_list =[];
                if(prj_ids!==""){
                    ids_list = prj_ids.split(",");
                }
                if($(this).prop("checked")){
                    ids_list.splice(ids_list.length,0,status);
                }else{
                    ids_list.splice($.inArray(status,ids_list),1);
                }
                $(".prj_ids").val(ids_list);
            });
        } else {
            $("input[type='checkbox'][name='checkworks']").prop("checked",false);  //取消全选
            $(".checkall").prop("checked",false);  //取消全选
            //遍历所有的复选框
            $.each($(".checkedres"),function () {
                //清空隐藏的input的值
                $(".prj_ids").val("");
            });
        }
    })


    //列表数据中单独单击复选框
    $("body").delegate(".checkedres","click",function () {
        var sumcheckbox = $("input[type='checkbox'][name='checkworks']").length; //获取所有复选框的总个数
        var sumcheckedbox = $("input[type='checkbox'][name='checkworks']:checked").length; //获取选中的总个数
        //对比是否相等就全部选中，否则非全选
        if (sumcheckbox == sumcheckedbox) {
            $(".checkall").prop("checked",true);//全选
        }else{
            $(".checkall").prop("checked",false);//取消全选
        }

        var status = $(this).attr('data-value');
        var prj_ids = $(".prj_ids").val();

        var ids_list =[];
        if(prj_ids!==""){
            ids_list = prj_ids.split(",");
        }
        if($(this).prop("checked")){
            ids_list.splice(ids_list.length,0,status);
        }else{
            ids_list.splice($.inArray(status,ids_list),1);
        }
        $(".prj_ids").val(ids_list);
    });


    //tab 作品状态标签切换数据
    $(".works_tab").click(function () {
        var status = $(this).attr("data-val");
        //制作中
        if(status==1){
            $("#act-btn").show();
            $("#down-act-btn").show();
            $(".btn-check").hide();
            $(".btn-tongbu-check").hide();
            $(".btn-search-plus").show();
            $(".btn-paperclip").hide();
            $(".btn-clone").hide();
            $(".btn-trash").show();
            $(".btn-undo").hide();
        }
        //待确认
        else if(status==2){
            $("#act-btn").show();
            $("#down-act-btn").show();
            $(".btn-check").show();
            $(".btn-tongbu-check").show();
            $(".btn-search-plus").hide();
            $(".btn-paperclip").show();
            $(".btn-clone").show();
            $(".btn-trash").show();
            $(".btn-undo").hide();
        }
        //已订购
        else if(status==3){
            $("#act-btn").show();
            $("#down-act-btn").show();
            $(".btn-check").hide();
            $(".btn-tongbu-check").hide();
            $(".btn-search-plus").hide();
            $(".btn-paperclip").hide();
            $(".btn-clone").show();
            $(".btn-trash").hide();
            $(".btn-undo").hide();
        }
        //回收站
        else if(status==4){
            $("#act-btn").show();
            $("#down-act-btn").show();
            $(".btn-check").hide();
            $(".btn-tongbu-check").hide();
            $(".btn-trash").hide();
            $(".btn-search-plus").hide();
            $(".btn-paperclip").hide();
            $(".btn-clone").hide();
            $(".btn-undo").show();
        }
        else{
            $("#act-btn").hide();
            $("#down-act-btn").hide();
            $(".btn-check").hide();
            $(".btn-tongbu-check").hide();
            $(".btn-search-plus").hide();
            $(".btn-paperclip").hide();
            $(".btn-clone").hide();
            $(".btn-trash").hide();
            $(".btn-undo").hide();
        }

        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        obj.limit = $("#pages-limit").val();
        obj.prj_status = status;
        loadTable(obj);
    });

    //标签选择
    $("body").delegate(".checkedlabel","click",function () {

        var prj_label_list = $("#prj_label_list").val();
        var prj_label_id = $(this)[0].name;
        var prj_label_array=[];
        if(prj_label_list!==""){
            prj_label_array = prj_label_list.split(",");
        }
        if($(this).prop("checked")){
            prj_label_array.splice(prj_label_array.length,0,prj_label_id);
        }else{
            prj_label_array.splice($.inArray(prj_label_id,prj_label_array),1);
        }
        $("#prj_label_list").val(prj_label_array);
    });

    //批量操作
    batch = function (flag) {
        var type = 'GET';
        var url = "";
        var data = $(".prj_ids").val();
        var prj_data = [];

        if(!data){
            layer.msg('请选择要操作的数据');
            return;
        }
        //审核
        if(flag=='review'){
            url = "/works/review";
        }
        //克隆
        else if(flag=='clone'){
            url = "/works/clone";
            type = 'POST';
            prj_data = data.split(",");
            for(var i=0;i<prj_data.length;i++){
                var file_type = $("#prj"+prj_data[i]).val();
                var name = $("#prj"+prj_data[i]).attr("data-value");
                if(file_type==2){
                    layer.msg("作品"+name+"不可克隆");
                    return;
                }
            }
        }
        //删除
        else if(flag=='trash'){
            url = "/works/delIds";
        }
        //恢复
        else if(flag=='undo'){
            url = "/works/regain";
        }

        //标签
        else if(flag == 'paperclip'){
            var title = "标签作品";
            var params = {
                area : eval($(this).attr('data-area'))
            }
            var re_index = layer.commonopen('',title,params);

            $.ajax({
                url : '/works/remarks',
                type: 'GET',
                data : {
                    prj_id:data
                },
                dataType : 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success : function(res){
                    $("#layui-layer"+re_index).find(".layui-layer-content").html(res['data'].html);
                    layer.layerfooter($("#layui-layer"+re_index),re_index);
                },
                error : function(){

                }
            });
            return;
        }

        $.ajax({
            url : url,
            type : type,
            data : {
                prj_id:data
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data.status==200&&data.success=='true'){
                 loadTable();
                }
                if(data.status==404&&data.success=='false'){
                    layer.alert(data.message, {
                        skin: 'skin-class', //样式类名
                        closeBtn: 0
                    });
                }
            }
        });
    };

    //批量订购
    $('body').delegate(".btn-check",'click',function () {
        var data = $(this).attr("data-id");
        if(data==undefined){
            data = $(".prj_ids").val();
            if(!data){
                layer.msg('请选择要操作的数据');
                return;
            }
        }
        if($("#shopping_car").val()!=1){
            var title = "订购作品";
            var params = {
                area : eval(['70%', '70%'])
            };
            var re_index = layer.commonopen('',title,params);
        }

        $.ajax({
            url : "/works/order",
            type: 'GET',
            data:{
                prj_id:data
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                if(res.data.cart==1){
                    tip_note("订购的作品已经加入到购物车,请移步到购物车查看","success");
                    return;
                }else{
                    $("#layui-layer"+re_index).find(".layui-layer-content").html(res['data'].html);
                    layer.layerfooter($("#layui-layer"+re_index),re_index);

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
                                tip_note("程序出现错误");
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
                                    // self.next().next().html('<option>区</option>');
                                    self.next().append(city_html);
                                },
                                error : function(){
                                    tip_note("程序出现错误");
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
                                    get_price();
                                },
                                error : function(){
                                    tip_note("程序出现错误");
                                }
                            });
                        }
                    }
                }

            },
            error : function(){

            }
        });
    });


    //订购 同步订单
    $('body').delegate(".btn-tongbu-check",'click',function () {
        var data = $(this).attr("data-id");
        if(data==undefined){
            data = $(".prj_ids").val();
            if(!data){
                layer.msg('请选择要操作的数据');
                return;
            }
        }
        if($("#shopping_car").val()!=1){
            var title = "订购作品";
            var params = {
                area : eval(['70%', '70%'])
            };
        }

        $.ajax({
            url : "/works/ajaxtongbu",
            type: 'GET',
            data:{
                prj_id:data
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                if(res.data.cart==1){
                    if(res.data.message==""){
                        tip_note("订购的作品已经加入到购物车,请移步到购物车查看","success");
                        return;
                    }else{
                        tip_note(res.data.message);
                        return;
                    }

                }else{
                    var re_index = layer.commonopen('',title,params);
                    $("#layui-layer"+re_index).find(".layui-layer-content").html(res['data'].html);
                    layer.layerfooter($("#layui-layer"+re_index),re_index);

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
                                tip_note("程序出现错误");
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
                                    // self.next().next().html('<option>区</option>');
                                    self.next().append(city_html);
                                },
                                error : function(){
                                    tip_note("程序出现错误");
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
                                    get_price();
                                },
                                error : function(){
                                    tip_note("程序出现错误");
                                }
                            });
                        }
                    }
                }
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    });

    $("body").delegate(".syn-add","click",function(){
        $("#add-syn").append(
            '<div class="form-group reduces" >' +
            '<span class="col-xs-12 col-sm-2" style="margin-right: 46px"></span>' +
            '<div class="col-xs-12 col-sm-4" style="display: inline-block">' +
            '<input type="text" class="form-control out_order_no" width="100%" id="out_order_no"  placeholder="天猫/淘宝/京东/订单号" autocomplete="off" value=""  onkeyup="this.value=this.value.replace(/[^\\d]/g,\'\')" onafterpaste="this.value=this.value.replace(/[^\\d]/g,\'\') "/>' +
            '</div> ' +
            '<div class="col-xs-12 col-sm-4" style="display: inline-block"><a href="javascript:;" id="reduce" class="btn btn-success reduce" title="移除"><i class="fa fa-remove"></i>移除</a></div>' +
            '</div>'
        );

    });

    //订单同步订购页面移除按钮
    $("body").delegate("#reduce",'click',function () {
        $(this).parent().parent().remove();
    });

    //订单同步订购页面同步按钮
    $("body").delegate("#tongbu",'click',function () {
        // var order_no = $('#out_order_no').val();//获取输入的订单号
        var outer_account = $(".prj_outer_account").val();

        var orderNo_arr = [];
        var flag = true;
        $.each($(".out_order_no"),function () {
            var order_no = $(this).val();
            if(order_no==""){
                flag = false;
            }
            //清空隐藏的input的值
            orderNo_arr.push(order_no);
        });

        if(!flag){
            layer.msg("请把要同步的订单号填写完整,不需要请移除");
            return;
        }
        orderNo_arr = orderNo_arr.join(",");
        $.ajax({
            url : '/works/outerNo',
            type : 'POST',
            data : {
                outer_no:orderNo_arr,
                outer_account:outer_account
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data.status==404&&data.success=='false'){
                    tip_note(data.message);
                }else{
                    //订单信息
                    var outer_order_data = data.data.outer_data;
                    var obj_orders= outer_order_data['orders']['order'];
                    //可能有实物
                    $("#items").val(data.data.items);

                    //判断商品数量与同步的商品数量是否一致
                    //商品数量
                    // var trlength = $("#tab>tbody").children("tr").length;
                    // //淘宝购买商品数量
                    // var tblength = 0;

                    var flag = true;
                    //判断同步的商品是否是购买的商品
                    for (var i = 0; i <obj_orders.length; i++){
                        // if(obj_orders[i]['entity']==false){
                        //     tblength +=1;
                        // }
                        if($("body").find($("input[class='"+obj_orders[i]['sku_id']+"']")).length==0 && obj_orders[i]['entity']==false){
                            flag = false;
                        }
                        else{
                            //判断购买的商品总数是否跟淘宝购买的一致
                            var num = 0;
                            $.each($("input[type='number'][data-value='"+obj_orders[i]['sku_id']+"']"),function () {
                                num += parseFloat($(this).val());
                            });
                            if(num!=obj_orders[i]['num']){
                                layer.alert('同货号的商品购买数量与淘宝购买数量不一致,请小心同步', {
                                    skin: 'skin-class', //样式类名
                                    closeBtn: 0
                                });
                            }
                            // $("input[class='"+obj_orders[i]['sku_id']+"']");
                            // $("input[type='number'][data-value='"+obj_orders[i]['sku_id']+"']").val(obj_orders[i]['num']);
                            $("input[id='sku_price'][data-value='"+obj_orders[i]['sku_id']+"']").val(obj_orders[i]['price']);
                            $("input[id='sku_price'][data-value='"+obj_orders[i]['sku_id']+"']").prev().text("￥"+obj_orders[i]['price']);
                            $("input[id='cot_prices'][data-value='"+obj_orders[i]['sku_id']+"']").val(obj_orders[i]['payment']);
                            $("input[id='cot_prices'][data-value='"+obj_orders[i]['sku_id']+"']").prev().text("￥"+obj_orders[i]['payment']);
                        }
                    }

                    if(!flag){
                        layer.alert('同步的商品与淘宝商品不一致,请小心同步', {
                            skin: 'skin-class', //样式类名
                            closeBtn: 0
                        });
                    }


                    //收货信息
                    var receiver_data = data.data.receiver_data;
                    //价格
                    var price = outer_order_data.payment;
                    //省
                    var receiver_province = receiver_data.receiver_province;
                    //市
                    var receiver_city = receiver_data.receiver_city;
                    //区
                    var receiver_area = receiver_data.receiver_district;
                    //如果省市区为空，提示原始的地区
                    if(receiver_province=="" || receiver_city=="" || receiver_area==""){
                        var text = outer_order_data.receiver_state+"-"+outer_order_data.receiver_city+"-"+outer_order_data.receiver_district;
                        $("#diqu").text(text);
                    }

                    //关联订单号
                    $("#order_id").val(receiver_data.outer_order_no);
                    //收货人姓名
                    $("#receiver_name").val(receiver_data.receiver_name);
                    //邮编
                    $("#zip_code").val(receiver_data.receiver_zip);
                    //手机号
                    $("#receiver_phone").val(receiver_data.receiver_mobile);
                    //详细地址
                    $("#receiver_address").val(receiver_data.receiver_address);
                    //用户备注
                    $("#buyer_memo").val(receiver_data.buyer_memo);
                    //购买的商品数量
                    $("#orderCount").val(receiver_data.order_count);
                    //淘宝付款价格
                    $("#good_price").text("￥"+price+"元");
                    $("#goodprice").val(price);
                    $("#pay_price").text("￥"+price+"元");
                    $("#payprice").val(price);
                    //省市区
                    $(".areas-province").find("option[value='"+receiver_province+"']").attr("selected",true);
                    var self = $("select[name='province']");



                    //填充市option
                    $.ajax({
                        url : '/ajax',
                        type: 'POST',
                        data:{
                            id:receiver_province
                        },
                        dataType : 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        success : function(data){
                            var city_html = '<option value="-1">市</option>';
                            if(receiver_province != 0){
                                for(var j=0; j<data.list.length; j++){
                                    if(receiver_city == data.list[j]['area_id']){
                                        city_html += "<option selected value='"+data.list[j]['area_id']+"'>"+data.list[j]['area_name']+"</option>";
                                    }else{
                                        city_html += "<option value='"+data.list[j]['area_id']+"'>"+data.list[j]['area_name']+"</option>";
                                    }
                                }
                            }
                            self.next().html('');
                            // self.next().next().html('<option>区</option>');
                            self.next().append(city_html);
                        },
                        error : function(){
                            tip_note("程序出现错误");
                        }
                    });

                    //填充区option
                    $.ajax({
                        url : '/ajax',
                        type: 'POST',
                        data:{
                            id:receiver_city,
                        },
                        dataType : 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        success : function(data){
                            var areas_html = '<option>区</option>';
                            for(var k=0; k<data.list.length; k++){
                                if(receiver_area == data.list[k]['area_id']){
                                    areas_html += "<option selected value='"+data.list[k]['area_id']+"'>"+data.list[k]['area_name']+"</option>";
                                }else{
                                    areas_html += "<option value='"+data.list[k]['area_id']+"'>"+data.list[k]['area_name']+"</option>";
                                }
                            }
                            self.next().next().html('');
                            self.next().next().append(areas_html);
                            get_price()
                        },
                        error : function(){
                            tip_note("程序出现错误");
                        }
                    });

                    var tb_order_status = data.data.tb_order_status;
                    //订单信息
                    $("#tb_orders_info").html("");
                    var orders_html="<tr>";
                    orders_html +='<td style="vertical-align: middle; width: 15%;">'+outer_order_data['tid']+'</td>';
                    orders_html +='<td style="vertical-align: middle;  width: 10%;">'+outer_order_data['created']+' </td>';
                    orders_html +='<td style="vertical-align: middle;  width: 15%;">'+outer_order_data['buyer_nick']+'</td>';
                    orders_html +='<td style="vertical-align: middle;  width: 35%;">' +
                        '收货人：'+outer_order_data['receiver_name']+'<br>' +
                        '手机号：'+outer_order_data['receiver_mobile']+'<br>' +
                        '邮编：'+outer_order_data['receiver_zip']+'<br>' +
                        '区域：'+outer_order_data['receiver_state']+'-' +outer_order_data['receiver_city']+'-'+outer_order_data['receiver_district']+'<br>'+
                        '地址：'+outer_order_data['receiver_address']+
                        '</td>';

                    orders_html +='<td style="vertical-align: middle;  width: 10%;">￥'+outer_order_data['payment']+'</td>';
                    orders_html +='<td style="vertical-align: middle;  width: 15%;">'+tb_order_status[outer_order_data['status']]+'</td>';
                    $("#tb_orders_info").append(orders_html+' </tr>');


                    //商品信息
                    $("#goods_info").html("");
                    for ( var r = 0; r <obj_orders.length; r++){
                        var sku_properties_name= obj_orders[r]['sku_properties_name'];
                        var outer_sku_id =obj_orders[r]['outer_sku_id'];
                        if(!sku_properties_name){
                            sku_properties_name="";
                        }
                        if(!outer_sku_id){
                            outer_sku_id="";
                        }
                        var goods_html="<tr>";
                        goods_html +='<td style="vertical-align: middle;width: 16.66%;"><img class="img-thumbnail" width="100" height="100"  src="'+obj_orders[r]['pic_path']+'"></td>';
                        goods_html +='<td style="vertical-align: middle; width: 16.66%;">'+obj_orders[r]['title']+ '<br>货号：'+outer_sku_id+' </td>';
                        goods_html +='<td style="vertical-align: middle;width: 10.66%;">'+sku_properties_name+'</td>';
                        goods_html +='<td style="vertical-align: middle;width: 10.66%;">￥'+obj_orders[r]['price']+'</td>';
                        goods_html +='<td style="vertical-align: middle;width: 10.66%;">'+obj_orders[r]['num']+'</td>';
                        goods_html +='<td style="vertical-align: middle;width: 10.66%;">'+tb_order_status[obj_orders[r]['status']]+'</td>';
                        $("#goods_info").append(goods_html+' </tr>');
                    }

                    //收货信息
                    $("#receiver").show();
                    $("#receiver_info").show();
                    //配送信息
                    $("#express").show();
                    //支付信息
                    $("#payment").show();
                    $("#payment_info").show();
                    //淘宝订单信息
                    $("#order_info1").show();
                    $("#order_info").show();
                    //淘宝订单商品信息
                    $("#goodsinfo1").show();
                    $("#goodsinfo").show();
                    //费用信息
                    $("#priceinfo").show();
                    $("#price").show();


                }
            },
            error:function () {
                tip_note("程序出现错误");
            }
        });

    });
    
    
    

    //审核作品
    review = function(id) {
        layer.confirm("作品审核通过?",{btn:['确定','取消']},
            function (index) {
                $.ajax({
                    url : '/works/review',
                    type : 'GET',
                    data : {
                        prj_id:id
                    },
                    dataType : 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success : function (data) {
                        if(data.status==200&&data.success=='true'){
                            layer.close(index);
                            loadTable();
                        }
                        if(data.status==404&&data.success=='false'){
                            layer.close(index);
                            layer.alert(data.message, {
                                skin: 'skin-class', //样式类名
                                closeBtn: 0
                            });
                        }
                    },
                    error:function () {
                        tip_note("程序出现错误");
                    }
                });
            });
    };

    //订购作品页面
    //数量减
    $("body").delegate(".min","click",function () {
        var nums = $(this).next().val()-1;
        if(nums<1){
            layer.msg('数量不能少于1');
            return;
        }
        var sku_price = parseFloat($(this).parent().parent().prev().children(":first-child").next().val());
        var total = parseFloat(nums*sku_price);
        $(this).next().val(nums);
        $(this).parent().parent().next().children(":first-child").text("￥"+total.toFixed(2));
        $(this).parent().parent().next().children().next().val(total.toFixed(2));

        Settotal();
    });

    //数量加
    $("body").delegate(".add","click",function () {
        var nums = parseFloat($(this).prev().val())+1;
        var sku_price = parseFloat($(this).parent().parent().prev().children(":first-child").next().val());
        var total = parseFloat(nums*sku_price);
        $(this).prev().val(nums);
        $(this).parent().parent().next().children(":first-child").text("￥"+total.toFixed(2));
        $(this).parent().parent().next().children().next().val(total.toFixed(2));
        Settotal();
    });
    //数量输入框
    $("body").delegate(".changenum","change",function () {
        var nums = $(this).val();
        if(nums<1){
            layer.msg('数量不能少于1');
            nums = 1;
            $(this).val(nums);
        }
        var sku_price = parseFloat($(this).parent().parent().prev().children(":first-child").next().val());
        var total = parseFloat(nums*sku_price);
        $(this).parent().parent().next().children(":first-child").text("￥"+total.toFixed(2));
        $(this).parent().parent().next().children().next().val(total.toFixed(2));
        Settotal();
    });

    //总价格
    function Settotal() {
        var price = 0;
        $.each($(".cot_prices"),function () {
            price += parseFloat($(this).val());
        });
        var delivery_fee = parseFloat($("#firstprice").val());
        var total_price = parseFloat(price+delivery_fee);
        $("#goods_price").text("￥"+price.toFixed(2)+"元");
        $("#goodsprice").val(price.toFixed(2));
        $("#total_price").text("￥"+total_price.toFixed(2)+"元");
        $("#totalprice").val(total_price.toFixed(2));

        if($("input[type='radio'][name='delivery']").length>0){
            get_price();
        }
    }

    //订购作品
    $("body").delegate(".btn-click","click",function () {
        var that = $(this);
        // var mobile = $("#receiver_phone").val();
        // var reg = /^1[3|4|5|7|8][0-9]{9}$/; //验证规则

        //收货信息为空判断
        if($("#receiver_name").val()=="" || $("#receiver_phone").val()=="" || $("#receiver_address").val()==""){
            layer.msg("请把收货人信息填写完整，带'*'为必填项");
            return;
        }

        //手机号验证
        // var flag = reg.test(mobile);
        // if(flag==false){
        //     layer.msg("请输入的正确的手机号码");
        //     return;
        // }

        //收货地区为空判断
        if($(".areas-area").find("option:selected").val()==0 || $(".areas-area").find("option:selected").val()=='区'){
            layer.msg("请选择收货地区");
            return;
        }

        //支付方式为空判断
        if($("body").find($("input[type='radio'][name='order_pay_id']:checked")).length==0){
            layer.msg("请先配置好支付方式再进行操作");
            return;
        }

        var postData = $(this).parents(".layui-layer").find('#form-save').serialize();
        var pay_name = $("input[type='radio'][name='order_pay_id']:checked").attr("data-value");

        if(pay_name.indexOf("alipay")>=0){
            layer.msg("该支付测试暂未通过,请不要选择该支付方式");
            return;
            layer.close(layer.index);
            parent.open("/works/orderSave?"+postData);

        }
        if(pay_name.indexOf("wxpay")>=0){
            layer.msg("该支付测试暂未通过,请不要选择该支付方式");
            return;
            parent.location.href="/works/orderSave?"+postData;
            layer.close(layer.index);
        }
        if(pay_name.indexOf("balance")>=0){

            if($("#is_open_pay").val()==1){
                layer.prompt({title: '请输入支付密码', formType: 1}, function(pass, index){
                    $.ajax({
                        url : '/works/checkPayword',
                        type : 'POST',
                        data : {
                            payword:pass
                        },
                        dataType : 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        success : function (data) {
                            if(data.status==200&&data.success=='true'){
                                layer.close(index);
                                that.attr("disabled","disabled");
                                $.ajax({
                                    url : "/works/orderSave",
                                    type : 'get',
                                    data : postData,
                                    dataType : 'JSON',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    },
                                    success : function (data) {
                                        if(data.status==200){
                                            var layerIndex = that.parents(".layui-layer").attr("times");
                                            layer.close(layerIndex);
                                            loadTable();
                                        }
                                        if(data.status==404){
                                            layer.msg(data.message);
                                        }
                                    },
                                    error:function () {
                                        tip_note("程序出现错误");
                                    }
                                });
                            }
                            if(data.status==404&&data.success=='false'){
                                layer.msg('支付密码输入错误，请重新输入');
                                return;
                            }
                        },
                        error:function () {
                            tip_note("程序出现错误");
                        }
                    });
                });
            }else{
                that.attr("disabled","disabled");
                $.ajax({
                    url : "/works/orderSave",
                    type : 'get',
                    data : postData,
                    dataType : 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success : function (data) {
                        if(data.status==200){
                            var layerIndex = that.parents(".layui-layer").attr("times");
                            layer.close(layerIndex);
                            loadTable();
                        }
                        if(data.status==404){
                            layer.msg(data.message);
                        }
                    },
                    error:function () {
                        tip_note("程序出现错误");
                    }
                });
            }
        }
        return false;
    });


    //监听区的变化
    $("body").delegate(".areas-area","change",function (){
        get_price();
    });


    function get_price() {
        //省id
        var pro_id = $(".areas-province").val();
        //市id
        var city_id = $(".areas-city").val();
        //区id
        var area_id = $(".areas-area").val();
        //作品id
        var works_id = $("#ids").val();
        var works = works_id.split(",");
        //总重量
        var total_weight =0;
        for(var i=0;i<works.length;i++){
            var id = works[i];
            var weight = $("#sku_weight"+id).val();
            var num = $("#numchange"+id).val();
            total_weight += weight*num;
        }
        //模板id
        var temp_ids = "";
        $(".temp_id").each(function () {
            if(temp_ids==""){
                temp_ids = $(this).val();
            }else{
                temp_ids = temp_ids+","+$(this).val();
            }
        });

        //获取快递
        $.ajax({
            url : '/works/getPrice',
            type: 'POST',
            data:{
                works_id:works_id,
                pro_id:pro_id,
                city_id:city_id,
                area_id:area_id,
                total_weight:total_weight,
                temp_id:temp_ids
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if(data.status==404){
                    layer.msg(data.message);
                    return;
                }
                var aa = $(".table-delivery").css("display");
                if (aa=="none") {
                    $(".table-delivery").show();
                    $(".table-delivery").append('<thead><tr><th class="thc">快递名称</th><th class="thc">描述</th><th class="thc">价格</th><th class="thc">选择</th></tr></thead><tbody></tbody>>');
                    for (var i = 1; i <= data.length; i++) {
                        var j = i - 1;
                        if(j==0){
                            $(".table-delivery tbody").append('<input type="hidden" name="delivery_temp_id" value="'+data[j].del_temp_id+'"/>');
                            var radio = '<input type="radio" id="deliveryIds" class="deliveryIds" name="order_delivery_id"  checked value="'+data[j].delivery_id+'" data-value="' + data[j].deli_price + '"/>';

                            var fee = parseFloat(data[j].deli_price);
                            var total_price = parseFloat($("#goodsprice").val());
                            var price = parseFloat(fee+total_price);
                            $("#firstprice").val(fee.toFixed(2));
                            $("#freight").text("￥"+fee.toFixed(2)+"元");
                            $("#total_price").text("￥"+price.toFixed(2)+"元");
                            $("#totalprice").val(price.toFixed(2));

                        }else{
                            var radio = '<input type="radio" id="deliveryIds" class="deliveryIds" name="order_delivery_id"  value="'+data[j].delivery_id+'" data-value="' + data[j].deli_price + '"/>';
                        }
                        $(".table-delivery tbody").append('<tr data-value="tr-' + j + '">' +
                            ' <td style="vertical-align: middle;"><span style="font-size: 10px">' + data[j].delivery_name + '</span></td> ' +
                            '<td style="vertical-align: middle;">' + data[j].delivery_desc + '</td> ' +
                            '<td style="vertical-align: middle;" data-value="d_price-' + j + '">￥ ' + data[j].deli_price + '</td> ' +
                            '<td style="vertical-align: middle;"  data-value="shipping_id-' + j + '" class="shipping_id">' + radio +
                            '</td>' +
                            ' </tr>');
                    }
                }
                else{
                    $(".table-delivery tbody").empty();
                    for (var i=1;i<=data.length;i++) {
                        var j=i-1;
                        if(j==0){
                            $(".table-delivery tbody").append('<input type="hidden" name="delivery_temp_id" value="'+data[j].del_temp_id+'"/>');
                            var radio = '<input type="radio" id="deliveryIds" class="deliveryIds" name="order_delivery_id"  checked value="'+data[j].delivery_id+'" data-value="' + data[j].deli_price + '"/>';

                            var fee = parseFloat(data[j].deli_price);
                            var total_price = parseFloat($("#goodsprice").val());
                            var price = parseFloat(fee+total_price);
                            $("#firstprice").val(fee.toFixed(2));
                            $("#freight").text("￥"+fee.toFixed(2)+"元");
                            $("#total_price").text("￥"+price.toFixed(2)+"元");
                            $("#totalprice").val(price.toFixed(2));

                        }else{
                            var radio = '<input type="radio" id="deliveryIds" class="deliveryIds" name="order_delivery_id"  value="'+data[j].delivery_id+'" data-value="' + data[j].deli_price + '"/>';
                        }
                        $(".table-delivery tbody").append('<tr data-value="tr-'+j+'">' +
                            ' <td style="vertical-align: middle;"><span style="font-size: 10px">'+data[j].delivery_name+'</span></td> ' +
                            '<td style="vertical-align: middle;">'+data[j].delivery_desc+'</td> ' +
                            '<td style="vertical-align: middle;" data-value="d_price-'+j+'">￥ '+data[j].deli_price+'</td> '+
                            '<td style="vertical-align: middle;"  data-value="shipping_id-'+j+'" class="shipping_id">' + radio + '</td>'+
                            ' </tr>');
                    }
                }
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    }


    $("body").delegate("#deliveryIds","click",function () {
        var delivery_fee = parseFloat($(this).attr("data-value"));
        var total_price = parseFloat($("#goodsprice").val());
        var price = parseFloat(delivery_fee+total_price);
        $("#firstprice").val(delivery_fee.toFixed(2));
        $("#freight").text("￥"+delivery_fee.toFixed(2)+"元");
        $("#total_price").text("￥"+price.toFixed(2)+"元");
        $("#totalprice").val(price.toFixed(2));
    });


    //查看异常
    $("body").delegate("#yicang","click",function () {
        var prj_id = $(this).attr("data-value");

        $.ajax({
            url : "/works/error",
            type : 'get',
            data : {prj_id:prj_id},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data.status==200){
                    if(data.data.length!=0){
                        var pageData = data.data;
                        var message = "";
                        for(var i=0;i<pageData.length;i++){
                            message += "页面名称:"+pageData[i]['prj_page_name']+";空图数量:"+pageData[i]['mask_empty_count']
                                +";像素不足图片数量:"+pageData[i]['maks_badpx_count']+"</br>";
                        }
                        layer.alert(message, {
                            skin: 'skin-class', //样式类名
                            area: ['400px','300px'], //宽高
                            closeBtn: 0
                        });
                    }else{
                        layer.alert("数据有问题,请联系技术人员", {
                            skin: 'skin-class', //样式类名
                            area: ['400px'], //宽高
                            closeBtn: 0
                        });
                    }

                }
                if(data.status==404){
                    layer.alert(data.message, {
                        skin: 'skin-class', //样式类名
                        area: ['400px'], //宽高
                        closeBtn: 0
                    });
                }
            },
            error:function () {
                tip_note("程序出现错误");
            }
        });

    })


    //手机预览
    $("body").delegate('#mobile_preview','mouseenter',function () {
        var id = $(this).attr("data-value");
        $('#qrcode'+id).qrcode({
            render: "canvas", //也可以替换为table
            width: 100,
            height: 100,
            text: $('#qrcode_url'+id).val()
        }
        );
        $('#qrcode'+id).show();
    });

    $("body").delegate('#mobile_preview','mouseleave',function () {
        var id = $(this).attr("data-value");
        $('#qrcode'+id).html('');
        $('#qrcode'+id).hide();
    });


});