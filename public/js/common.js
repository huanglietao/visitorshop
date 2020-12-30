/**
 * 框架内iframe 公共处理的js
 * Created by yanxs on 2019/8/1.
 */
var tip_success ;
var tip_warn ;
var loadTable;
var loadTableList;
var getFormData;
var formCallback;
var tip_note;
$(function(){
    'use strict';


    //统计数量时执行方法
    function statusCount() {
        var url = $('.needCount').attr('data-action');
        $.ajax({
            url : url,
            type: 'POST',
            data:{},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (data) {
                if(data.status===200){
                    $.each(data.data, function(i,val){
                        $("div[data-val='"+i+"']").text(val);
                    });
                }else{
                    layer.msg('统计出现错误!');
                }

            }
        });
    }

    //table列表加载
    loadTable = function(search)
    {
        //如果存在，表示该状态栏需要统计数量
        if($("body").find('.needCount').length==1){
            statusCount();
        }
        if ($('.no-border-table').attr('data-url')) {
            var url = $('.no-border-table').attr('data-url');

            //加入加载标示
            var html = '<div class="loading" style="text-align: center;margin-top:10px"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>';

            $('.no-border-table').after(html);
            loadTableList(url, search);

        }


    };

    //加载记录列表
    loadTableList = function(url, search){
        $(".tbl-content").html('');
        $.ajax({
            url :url,
            type: 'POST',
            data : search,
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                var data = res['data'];

                $(".loading").hide();
               // console.log(data);
                $(".tbl-content").html(data.html);

                //判断有无全选按钮 by hlt
                if ($(".checkall"))
                {
                    $(".checkall").prop("checked",false);  //取消全选
                }

                //渲染分页数据
                $("#list-total").html(data.total); //总数

                var limit = parseInt($("#pages-limit").val());
                if(isNaN(limit)) {
                    limit = 10;
                }

                //页数
                var pages = Math.ceil(data.total/limit);
                $("#limit_pages").text(pages);// 分多少页数.d
                $("#total_pages").val(pages);
                if(pages==1 || pages==0){
                    $(".page-act").hide();
                    return;
                }else {
                    $(".paginate").show();
                    $(".page-act").show();
                }

                //当前页
                var  currentPage = 1;
                if(search !='' && typeof (search) !='undefined') {
                    if(typeof (search.page) == "undefined") {
                        currentPage = 1;
                    } else {
                        currentPage = search.page;
                    }
                }

                if(currentPage == 1) { //第1页时上一页和首页不能操作
                    $(".pages-first").addClass('disabled');
                    $(".pages-prev").addClass('disabled');
                    $(".pages-next").removeClass('disabled');
                    $(".pages-last").removeClass('disabled');
                }else if(currentPage >=pages) {
                    $(".pages-next").addClass('disabled');
                    $(".pages-last").addClass('disabled');
                    $(".pages-first").removeClass('disabled');
                    $(".pages-prev").removeClass('disabled');
                } else {
                    $(".pages-first").removeClass('disabled');
                    $(".pages-prev").removeClass('disabled');
                    $(".pages-next").removeClass('disabled');
                    $(".pages-last").removeClass('disabled');
                }


                var option = "";
                var selected  = '';
                for (var i=1; i<=pages; i++) {
                    if(currentPage == i) {
                        selected = "selected"
                    } else {
                        selected = ""
                    }
                    option += "<option "+selected+" value='"+i+"'>第"+i+"页</option>";
                }

                $("#pages-item").html(option);

            },
            error : function(){

            }
        });
    };
    //格式化搜索表单数据
    getFormData = function($form) {
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};

        $.map(unindexed_array, function (n, i) {
            indexed_array[n['name']] = n['value'];
        });

        return indexed_array;
    }

    //页面改变时重新定位菜单选中状态
    var pathInfo = window.location.pathname;

    //循环匹配菜单项
    $(".nav-item>.nav-link", parent.document).each(function(i,v){
        var curUrl = $(this).attr('data-url');
        if(pathInfo == curUrl && !$(this).hasClass('active')) {
            console.log($(this))
            parent.$(this).trigger("click",'true'); //由iframe触发。
        }

    })

    var logs_index = '';

    //帮助组件相关js，把固定部分移出

    $('.help-tips').mouseenter(function(){
        var title = $(this).attr('data-title')
        logs_index =layer.tips('<span style="font-size: 10px">'+title+'</span>', $(this), {
            tips: [1, '#111110'], //还可配置颜色
            time: 0,
            area: ['300px', 'auto']
        });
    }).mouseleave(function(){
        layer.close(logs_index);
    })
    $("body").delegate(".hover-show,tips-modal","mouseenter",function () {
        var modalWidth = $(this).find('.tips-modal').width();
        var py = (modalWidth- $(this).width())/2;
        /*console.log(modalWidth);
        console.log(modalWidth);
        console.log(py)*/
        $(this).find('.tips-modal').css('left','-'+py+'px')
        $(this).find('.tips-modal').show();
    })
    $("body").delegate(".hover-show,tips-modal","mouseleave",function () {
        $(this).find('.tips-modal').hide();
    })
    /*$('.hover-show,tips-modal').mouseleave(function(){
        //console.log($(this).nextAll('.tips-modal'));
        var modalWidth = $(this).find('.tips-modal').width();
        var py = (modalWidth- $(this).width())/2;
        console.log(modalWidth);
        console.log(modalWidth);
        console.log(py)
        $(this).find('.tips-modal').css('left','-'+py+'px')
        $(this).find('.tips-modal').show();
    }).mouseleave(function(){
        $(this).find('.tips-modal').hide();
    })*/



    //button操作开始

    //============进入时自动加载table ========//
    loadTable();

    $("body").delegate(".btn-refresh","click",function () {
        //刷新当前列表
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        obj.limit = $("#pages-limit").val();
        obj.page = $("#pages-item").val();
        loadTable(obj);
    })

    $("body").delegate(".table-rest","click",function () {

        var obj = {status:''};
        if($('.tab_val').val() != undefined){
            //在当前tab栏目刷新表格
            var tj = JSON.stringify(obj);
            obj = JSON.parse(tj);
            obj.limit = $("#pages-limit").val();
            //在当前tab栏目搜索,没有tab则跳过
            obj.status = $('.tab_val').val()

            //刷新当前列表
            loadTable(obj);

        }else{
            //刷新当前列表
            loadTable();
        }
    })

    $(document).ready(function () {
        if ( $(".main-search").find("#reservationtime"))
        {
            rangedatapicker();
        }
    });


    //================button操作结束  ========//


    //==================分页相关js开始 =========//
    $("body").delegate("#pages-item","change",function () {
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        var status = $('.nav_status_current').attr('data-val');
       // var pages = $(this).val();
        obj.limit = $("#pages-limit").val();
        obj.page = $(this).val();
        if(status != undefined){
            obj.status = status;
        }
        loadTable(obj);
    });

    $("body").delegate("#pages-limit","change",function () {

        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        var status = $('.nav_status_current').attr('data-val');
        obj.limit = $(this).val();
        if(status != undefined){
            obj.status = status;
        }
        loadTable(obj);
    });
    //首页
    $("body").delegate(".paginate .pages","click",function () {
        if($(this).hasClass("disabled")){
            return ;
        }
        var curPage = $("#pages-item").val();
        var flag = $(this).attr('data-flag');
        var status = $('.nav_status_current').attr('data-val');

        var pageSize = parseInt($("#total_pages").val());

        switch (flag) {
            case "prev" :
                curPage--;
                break;
            case "next" :
                curPage++;
                break;
            case 'first' :
                curPage = 1;
                break;
            case 'last' :
                curPage = pageSize;
                break;
            default : curPage = 1;break;

        }

        if(curPage < 1 || curPage > pageSize) {
            return ;
        } else {
            var tj = JSON.stringify(getFormData($("form#search-form")));
            var obj = JSON.parse(tj);
            obj.limit = $("#pages-limit").val();
            obj.page = curPage;
            if(status != undefined){
                obj.status = status;
            }
            loadTable(obj);
        }
    })


    //======================分页js结束 =============//
    //条件查询
    $("body").on('submit',"#search-form", function(){
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        obj.limit = $("#pages-limit").val();

        if($('.tab_val').val() != undefined){
            //在当前tab栏目搜索,没有tab则跳过
            obj.status = $('.tab_val').val()
        }

        loadTable(obj);
        return false;
    })




   //操作提示的js
    $("#explanationZoom").on("click",function(){
        var explanation = $(this).parents(".explanation");
        var width = $(".explanation").parents().width();
        var isign = $(this).attr("aria-hidden");

        if(isign=='false'){
            $(this).removeClass("fa-plus").addClass("fa-minus");
            $(this).attr("title","收起提示");
            $(this).attr("aria-hidden",true)
            //    explanation.find(".ex_tit").css("margin-bottom",10);
            explanation.animate({
                width:width
            },300,function(){
                $(".explanation").find(".ex_descrition").show();
            });
        }else{
            $(this).addClass("fa-plus").removeClass("fa-minus");
            $(this).attr("title","提示相关设置操作时应注意的要点");
            $(this).attr("aria-hidden",false)
            //  explanation.find(".ex_tit").css("margin-bottom",0);
            explanation.animate({
                width:"140"
            },300);
            explanation.find(".ex_descrition").hide();
        }
    });

    //新页面弹窗功能
    $('body').delegate(".btn-dialog",'click',function () {

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
                            // $(".areas-province").append(provinces_html);
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
                                // $(".areas-city").html('');
                                // $(".areas-area").html('<option>区</option>');
                                // $(".areas-city").append(city_html);
                                self.next().html('');
                                // self.next().next().html('<option>区</option>');
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
                                // $(".areas-area").html('')
                                // $(".areas-area").append(areas_html);

                                self.next().next().html('');
                                self.next().next().append(areas_html);
                            },
                            error : function(){
                                console.log("error");
                            }
                        });
                    }
                }

            },
            error : function(){

            }
        });
    });
    //操作成功提示
    tip_success = function (url,text,title,interval) {
        title = title?title:"";
        interval = interval?interval:1;
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
    //提示触发
/*    $("body").delegate(".btn-tips","click",function () {
        var text = $(this).attr("data-text")?$(this).attr("data-text"):"";
        var interval = $(this).attr("data-interval")?$(this).attr("data-interval"):"";
        var title = $(this).attr("data-title")?$(this).attr("data-title"):"";
        tip_success(text,interval,title)
    })*/


    //删除警告提示
    //text:警告文本
    tip_warn = function (option,callback) {

        var text = option.text?option.text:"";
        var url = option.url?option.url:"";
        var title = option.title?option.title:"";
        var recover = option.recover?option.recover:'0';
        var is_comfirm = option.is_comfirm?option.is_comfirm:'1';
        var is_callback = 0;
        //判断是否有回调函数
        if (typeof callback === "function"){
            formCallback = callback;
            is_callback = 1;
        }

        $.ajax({
            url : "/tips_warn",
            type: 'POST',
            data:{
                text:text,
                url:url,
                title:title,
                recover:recover,
                is_callback:is_callback,
                is_comfirm:is_comfirm
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                layer.open({
                    type:1,
                    title:false,
                    closeBtn: 0,
                    resize : false,
                    shade:0.1,
                    area:['600px','183px'],
                    skin:"success-skin",
                    content: data.html,
                    zIndex: layer.zIndex,
                    success: function(layero, index){
                        layer.setTop(layero);
                        //刷新当前列表

                    }
                });


            },
            error : function(){

            }
        });
    };
    //警告触发
    $("body").delegate(".btn-del","click",function () {
        var option = {};
        option.text = $(this).attr("data-text")?$(this).attr("data-text"):"";
        option.url = $(this).attr("data-url")?$(this).attr("data-url"):"";
        option.title = $(this).attr("data-title")?$(this).attr("data-title"):"";
        option.recover = $(this).attr("data-recover")?$(this).attr("data-recover"):'0';
        option.is_comfirm = $(this).attr("data-comfirm")?$(this).attr("data-comfirm"):'1';
        tip_warn(option)
    });
    $("body").delegate("#del-comfirm","click",function () {

        var url = $(this).attr("data-url");

        $.ajax({
            url : url,
            type: 'GET',
            data:{
                url:url,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){

                if(data.success == 'true' && data.status == 201){
                    layer.msg(data.data)
                }else if(data.success == 'false') {
                    layer.msg(data.message)
                }
              var tj = JSON.stringify(getFormData($("form#search-form")));
              var obj = JSON.parse(tj);
              obj.limit = $("#pages-limit").val();
              obj.page = $("#pages-item").val();

                loadTable(obj);

                var back = $(".is_callback").val();
                if (back=="1"){
                    formCallback();
                }

                var name = $("#del-comfirm").parents(".layui-layer").attr("times");
                //先得到当前iframe层的索引
                layer.close(name);
            },
            error : function(){

            }
        });


    });




    //dilogo中的form提交处理
    $("body").delegate(".btn-submit","click",function () {

        var $form = $(this).parents(".layui-layer").find('form');
        var url = $form.attr('action');
        var that = $(this);
        $form.trigger("validate");


        //没有出现验证错误情况下才提交
        if($form.find('.form-control').hasClass('n-invalid')) {
            return false;
        }

        var postData = $(this).parents(".layui-layer").find('#form-save').serialize();
        if($('.service_exchange').val() == 'service'){
            //处理换货单提交时
            var str = '&job_responsibility='+$('.responsibility option:selected') .val()+'&job_handel_voucher='+$("input[name='job_handel_voucher']").val()+'&job_handle='+$('input[name="handle_type"]:checked').val()+''
            postData += str;
        }

        $.ajax({
            url : url,
            type : 'POST',
            data : postData,
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                if(data.code == 442 && data.success== "false") {
                    //错误验证,把错误显示和js的错误显示合并掉
                    for (var i in data.errors) {
                        var field = i;
                        //console.log(field);
                        $form.find("#"+field).removeClass('n-valid');
                        $form.find("#"+field).addClass('n-invalid');
                        $form.find("#"+field).parent().next().show();
                        $form.find("#"+field).parent().next().html(data.errors[field]);
                    }
                    return false;
                }else if (data.success== "false"){
                    layer.msg('',{
                        title: false,
                        content: data.message,
                        closeBtn:0,
                        offset: 'auto',
                        icon:5,
                        zIndex: layer.zIndex, //重点1
                        success: function(layero){
                            layer.setTop(layero); //重点2
                        }
                    });
                    return false;
                }else if(data.success == 'true' && data.status == 201){
                    tip_success('',data.data,'提示',2)
                    return false;
                }else if(data.success == 'true' && data.status == 202){
                    layer.msg('',{
                        title: false,
                        content: data.data,
                        closeBtn:0,
                        offset: 'auto',
                        icon:1,
                        zIndex: layer.zIndex, //重点1
                        success: function(layero){
                            layer.setTop(layero); //重点2
                        }
                    });
                    var tj = JSON.stringify(getFormData($("form#search-form")));
                    var obj = JSON.parse(tj);
                    obj.limit = $("#pages-limit").val();
                    obj.status = $(".tab_val").val();
                    //刷新当前列表
                    loadTable(obj);
                }
                var layerIndex = that.parents(".layui-layer").attr("times");
                console.log(that.parents(".layui-layer"));
                layer.close(layerIndex);
                $("#act-btn>.btn-refresh").trigger('click')
            }
        });
        return false;
    })

    $("body").delegate("#del-cancel","click",function () {
        layer.close(layer.index);
    });


    //dilogo中的form重置按钮
/*    $("body").delegate(".layui-layer-footer .btn-reset","click",function () {
        $(this).parents(".layui-layer-footer").siblings(".layui-layer-content").find("#form-save")[0].reset();
    });*/

    //搜索展开
    $("body").delegate(".search-more","click",function () {
        $(".search-open").slideDown(500);
        $(".btn-search").hide();
        $('.open-search-btn').show();
    })
})
    //搜索收起
    $("body").delegate(".search-more-hide","click",function () {
        $(".search-open").slideUp(500);
        $(".btn-search").show();
        $('.open-search-btn').hide();
    });

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

    //省市区组件 end

//时间组件 start

    function rangedatapicker() {
        var ranges = {};

        ranges['今天'] = [moment().startOf('day'), moment().endOf('day')];
        ranges['昨天'] = [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')];
        ranges['最近7天'] = [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')];
        ranges['最近30天'] = [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')];
        ranges['这个月'] = [moment().startOf('month'), moment().endOf('month')];
        ranges['上个月'] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];

        var options = {
            timePicker: false,
            autoUpdateInput: false,
            timePickerSeconds: true,
            timePicker24Hour: true,
            autoApply: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss',
                customRangeLabel: "Custom Range",
                applyLabel: "Apply",
                cancelLabel: "Clear",
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'
                ],
            },
            /* ranges: ranges,*/
        };
        var origincallback = function (start, end) {
            $(this.element).val(start.format(options.locale.format) + " - " + end.format(options.locale.format));
            $(this.element).trigger('blur');
        };

        $(".datetimerange").each(function () {
            var callback = typeof $(this).data('callback') == 'function' ? $(this).data('callback') : origincallback;
            $(this).on('apply.daterangepicker', function (ev, picker) {
                callback.call(picker, picker.startDate, picker.endDate);
            });
            $(this).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('').trigger('blur');
            });
            $(this).daterangepicker($.extend({}, options, $(this).data()), callback);
        });
    }


    //选择查询天数
    $("#main").delegate(".search-data-num","click",function () {
    var num = $(this).attr("data-num");
    var str;
    if (num == 1)
    {
        str = [moment().subtract(1, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss'), moment().subtract(1, 'days').endOf('day').format('YYYY-MM-DD HH:mm:ss')];
    }else if (num == 7){
        str = [moment().subtract(6, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss'), moment().endOf('day').format('YYYY-MM-DD HH:mm:ss')];
    }else{
        str = [moment().subtract(29, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss'), moment().endOf('day').format('YYYY-MM-DD HH:mm:ss')];
    }
    str = str.join(" - ");
    $(this).parent(".data-num").siblings(".reservationtime").find("#reservationtime").val(str);


})


//时间组件 end

    // 内容的状态导航栏切换
    $(".nav_status_btn").on("click",function () {
        $(this).addClass("nav_status_current").siblings(".nav_status_btn").removeClass("nav_status_current");
    })

    //提示框 by hlt
    tip_note = function (msg,type) {
        if (type == 'success'){
            layer.msg('',{
                title: false,
                content: msg,
                closeBtn:0,
                offset: 'auto',
                icon:1,
                zIndex: layer.zIndex, //重点1
                success: function(layero){
                    layer.setTop(layero); //重点2
                }
            });
        }else {
                layer.msg('',{
                    title: false,
                    content: msg,
                    closeBtn:0,
                    offset: 'auto',
                    icon:5,
                    zIndex: layer.zIndex, //重点1
                    success: function(layero){
                        layer.setTop(layero); //重点2
                    }
                });
        }


    }


   //处理公共面包屑和提示消息
   var url = window.location.href;
   $.ajax({
     url : '/getRuleRemarkAndBcrumb',
     type: 'POST',
     data:{url:url},
     dataType : 'JSON',
     headers: {
       'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
     },
     success : function(data){
         if(data.success=='true'){
             $('#bcrumb').html(data.data.breadcrumb);
             $('.ex_descrition').children('p').html(data.data.remark);
         }
         //console.log(data)
     },
    error : function(){
      console.log("error");
        }
   });





