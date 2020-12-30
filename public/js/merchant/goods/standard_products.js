var loadProductsTable;
var createList;
$(function(){
    'use strict';


    //点击模板分类触发
    $('body').delegate('.cate-item','click', function() {
        $('.cate-item').removeClass('cate-active');
        $(this).addClass('cate-active');
        var cate_item_id = $(this).attr("data-id");
        if (cate_item_id == "all"){
            cate_item_id = "";
        }

        $("#spec").html("");
        var fhtml = ' <option value="">全部</option>';
        $("#spec").append(fhtml);
        //联动规格
        $.ajax({
            url : '/goods/products/get_cate_size',
            type: 'POST',
            data:{
                cate_id:cate_item_id,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                $.each(data,function (index,value) {
                    var html = ' <option value="'+value.size_id+'">'+value.size_new_name+'</option>'
                    $("#spec").append(html);
                })
            },
            error : function(){

                console.log("error");
            }
        });
        loadProductsTable(1);
    });
    //翻页
    $('body').delegate('.fy','click', function() {
        var page = $(this).attr('data-page');
        if(typeof(page)  == 'undefined' || page=='') {
            return false;
        }
        loadProductsTable(page);

    });
    //选取规格
    $('body').delegate('#spec','change', function() {
        loadProductsTable(1);

    });
    //输入查询
    $('body').delegate('#search-value-search','click', function() {
        loadProductsTable(1);
    });
    $('body').delegate('.temp-list','click', function() {

        if ($(this).find(".main-temp-list").hasClass("img-active"))
        {
            $(this).find(".main-temp-list").removeClass('img-active')
        }else{
            $(this).find(".main-temp-list").addClass('img-active');
        }


    });




    loadProductsTable = function (page) {
        //获取查询参数
        //分类
        var cate_item_id = $(".cate-active").attr("data-id");
        if (cate_item_id == "all")
        {
            cate_item_id = ""
        }
        //规格
        var product_s_size = $("#spec").val();
        if (product_s_size == "all")
        {
            product_s_size = ""
        }
        //商品名称
        var product_name = $("#search-value").val();

        var search = {};
        search['cate_id'] = cate_item_id;
        search['size_id'] = product_s_size;
        search['product_name'] = product_name;
        search['page'] = page;

        $("#main1").html("");
        $(".item-loading").show();
        $('.fy').hide();
        //获取商品列表
        $.ajax({
            url : '/goods/products/get_standard_product_list',
            type: 'POST',
            data:{
                search:search,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(ret){
                $(".item-loading").hide();


                console.log(ret);

                var html = createList(ret.data);
                $("#main1").html(html);

                if(ret.data.total > ret.data.limit)
                {
                    $('.fy').show();
                } else {
                    $('.fy').hide();
                }

                if(ret.data.pagesize  <= ret.data.page) {
                    $("#next").css('cursor' , 'not-allowed');
                    $("#next").attr('data-page', '');
                    $("#prev").css('cursor' , 'pointer');
                    $("#prev").attr('data-page', parseInt(ret.data.page)-1);
                }else if(ret.data.page >1 && ret.data.page <ret.data.pagesize ){
                    $("#prev").css('cursor' , 'pointer');
                    $("#prev").attr('data-page', parseInt(ret.data.page)-1);
                    $("#next").css('cursor' , 'pointer');
                    $("#next").attr('data-page', parseInt(ret.data.page)+1);
                }

                if(ret.data.page  <= 1) {
                    $("#prev").css('cursor' , 'not-allowed');
                    $("#prev").attr('data-page', '');
                    $("#next").css('cursor' , 'pointer');
                    $("#next").attr('data-page', parseInt(ret.data.page)+1);
                }
            },
            error : function(e){

                console.log(e);
            }
        });

    }

    //列表数据渲染
    createList = function (data)
    {
        var html = "";
        if(data.total == 0) {
            return '<div style="margin-top:20%;margin-left:45%">暂无数据</div>';
        }
            $.each(data.list, function(k,v) {
                html += '<div class="temp-list" data-id="'+v.prod_id+'" data-name="'+v.prod_name+'"><div class="main-temp-list" style="display: inline-block;padding-top: 20px""><img src="'+v.prod_main_thumb+'" style="width:110px;"><p style="text-align: center">'+v.prod_name+'</p></div></div>';
            });
        return html;
    }

    //点击添加标准化商品
    $('body').delegate('#setting','click', function() {
        var that = $(this);
        var goods_id = [];
       $.each( $(".img-active"),function (k,v) {
           console.log($(this).parents(".temp-list").attr("data-id"))
           goods_id.push($(this).parents(".temp-list").attr("data-id"));
       });

       if (goods_id.length == 0){
           tip_note("请选择要添加的商品")
           return;
       }
       goods_id = JSON.stringify(goods_id);
        var waiting_index = layer.msg('添加中,请稍候', {
            shade: 0.4,
            time:false //取消自动关闭
        });
        var layerInner = that.parents(".layui-layer").attr("times");

        //添加商品
        //获取商品列表
        $.ajax({
            url : '/goods/products/add_standard_new_product',
            type: 'POST',
            data:{
                goods_id:goods_id,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(ret){
                if (ret.success== "true")
                {
                    //添加成功
                    layer.close(waiting_index);
                    tip_note("添加成功",'success');
                    setTimeout(function () {
                        layer.close(layerInner);
                    },1000)
                    loadTable();
                }else{
                    //添加失败
                    layer.close(waiting_index);
                    tip_note("添加失败");
                    setTimeout(function () {
                        layer.close(layerInner);
                    },1000)

                }
            },
            error : function(e){
                tip_note("添加失败,程序出现错误");
            }
        });




        console.log(goods_id);
       return;


    });




})