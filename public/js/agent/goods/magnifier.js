$(document).ready(function(){
    var stock; //库存
    var now_num; //商品详情页-数量

    getPrice();

    jQuery.browser = {};
    (function () {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();
    // 图片上下滚动
    var count = $("#imageMenu li").length - 5; /* 显示 6 个 li标签内容 */
    var interval = $("#imageMenu li:first").width();
    var curIndex = 0;

    // 解决 ie6 select框 问题
    $.fn.decorateIframe = function(options) {
        if ($.browser.msie && $.browser.version < 7) {
            var opts = $.extend({}, $.fn.decorateIframe.defaults, options);
            $(this).each(function() {
                var $myThis = $(this);
                //创建一个IFRAME
                var divIframe = $("<iframe />");
                divIframe.attr("id", opts.iframeId);
                divIframe.css("position", "absolute");
                divIframe.css("display", "none");
                divIframe.css("display", "block");
                divIframe.css("z-index", opts.iframeZIndex);
                divIframe.css("border");
                divIframe.css("top", "0");
                divIframe.css("left", "0");
                if (opts.width == 0) {
                    divIframe.css("width", $myThis.width() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                if (opts.height == 0) {
                    divIframe.css("height", $myThis.height() + parseInt($myThis.css("padding")) * 2 + "px");
                }
                divIframe.css("filter", "mask(color=#fff)");
                $myThis.append(divIframe);
            });
        }
    }
    $.fn.decorateIframe.defaults = {
        iframeId: "decorateIframe1",
        iframeZIndex: -1,
        width: 0,
        height: 0
    }
    //放大镜视窗
    $("#bigView").decorateIframe();
    //点击到中图
    var midChangeHandler = null;

    $("#imageMenu li img").bind("click", function(){
        if ($(this).attr("id") != "onlickImg") {
            midChange($(this).attr("src").replace("small", "mid"));
            $("#imageMenu li").removeAttr("id");
            $(this).parent().attr("id", "onlickImg");
        }
    }).bind("mouseover", function(){
        if ($(this).attr("id") != "onlickImg") {
            window.clearTimeout(midChangeHandler);
            $(this).parent().parent().children().removeAttr("id")
            $(this).parent().attr("id", "onlickImg");
            midChange($(this).attr("src").replace("small", "mid"));
            $(this).css({ "border": "1px solid #0773fc" });
        }
    }).bind("mouseout", function(){
        if($(this).attr("id") != "onlickImg"){
            $(this).removeAttr("style");
            midChangeHandler = window.setTimeout(function(){
                midChange($("#onlickImg img").attr("src").replace("small", "mid"));
            }, 1000);
        }
    });
    function midChange(src) {
        $("#midimg").attr("src", src).on('load',function(){
            changeViewImg();
        });
    }
    //大视窗看图
    function mouseover(e) {
        if ($("#winSelector").css("display") == "none") {
            $("#winSelector,#bigView").show();
        }
        $("#winSelector").css(fixedPosition(e));
        e.stopPropagation();
    }
    function mouseOut(e) {
        if ($("#winSelector").css("display") != "none") {
            $("#winSelector,#bigView").hide();
        }
        e.stopPropagation();
    }
    $("#midimg").mouseover(mouseover); //中图事件
    $("#midimg,#winSelector").mousemove(mouseover).mouseout(mouseOut); //选择器事件

    var $divWidth = $("#winSelector").width(); //选择器宽度
    var $divHeight = $("#winSelector").height(); //选择器高度
    var $imgWidth = $("#midimg").width(); //中图宽度
    var $imgHeight = $("#midimg").height(); //中图高度
    var $viewImgWidth = $viewImgHeight = $height = null; //IE加载后才能得到 大图宽度 大图高度 大图视窗高度

    function changeViewImg() {
        $("#bigView img").attr("src", $("#midimg").attr("src").replace("mid", "big"));
    }
    changeViewImg();
    $("#bigView").scrollLeft(0).scrollTop(0);
    function fixedPosition(e) {
        if (e == null) {
            return;
        }
        var $imgLeft = $("#midimg").offset().left; //中图左边距
        var $imgTop = $("#midimg").offset().top; //中图上边距
        X = e.pageX - $imgLeft - $divWidth / 2; //selector顶点坐标 X
        Y = e.pageY - $imgTop - $divHeight / 2; //selector顶点坐标 Y
        X = X < 0 ? 0 : X;
        Y = Y < 0 ? 0 : Y;
        X = X + $divWidth > $imgWidth ? $imgWidth - $divWidth : X;
        Y = Y + $divHeight > $imgHeight ? $imgHeight - $divHeight : Y;

        if ($viewImgWidth == null) {
            $viewImgWidth = $("#bigView img").outerWidth();
            $viewImgHeight = $("#bigView img").height();
            if ($viewImgWidth < 200 || $viewImgHeight < 200) {
                $viewImgWidth = $viewImgHeight = 800;
            }
            $height = $divHeight * $viewImgHeight / $imgHeight;
            $("#bigView").width($divWidth * $viewImgWidth / $imgWidth);
            $("#bigView").height($height);
        }
        var scrollX = X * $viewImgWidth / $imgWidth;
        var scrollY = Y * $viewImgHeight / $imgHeight;
        $("#bigView img").css({ "left": scrollX * -1, "top": scrollY * -1 });
        $("#bigView").css({ "top": "0px", "left": $(".preview").offset().left + $(".preview").width() - 10 });

        return { left: X, top: Y };
    }

    // 图片放大镜 end

    //detail-商品属性选择效果
    $(".detail-forty-eight").click(function () {
        if($(this).hasClass("has")){
            $(this).removeClass("has");
            $(this).find("i").remove();
            $(this).removeAttr("style");
        }else{
            $(this).addClass("has")
            $(this).append("<i></i>")
            $(this).attr("style","border:1px solid red");
            $(this).siblings().removeClass("has");
            $(this).siblings().find("i").remove();
            $(this).siblings().removeAttr("style");
            var rel_attr_id = $(this).attr('data-id');
            $(this).parent().children(":first-child").val(rel_attr_id);
        }
        //数量重置为1
        $(".detail-fifty").val("1");
        //如果该属性没有被选择，则不填充数值
        if($(this).parent().find(".detail-forty-eight").hasClass("has")==false){
            $(this).parent().children(":first-child").val('');
        }
        getPrice();

    });

    //detail-数量框按钮 增加
    $(".detail-fifty-two").click(function () {
        prod_stock_status = $("#prod_stock_status").val();
        stock = parseInt($(".detail-fifty-five span").text());
        now_num = parseInt($(".detail-fifty").val());
        if(prod_stock_status==1){
            if(now_num >= stock){
                now_num = stock;
                $(".detail-fifty").val(now_num);
                layer.msg('该商品库存不足!');
            }else{
                $(".detail-fifty").val(now_num+1);
            }
        }else{
            $(".detail-fifty").val(now_num+1);
        }
        //计算价格
        if($("#product_price").attr("style")==""){
            $("#product_price").text("￥"+parseFloat($(".detail-fifty").val()*$("#product_price").attr("data-price")).toFixed(2));
        }
    });
    //减少
    $(".detail-fifty-four").click(function () {
        prod_stock_status = $("#prod_stock_status").val();
        stock = parseInt($(".detail-fifty-five span").text());
        now_num = parseInt($(".detail-fifty").val());
        if(now_num <= 1){
            now_num = 1;
            layer.msg('数量不能少于1');
            $(".detail-fifty").val(now_num);
        }else{
            $(".detail-fifty").val(now_num-1);
        }
        //计算价格
        if($("#product_price").attr("style")==""){
            $("#product_price").text("￥"+parseFloat($(".detail-fifty").val()*$("#product_price").attr("data-price")).toFixed(2));
        }
    });
    //数量框
    $("body").delegate("#nums","change",function () {
        prod_stock_status = $("#prod_stock_status").val();
        var stock = parseInt($(".detail-fifty-five span").text());
        var nums = $(this).val();
        nums = parseInt(nums);
        if(prod_stock_status==1){
            if(nums>stock){
                nums = stock;
                $(this).val(nums);
                layer.msg('数量已超过库存量');
            }
        }
        if(nums<1 || isNaN(nums)){
            layer.msg('数量不能少于1');
            nums = 1;
            $(this).val(nums);
        }
        $(this).val(nums);
        //计算价格
        if($("#product_price").attr("style")==""){
            $("#product_price").text("￥"+parseFloat($(this).val()*$("#product_price").attr("data-price")).toFixed(2));
        }
    });

    //Tab切换
    $(".nav_status_btn").on("click",function () {
        if($(this).attr("data-val") == 1) {
            $(".details").attr("style","display:none");
            $(".transaction").attr("style","display:none");
            $(".comment").attr("style","display:block");
        }else if ($(this).attr("data-val") == 2){
            $(".details").attr("style","display:none");
            $(".comment").attr("style","display:none");
            $(".transaction").attr("style","display:block");
        }else{
            $(".details").attr("style","display:block");
            $(".comment").attr("style","display:none");
            $(".transaction").attr("style","display:none");
        }
    });

    //制作链接
    $("#makeURL").click(function () {
        var num = $("#attribute-detail").find("input[data-value='numbers']").length;
        var length = $("#rel_attr_ids").val().split(",").length;
        if(length==1){
            if($("body").find('.has').length==1){
                length = 1;
            }
            else{
                layer.msg("请把商品属性选择完整！");
                return;
            }
        }

        if(length==num) {
            var prod_id = $("#prod_id").val();
            var prod_attr_comb = $("#rel_attr_ids").val();
            var comprint_flag = $("#comprint").val();
            $.ajax({
                url: "/goods/detail/tips",
                type: 'POST',
                data: {
                    prod_id: prod_id,
                    prod_attr_comb:prod_attr_comb,
                    comprint_flag:comprint_flag
                },
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function (data) {
                    layer.open({
                        type: 1,
                        title: false,
                        closeBtn: 0,
                        resize: false,
                        shade: 0.1,
                        area: ['600px', '183px'],
                        skin: "success-skin",
                        content: data.html,
                        zIndex: layer.zIndex,
                        success: function (layero, index) {

                        }
                    });
                }
            });
        }else{
            layer.msg("请把商品属性选择完整！");
        }
    });

    //模板市场
    $("#maketemplate").click(function () {
        var num = $("#attribute-detail").find("input[data-value='numbers']").length;
        var length = $("#rel_attr_ids").val().split(",").length;
        var comprint_flag = $("#comprint").val();
        if(length==1){
            if($("body").find('.has').length==1){
                length = 1;
            }
            else{
                layer.msg("请把商品属性选择完整！");
                return;
            }
        }
        if(length==num){
            var prod_id = $("#prod_id").val();
            var prod_attr_comb = $("#rel_attr_ids").val();
            var mid = $("#mid").val();
            var aid = $("#aid").val();
            if(comprint_flag == 'YS'){
              parent.open("/goods/detail/comltemplate?mid="+mid+"&aid="+aid+"&prod_id="+prod_id+"&prod_attr_comb="+prod_attr_comb);
            }else {
              parent.open("/goods/detail/template?mid="+mid+"&aid="+aid+"&prod_id="+prod_id+"&prod_attr_comb="+prod_attr_comb);
            }

        }else{
            layer.msg("请把商品属性选择完整！");
        }
    });

    //稿件上传
    $("#workUpload").click(function () {
        var num = $("#attribute-detail").find("input[data-value='numbers']").length;
        var length = $("#rel_attr_ids").val().split(",").length;
        if(length==1){
            if($("body").find('.has').length==1){
                length = 1;
            }
            else{
                layer.msg("请把商品属性选择完整！");
                return;
            }
        }

        if(length==num) {
            var prod_id = $("#prod_id").val();
            var prod_attr_comb = $("#rel_attr_ids").val();
            var user_id = $("#aid").val();
            var mch_id = $("#mid").val();
            parent.open("/goods/detail/work_upload?mid="+mch_id+"&a="+user_id+"&prod_id="+prod_id+"&prod_attr_comb="+prod_attr_comb);
        }else{
            layer.msg("请把商品属性选择完整！");
        }
    });
    //加入购物车
    $(".btn-add-cart").click(function () {
        var num = $("#attribute-detail").find("input[data-value='numbers']").length;
        var length = $("#rel_attr_ids").val().split(",").length;

        //判断为sku还是spu
        var price_type = $("#rel_attr_ids").attr("data-type");

        if (price_type == 'sku')
        {
            if(length==1){
                if($("body").find('.has').length==1){
                    length = 1;
                }
                else{
                    layer.msg("请把商品属性选择完整！");
                    return;
                }
            }
        }else{
            //spu商品无需以上判断
            num = 1;
        }
        //获取选择的数量
        var prod_num = $(".prod_nums").val();

        if(length==num) {
            var prod_id = $("#prod_id").val();
            var prod_attr_comb = $("#rel_attr_ids").val();
            $.ajax({
                url: "/goods/detail/add_cart",
                type: 'POST',
                data: {
                    prod_id: prod_id,
                    prod_attr_comb:prod_attr_comb,
                    price_type:price_type,
                    prod_num:prod_num,
                },
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == '404')
                    {
                        tip_note(data.message);
                        return;
                    }
                    location.href='/orders/cart';
                },
                error:function () {
                    tip_note("程序出现错误")
                }
            });
        }else{
            layer.msg("请把商品属性选择完整！");
        }
        
    })


    function getPrice() {
        //每次清空被选中属性的具体数值
        $("#rel_attr_ids").val("");

        //判断所有属性是否都有数值
        $("#attribute-detail").find("input[data-value='numbers']").each(function () {
            if($(this).val()!=""){
                var rel_attr_ids = $("#rel_attr_ids").val();
                if(!rel_attr_ids){
                    $("#rel_attr_ids").val($(this).val());
                }else{
                    $("#rel_attr_ids").val(rel_attr_ids+","+$(this).val());
                }
            }
        });


        //如果所有属性都被选中了，则发起请求获取该货品的价格
        var num = $("#attribute-detail").find("input[data-value='numbers']").length;
        var length = $("#rel_attr_ids").val().split(",").length;
        if(length==1){
            if($("body").find('.has').length==1){
                length = 1;
            }
            else{
                return;
            }
        }
        if(length==num){
            var prod_id = $("#prod_id").val();
            $.ajax({
                type: 'POST',
                url: '/goods/detail/getPrice',
                dataType: "json",
                data: {
                    'prod_id':prod_id,
                    'rel_attr_id':$("#rel_attr_ids").val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data) {
                    if(data.status == 200){
                        $(".detail-ninety-one").show();
                        $(".detail-fifty-seven").hide();
                        $("#product_prices").hide();
                        $("#product_price").text("￥"+data.price);
                        $("#product_price").attr("data-price",data.price);
                        $("#product_price").show();

                    }else{
                        $("#product_price").hide();
                        $("#product_prices").show();
                        $(".detail-ninety-one").hide();
                        $(".detail-fifty-seven").show();
                    }
                },
                error:function(res){
                    console.log(res);
                }
            });
        }else{
            $("#product_price").hide();
            $("#product_prices").show();
        }
    }
});