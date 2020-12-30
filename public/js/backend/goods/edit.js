var getAllParentCate;
var getCategoryList;
var getAttribute;
var calcDescartes;
var getAttrCheckbox;
var getSkuHtml;
var setPageButton;
var pJudge;
var diffCheck;
$(function(){
    'use strict';

    //加载百度编辑器
    $(function(){
        UM.getEditor('pc_container', {
        });
        UM.getEditor('mob_container', {
        });
        //手机编辑器隐藏
        $("#mob_container").parents(".edui-container").hide();

        //
        $('.return_flag').selectpicker({
            'selectedText': 'cat'
        });
        $('.aftersale_flag').selectpicker({
            'selectedText': 'cat'
        });
        $('.comment_flag').selectpicker({
            'selectedText': 'cat'
        });

        //测试渠道定价,供货定价
        /*$(".good-category").hide();
        $(".good-info-fill").hide();
        $(".product-attr").show();
        $("input[type='text'][name = 'prod_name']").val('这是测试商品');
        $("input[type='radio'][name='prod_price_type'][value='2']").trigger('click');

        getAttribute(49,0);*/



    });
    $(document).keydown(function(event) {
        //按下回车键时触发，keyCode为13时，表示回车键
        if (event.keyCode == "13") {
            event.preventDefault();
        }
    });
    //全选
    $("body").delegate('.cc_checkall','click',function () {
        if ($(this).prop("checked")) {
            $("input[type='checkbox'][name='checkbox[]']").prop("checked",true);//全选
            $(".cc_checkall").prop("checked",true);//全选


            if ($(this).parents(".pa-table").length)
            {
                var onsale_arr = $("input[type='hidden'][name='sku_onsale[]']");
                $.each(onsale_arr,function (index,value) {
                    //获取选择的属性值id
                    $(this).val('1')
                });
            }


        } else {
            $("input[type='checkbox'][name='checkbox[]']").prop("checked",false);  //取消全选
            $(".cc_checkall").prop("checked",false);  //取消全选

            if ($(this).parents(".pa-table").length)
            {
                var onsale_arr = $("input[type='hidden'][name='sku_onsale[]']");
                $.each(onsale_arr,function (index,value) {
                    //获取选择的属性值id
                    $(this).val('0')
                });
            }
        }
    });
    //单击单选框
    $("body").delegate(".cc_checkedres","click",function () {
        var sumcheckbox = $("input[type='checkbox'][name='checkbox[]']").length; //获取所有复选框的总个数
        var sumcheckedbox = $("input[type='checkbox'][name='checkbox[]']:checked").length; //获取选中的总个数
        //对比是否相等就全部选中，否则非全选
        if (sumcheckbox == sumcheckedbox) {
            $(".cc_checkall").prop("checked",true);
            $(".cc_checkall").prop("checked",true);//全选
        }else{
            $(".cc_checkall").prop("checked",false);
            $(".cc_checkall").prop("checked",false);//取消全选
        }
        //



        var now_status = $(this).is(":checked");
        if (now_status){
            $(this).siblings("input[type='hidden'][name='sku_onsale[]']").val('1');
        }else{
            $(this).siblings("input[type='hidden'][name='sku_onsale[]']").val('0');
        }



    });
    //添加页面跳转
    $("body").delegate(".goods-add",'click',function () {
        var url = $(this).attr("data-url");

        window.location.href = url;
    })

    //页面加载时获取历史分类记录
    $(document).ready(function () {

        //hlt1
        //获取localstorage中的分类历史
        var admin_id = $(".admin_id").val();
        var cateHistory = JSON.parse(localStorage.getItem("category-"+admin_id));
        var html = "";
        $.each(cateHistory,function (index,value) {
            html='<option value="'+value.cate_id+'" data-cate-2 = "'+value.second_cate+'" data-cate-1 = "'+value.first_cate+'"> '+value.name+'</option>';
            $(".pgc-history").append(html);
        });

        //获取localstorage下的分类添加历史（之后添加商品成功后写入）
         /*var user = JSON.parse(localStorage.getItem("category-"+admin_id));
         if (!user)
         {
             user = {};
         }
         var obj = {"name":"测试分类","cate_id":"17",'first_cate':"4",'second_cate':"49"};
         user['17'] = obj;
         localStorage.setItem("category-"+admin_id,JSON.stringify(user));*/

    });
    //点击开启库存
    $("body").delegate("input[type = 'checkbox'][name = prod_stock_status]",'click',function () {
        if($(this).is(':checked'))
        {
            $(".stock-control").show();
            $(".stock-control-line").hide();
        }else{
            $(".stock-control").hide();
            $(".stock-control-line").show();
        }

    });

    //填写商品属性
    $("body").delegate(".btn-edit-good-attr",'click',function () {
        //前端验证

        //商品名称
        var prod_name = $("#prod_name").val();
        if (prod_name == "")
        {
            tip_note("请先填写商品名称")
            return;
        }
        //商品图片
        var prod_photo = $("input[type = 'hidden'][name = 'prod_photos']").val();
        if (prod_photo == ""){
            tip_note("请上传商品图片");
            return;
        }

        //选择了采用物流模板方式计算运费,则物流模板必选
        var delivery_type = $("input[type='radio'][name='prod_express_type']:checked").val();
        if (delivery_type == '2')
        {
            if ($("#prod_express_tpl_id").val()==""){
                tip_note("请先选择运费模板");
                return;
            }
        }

     /*   //销售渠道判断
        var sale_channle = $("input[type='checkbox'][name='sales_chanel[]']:checked").length;
        if (sale_channle == 0){
            tip_note("请至少勾选一个销售渠道");
            return;
        }*/

        //供货商渠道判断
        var supplier_qu = $("input[type='checkbox'][name='supplier[]']:checked").length;
        if (supplier_qu == 0){
            tip_note("请至少勾选一个供货商");
            return;
        }






        $(".good-info-fill").hide();
        $(".product-attr").show();

    })
    //返回填写商品信息
    $(".btn-return-good-attr").click(function () {
        $(".good-info-fill").show();
        $(".good-category").hide();
        $(".product-attr").hide();
    })
    //选择物流方式
    $("input[name='prod_express_type']").click(function () {
        var value = $(this).val();
        if (value=='1'){
            //固定运费
            $("#prod_express_fee").show();
            $(".express_type_span").show();
            //运费模板隐藏
            $("#prod_express_tpl_id").hide();
            $(".express_tpl_id").hide();
        }else{
            //运费模板
            $("#prod_express_fee").hide();
            $(".express_type_span").hide();
            //固定运费隐藏
            $("#prod_express_tpl_id").show();
            $(".express_tpl_id").show();
        }
    });

    //商品详情平台点击
    $(".c_u_new_add").click(function () {
        $(this).addClass("c_u_new_add_active").siblings(".c_u_new_add").removeClass("c_u_new_add_active");
        if ($(this).attr("data-value") == "pc"){
            //pc端详情,手机端详情编辑器隐藏
            $("#pc_container").parents(".edui-container").show();
            $("#pc_container").show();
            $("#mob_container").parents(".edui-container").hide();
            $("#mob_container").hide();
        }else{
            //手机端详情，pc端详情编辑器隐藏
            $("#pc_container").parents(".edui-container").hide();
            $("#pc_container").hide();
            $("#mob_container").parents(".edui-container").show();
            $("#mob_container").show();
        }
    });

    //点击是否增减p
    $("input[type='radio'][name='prod_is_add_page']").click(function () {
        setPageButton();
    });

    //选择价格类型（spu sku按钮）
    $("input[type='radio'][name='prod_price_type']").click(function () {
        var type = $(this).val();
        $(".now_price_type").val(type);
    });

    //自定义规格
    $("body").delegate('.custom-product-size','click',function () {
        if ($("#prod_size_id").val() == ""){
            tip_note("请先选择规格");
            $(this).attr("disabled",false);
            return;
        }

        var prod_id = $(this).attr('data-prod-id');
        var url = $(this).attr("data-url")+'?id='+$("#prod_size_id").val()+'&prod_id='+prod_id;
        var title = $(this).attr("data-title");
        var params = {

        };
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

            },
            error : function(){
                tip_note("程序出现错误");
            }
        });

    });
    //自定义规格保存
    $("body").delegate(".btn-custom-size-save",'click',function () {
        var $form = $(this).parents(".layui-layer").find('form');
        var url = $form.attr('action');
        var that = $(this);
        var postData = $(this).parents(".layui-layer").find('#form-save').serialize();
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
                }

                tip_note('生成自定义规格成功','success');
                var layerIndex = that.parents(".layui-layer").attr("times");

                layer.close(layerIndex);

            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
        return false;



    })

    //是否增减p按钮操作
    setPageButton = function () {
        //判断与当前的增减p是否相同
        var last_add_flag = $(".add_page_flag").val();
        var now_add_page = $("input[type='radio'][name='prod_is_add_page']:checked").val();
        var attr_length =$("input[type='checkbox'][name='attr_value']:checked").length;
        if (last_add_flag == now_add_page || attr_length == 0){

            var is_spu = $("input[type='radio'][name = prod_price_type]:checked").val();
            if ($("input[type='radio'][name='prod_is_add_page']:checked").val() == '1'){
                //可增减p 设置可加减p规则
                $(".prod_add_page").show();
                $(".prod_add_page_range").show();

                //隐藏p数属性
                $(".is-page-class").hide();
                //清空选择的p数属性
                $(".is-page-class").find("input[type='checkbox'][name='attr_value']").prop("checked",false)
                //判断是sku商品还是spu商品

                if (is_spu == '1')
                {
                    //spu商品b显示生成价格信息按钮
                    $(".prod_spu_but").show();
                    $(".pa-table").html("");
                }else{
                    $(".prod_spu_but").hide();
                }


            }else{
                //不可加减p 不设置加减p规则
                $(".prod_add_page").hide();
                $(".prod_add_page_range").hide();
                //p数规则input框清空
                $("input[type = 'number'][name = 'prod_add_page']").val('');
                //显示p数属性
                $(".is-page-class").show();
                $(".prod_spu_but").hide();
                //spu商品，重新生成价格信息
                if (is_spu == '1')
                {
                    getSkuHtml([],[],'')
                }
            }
            $(".add_page_flag").val(now_add_page);
        }else{
            //获取属性是否有被勾选

            if (attr_length>0){
                layer.confirm('切换增减p商品属性将重新加载,确定吗?', {
                    btn: ['确定', '取消'] //可以无限个按钮
                    ,cancel: function(index, layero){
                     //取消操作，点击右上角的X
                     //按钮【按钮二】的回调
                     layer.close(index);
                    $("input[type='radio'][name='prod_is_add_page']").prop("checked",false);
                    $("input[type='radio'][name='prod_is_add_page'][value="+last_add_flag+"]").prop("checked",true);
                    }
                },
                    function(index, layero){
                    //按钮【按钮一】的回调
                        var is_spu = $("input[type='radio'][name = prod_price_type]:checked").val();
                        if ($("input[type='radio'][name='prod_is_add_page']:checked").val() == '1'){
                            //可增减p 设置可加减p规则
                            $(".prod_add_page").show();
                            $(".prod_add_page_range").show();
                            //隐藏p数属性

                            $(".is-page-class").hide();
                            //清空选择的所有属性
                            $(".prod-sku-check").find("input[type='checkbox'][name='attr_value']").prop("checked",false)
                            //判断是sku商品还是spu商品

                            if (is_spu == '1')
                            {
                                //spu商品b显示生成价格信息按钮
                                $(".prod_spu_but").show();
                                getSkuHtml([],[],'')

                            }else{
                                $(".prod_spu_but").hide();
                                $(".pa-table").html("");
                            }
                        }else{
                            //不可加减p 不设置加减p规则
                            $(".prod_add_page").hide();
                            $(".prod_add_page_range").hide();
                            //p数规则input框清空
                            $("input[type = 'number'][name = 'prod_add_page']").val('');
                            //清空选择的所有属性
                            $(".prod-sku-check").find("input[type='checkbox'][name='attr_value']").prop("checked",false)
                            //显示p数属性
                            $(".is-page-class").show();
                            $(".prod_spu_but").hide();
                            //spu商品，重新生成价格信息
                            if (is_spu == '1')
                            {
                                getSkuHtml([],[],'')
                            }else{
                                $(".pa-table").html("");
                            }
                        }
                        $(".add_page_flag").val(now_add_page);
                        layer.close(index);


                }, function(index){
                    //按钮【按钮二】的回调
                    layer.close(index);
                    $("input[type='radio'][name='prod_is_add_page']").prop("checked",false);
                    $("input[type='radio'][name='prod_is_add_page'][value="+last_add_flag+"]").prop("checked",true);

                });
            }





        }





    }

    //spu商品显示价格信息按钮
    $("body").delegate(".btn-spu-attr-add","click",function () {
        var is_spu = $("input[type='radio'][name = prod_price_type]:checked").val();
        //获取是否增减p标识
        var is_add_page = $("input[type = 'radio'][name = 'prod_is_add_page']:checked").val();
        if (is_spu == '1' && is_add_page == "1"){


            //获取最小p数范围
            var min_p = $("input[type='number'][name = prod_min_add_page]").val();
            if (min_p == ""){
                tip_note("请先填写p数范围")
                return;
            }
            //获取p数规则
            var p_rule = $("input[type = 'number'][name = 'prod_add_page']").val();
             if (p_rule == ""){
                    tip_note("请先填写增减p规则数量")
                    return;
             }

            getSkuHtml([],[],'');


        }
    })

    //获取商品属性

    getAttribute = function (cate_id,is_add_page) {
        //填充区option
        $.ajax({
            url : '/goods/products/get_attribute',
            type: 'POST',
            data:{
                cate_id:cate_id,
                is_add_page:is_add_page
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                $(".pro_sku_comb").show();
                $(".product-sku").html(data.data.html)
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    };
    //sku显示收起
    $("body").delegate(".more-show",'click',function () {
        if ($(this).attr("data-action") == "show"){
            //展示被隐藏部分,
            $(this).find(".show-text").html("收起");
            $(this).find(".fa-angle-double-down").removeClass("fa-angle-double-down").addClass("fa-angle-double-up");
            $(this).attr("data-action","hide");
            $(".no-show-check").css("display",'inline-block');

        }else{
            //收起被隐藏部分
            $(this).find(".show-text").html("更多");
            $(this).find(".fa-angle-double-up").removeClass("fa-angle-double-up").addClass("fa-angle-double-down");
            $(this).attr("data-action","show");
            $(".no-show-check").css("display",'none');
        }
    })



    //生成货品
    $("body").delegate('.btn-attr-add','click',function () {
        /*console.log(calcDescartes([[1,2,3],['a','b','c'],['5','6','7']]));*/
        var data = getAttrCheckbox();
        var attr_id_arr = data.attr_id_arr;
        var attr_id_value_arr = data.attr_id_value_arr;
        var attr_p_value = data.attr_p_value_arr;


        getSkuHtml(attr_id_arr,attr_id_value_arr,attr_p_value);



    });
    //获取选中的sku值与属性id
    getAttrCheckbox = function () {
        var attr_arr = $("input[type='checkbox'][name='attr_value']:checked");
        var attr_id_arr = {};
        var attr_id_value_arr = {};
        var attr_p_value_arr =[];

        $.each(attr_arr,function (index,value) {
            //获取选择的属性值id
            var attr_id = $(this).val();
            //获取选择的属性值名称
            var attr_value = $(this).siblings('span').html();
            //获取对应的父级属性id
            var attr_pid = $(this).parents('.attr-child').siblings(".attr-pid").attr("data-id");
            //获取父级属性名称
            var attr_p_value = $(this).parents('.attr-child').siblings(".attr-pid").find(".attr_p_value").html();

            //组织id数组与值数组
            if (typeof(attr_id_arr[attr_pid])=="undefined"){
                attr_id_arr[attr_pid] = [];
                attr_id_value_arr[attr_pid] = [];
                attr_p_value_arr.push(attr_p_value);
            }
            attr_id_arr[attr_pid].push(attr_id);
            attr_id_value_arr[attr_pid].push(attr_value);


        });
        attr_p_value_arr = attr_p_value_arr.join(',');

        var data = {};
        data['attr_id_arr'] = attr_id_arr;
        data['attr_id_value_arr'] = attr_id_value_arr;
        data['attr_p_value_arr'] = attr_p_value_arr;


        return data;
    }
    //商品sku计算
    calcDescartes = function (array) {
        if (array.length < 2) return array[0] || [];
        return [].reduce.call(array, function (col, set) {
            var res = [];
            col.forEach(function (c) {
                set.forEach(function (s) {
                    var t = [].concat(Array.isArray(c) ? c : [c]);
                    t.push(s);
                    res.push(t);
                })
            });
            return res;
        });
    };
    diffCheck = function (being_sku_arr,now_sku_arr) {
        var diffArr = [];
        var sameArr = [];
        var same = 0;
        if (now_sku_arr.length == 0){
            //重新加载sku
            return {flag:1,array:being_sku_arr}
        }

        //那个长度长先循环哪个，才能取出适应各种情况的值
        if (now_sku_arr.length>being_sku_arr.length){
            var first_arr = now_sku_arr;
            var second_arr = being_sku_arr;
        }else{
            var first_arr = being_sku_arr;
            var second_arr = now_sku_arr;
        }


            $.each(first_arr,function (index,value) {
                same = 0;
                if (!$.isArray(value)){
                    value = value.split(',');
                }
                $.each(second_arr,function (b_index,b_value) {
                    //当b_value为字符穿时将它转为数组判断
                    if (!$.isArray(b_value)){
                        b_value = b_value.split(',');
                    }
                    if(value.sort().toString()==b_value.sort().toString())
                    {
                        //相同sku
                        same = 1;
                        sameArr.push(b_value);
                    }
                })
                if (same != 1){
                    //存储不同的数组
                    diffArr.push(value);
                }
            });

        //由于length机制问题,这里通过计算来记录数组长度
        var now_sku_len = 0;
        $.each(now_sku_arr,function () {
            now_sku_len++;
        });
        var being_sku_len = 0;
        $.each(being_sku_arr,function () {
            being_sku_len++;
        });


            if (sameArr.length!=0)
            {
                if (now_sku_len>being_sku_len)
                {
                    //减少
                    return {flag:4,array:diffArr}
                }else if (now_sku_len == being_sku_len){
                    //相同，不变
                    return {flag:3,array:[]}
                }else if(now_sku_len<being_sku_len){
                    //增加
                    return {flag:2,array:diffArr}
                }

                //减少
            }else{
                //重新加载sku
                return {flag:1,array:being_sku_arr}
            }



    }

    //获取计算后的sku数组并生成html
    //id_arr:属性id组合 例:[[25,26,27],[45,46,47]]
    //value_arr:属性值组合 例:[[a,b,c],[a,c,d]]
    //id_p_value:第一个tr显示的属性值名称
    getSkuHtml = function (id_arr,value_arr,id_p_value) {
        var cal_id_arr = [];
        var cal_value_arr = [];

        $.each(id_arr,function (index,value) {
           cal_id_arr.push(value);
        });
        $.each(value_arr,function (index,value) {
            cal_value_arr.push(value);
        });
        var cal_id_res = calcDescartes(cal_id_arr);
        var cal_value_res = calcDescartes(cal_value_arr);





        //组织html
        //获取为spu商品还是sku商品
        var is_spu = $("input[type='radio'][name = prod_price_type]:checked").val();
        //获取最小p数范围
        var min_p = $("input[type='number'][name = prod_min_add_page]").val();
        //获取最大p数
        var max_p = $("input[type='number'][name = prod_max_add_page]").val();
        //获取是否增减p标识
        var is_add_page = $("input[type = 'radio'][name = 'prod_is_add_page']:checked").val();



        if (is_spu == '1' && is_add_page =="0")
        {

        }else{

            var personal_print = $(".is_personal_print").val();

        if((min_p=="" || max_p=="") && personal_print=='1' && is_add_page == '1')
            {
                tip_note("请填写p数范围");
                return;
            }
        }






        if (is_add_page == "1")
        {
            if ((min_p<0&&min_p!="")||(max_p<0&&max_p!="")){
                tip_note("请先填写正确的p数范围");
                return;
            }
            //允许增减p
            //获取p数规则
            var p_rule = $("input[type = 'number'][name = 'prod_add_page']").val();
            if (p_rule == ""){
                tip_note("请先填写增减p规则数量");
                return;
            }
            //判断p数规则有没有超过范围
            if (parseInt(p_rule)> parseInt(max_p-min_p))
            {
                tip_note("p数规则超过了p数范围");
                return;
            }


        }
        var is_append = 0;

        //获取当前属性组合列表的sku属性值组合
        if ($("input[type='hidden'][name='attr_id[]']").length>0)
        {
            //当前已有sku列表
            var sku_arr = $("input[type='hidden'][name='attr_id[]']");
            var now_sku_arr = [];
            $.each(sku_arr,function () {
                now_sku_arr.push($(this).val().split(','));
            });

            //判断当前组合与即将生成列表的组合是否有相同的属性
            //有相同的属性组合则视为新增sku列表或者删减列表，不同则视为重新加载列表
            var new_cal_id = diffCheck(cal_id_res,now_sku_arr);
            //判断是否重新加载页面
            //flag[1:重载,2:新增,3:不变4:减少]
            if (new_cal_id.flag == 1){
                //重载 不做改动

            }else if (new_cal_id.flag==2){

                //新增,组织新增attr_id与attr_value
                var new_cal_value_res = [];
                $.each(new_cal_id.array,function (index,value) {
                    if (!$.isArray(value)){
                        value = value.split(',');
                    }
                    $.each(cal_id_res,function (c_index,c_value) {
                        if (!$.isArray(c_value)){
                            c_value = c_value.split(',');
                        }
                        if(value.sort().toString()==c_value.sort().toString())
                        {
                            //获取对应值
                            new_cal_value_res.push(cal_value_res[c_index]);
                        }
                    })
                });
                cal_id_res = new_cal_id.array;
                cal_value_res = new_cal_value_res;
                is_append = 1;
            }else if(new_cal_id.flag == 3){
                //不变 无需请求列表
                return;

            }else if(new_cal_id.flag == 4){
                //减少 无需请求列表 直接列表删除td
                $.each(new_cal_id.array,function (index,value) {
                    var str = value.join(',');
                    $("input[type='hidden'][name='attr_id[]'][value = '"+str+"']").parent('td').parent('tr').remove();

                })
                return;
             }
        }


        //判断是否个性影像类
        var is_personal_print = $(".is_personal_print").val();


        $.ajax({
            url : '/goods/products/get_sku_table',
            type: 'POST',
            data:{
                attr_id_arr: JSON.stringify(cal_id_res),
                attr_value_arr:JSON.stringify(cal_value_res),
                attr_p_value:id_p_value,
                is_sku:is_spu,
                min_p:min_p,
                is_add_page:is_add_page,
                p_rule:p_rule,
                is_personal_print:is_personal_print,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },

            success : function(data){
               if (data.success == 'false')
               {
                   tip_note(data.message);
               }else{
                   if (is_append == 1){
                       //添加

                       var html_all = data.data.html;
                       var html = $(html_all).find(".pa-content").html();

                       $(".pa-table").find(".pa-content").append(html);


                   }else{
                       $(".pa-table").html('').html(data.data.html);
                   }



               }

            },
            error : function(){
                tip_note("程序出现错误");
            }
    });


    };


    //修改最小p数
    $("body").delegate(".prod_min_add_page",'input propertychange',function () {
        var value=$(this).val();
        //获取是否增减p标识
        var is_add_page = $("input[type = 'radio'][name = 'prod_is_add_page']:checked").val();
        if (is_add_page == "1")
        {
            //修改第一列tr中的基准p的命名，不重新加载sku_table
            if ($(".add_p_tr_price"))
            {
                $(".add_p_tr_price").html(value+'P销售价');
            }
            if ($(".add_p_tr_cost")){
                $(".add_p_tr_cost").html(value+'P成本价');
            }
            if ($(".add_p_tr_weight")){
                $(".add_p_tr_weight").html(value+'P重量（克）');
            }
            if ($(".add_p_tr_spine_thickness")){
                $(".add_p_tr_spine_thickness").html(value+'P书脊厚度');
            }
        }

    });
    //修改p数规则
    $("body").delegate(".prod_p_rule",'input propertychange',function () {
        var value=$(this).val();
        //获取是否增减p标识
        var is_add_page = $("input[type = 'radio'][name = 'prod_is_add_page']:checked").val();

        //判断p数规则有没有超过范围

        if (is_add_page == "1")
        {
            //修改第一列tr中的基准p的命名，不重新加载sku_table
            if ($(".add_base_p_tr_price"))
            {
                $(".add_base_p_tr_price").html(value+'P销售价');
            }
            if ($(".add_base_p_tr_cost")){
                $(".add_base_p_tr_cost").html(value+'P成本价');
            }
            if ($(".add_base_p_tr_weight")){
                $(".add_base_p_tr_weight").html(value+'P重量（克）');
            }
            if ($(".add_base_p_tr_spine_thickness")){
                $(".add_base_p_tr_spine_thickness").html(value+'P书脊厚度');
            }
        }

    });

    //渠道定价
    $("body").delegate(".sales_price-form","click",function () {
        var param = {};
        //获取商品名称
        param['prod_name'] =$("input[type='text'][name = 'prod_name']").val();
        //获取为spu商品还是sku商品
        param['is_spu'] = $("input[type='radio'][name = prod_price_type]:checked").val();
        if (param['is_spu'] == '2'){
            //sku获取商品属性
            param['prod_attr_id'] = $(this).parent('td').siblings("td").find(".cc_checkedres").attr("data-value");
            param['prod_attr_value'] = $(this).parent('td').siblings("td").find(".prod-sku-value").html();
        }
        //获取商品货号
        param['prod_sn'] = $(this).parent('td').siblings("td").find(".prod_sku_sn").val();

        //获取渠道
        var channle_length = $("input[type='checkbox'][name='sales_chanel[]']:checked").length;
        var channle_arr = {};
        if (channle_length>0){

            $.each($("input[type='checkbox'][name='sales_chanel[]']:checked"),function (index,value) {
                channle_arr[index]={};
                channle_arr[index]['cha_id'] = $(this).val();
                channle_arr[index]['cha_flag'] = $(this).attr('data-value');
            });
        }
        param['channle'] = channle_arr;

        //获取最小p数范围
        var min_p = $("input[type='number'][name = prod_min_add_page]").val();
        //获取最大p数
        var max_p = $("input[type='number'][name = prod_max_add_page]").val();
        param['min_p'] = min_p;
        param['max_p'] = max_p;

        //是否加减p
        var is_add_page = $("input[type = 'radio'][name = 'prod_is_add_page']:checked").val();
        if (is_add_page == '1'){
            //获取p数规则
            param['p_rule'] = $("input[type = 'number'][name = 'prod_add_page']").val();
        }

        param['is_add_page'] = is_add_page;

        //判断是否已经编辑过
        if ($(this).siblings("input[type='hidden'][name='sale_channle_price[]']").val() != "" ){
            //重新显示编辑的内容
            param['is_edit'] = 1;
            param['sale_channle_price'] = $(this).siblings("input[type='hidden'][name='sale_channle_price[]']").val();
        }else{
            //编辑定价
            param['is_edit'] = 0;
        }
        var option = {};
        var re_index = layer.commonopen('','商品渠道定价',option);

        //获取这条记录的唯一标识
        param['only_flag'] = $(this).siblings(".uniqid-num").val();

        param['layer_index'] = re_index;
        console.log(param);


        $.ajax({
            url : '/goods/products/sales_price-form',
            type: 'POST',
            data:param,
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                $("#layui-layer"+re_index).find(".layui-layer-content").html(res.data.html);
                layer.layerfooter($("#layui-layer"+re_index),re_index);
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    });
    //渠道定价获取组织数据
    $("body").delegate(".btn-set-channle-price",'click',function () {
        var channle_price = {};
        //是否加减p
        var is_add_page = $("input[type = 'radio'][name = 'prod_is_add_page']:checked").val();

        var last_customer_id = 0;
        var last_channle_id = 0;
        $.each($("input[type='number'][name='customer-group']"),function (index,value) {
            var type = $(this).attr("data-type");
            var this_group = $(this);
            if (type == "start-price"){
                channle_price[index] = {};
                channle_price[index]['customer_id'] = $(this).attr("data-customer-id");
                channle_price[index]['channle_id'] = $(this).attr("data-channle-id");
                channle_price[index]['sku_sale_price_id'] = $(this).attr("data-price-id");
                channle_price[index]['channle_price'] = $(this).val();
            }else{
                $.each(channle_price,function (index,value) {
                    if(value.customer_id == this_group.attr("data-customer-id") && value.channle_id == this_group.attr("data-channle-id"))
                    {
                            channle_price[index]['channle_add_price'] = this_group.val();
                    }

                });
            }
        });


        var only_flag = $(this).attr('data-flag');
        $("input[type='hidden'][name='sale_channle_price[]'][class='sales_price_"+only_flag+"']").val(JSON.stringify(channle_price));
        tip_note('保存成功','success');
        var index = $(this).attr("data-value");
        layer.close(index);
    });




    //渠道,供货定价取消定价
    $("body").delegate(".cancel-set-price",'click',function () {
        var index = $(this).attr("data-value");
        layer.close(index);
    });

    //供货定价
    $("body").delegate(".supplier_price-form","click",function () {
        var param = {};
        //获取商品名称
        param['prod_name'] =$("input[type='text'][name = 'prod_name']").val();
        //获取为spu商品还是sku商品
        param['is_spu'] = $("input[type='radio'][name = prod_price_type]:checked").val();
        if (param['is_spu'] == '2'){
            //sku获取商品属性
            param['prod_attr_id'] = $(this).parent('td').siblings("td").find(".cc_checkedres").attr("data-value");
            param['prod_attr_value'] = $(this).parent('td').siblings("td").find(".prod-sku-value").html();
        }
        //获取商品货号
        param['prod_sn'] = $(this).parent('td').siblings("td").find(".prod_sku_sn").val();

        //获取渠道
        var supplier_arr = {};
            $.each($("input[type='checkbox'][name='supplier[]']:checked"),function (index,value) {
                supplier_arr[index]={};
                supplier_arr[index]['sup_id'] = $(this).val();
                supplier_arr[index]['sup_name'] = $(this).siblings("span").text();
            });

        param['supplier'] = supplier_arr;

        //获取最小p数范围
        var min_p = $("input[type='number'][name = prod_min_add_page]").val();
        //获取最大p数
        var max_p = $("input[type='number'][name = prod_max_add_page]").val();
        param['min_p'] = min_p;
        param['max_p'] = max_p;

        //是否加减p
        var is_add_page = $("input[type = 'radio'][name = 'prod_is_add_page']:checked").val();
        if (is_add_page == '1'){
            //获取p数规则
            param['p_rule'] = $("input[type = 'number'][name = 'prod_add_page']").val();
        }

        param['is_add_page'] = is_add_page;

        //判断是否已经编辑过
        if ($(this).siblings("input[type='hidden'][name='supplier_price[]']").val() != "" ){
            //重新显示编辑的内容
            param['is_edit'] = 1;
            param['supplier_price'] = $(this).siblings("input[type='hidden'][name='supplier_price[]']").val();
        }else{
            //编辑定价
            param['is_edit'] = 0;
        }
        console.log(param);

        var option = {};
        var re_index = layer.commonopen('','供货商定价',option);

        //获取这条记录的唯一标识
        param['only_flag'] = $(this).siblings(".uniqid-num").val();

        param['layer_index'] = re_index;

        $.ajax({
            url : '/goods/products/supplier_price_form',
            type: 'POST',
            data:param,
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                $("#layui-layer"+re_index).find(".layui-layer-content").html(res.data.html);
                layer.layerfooter($("#layui-layer"+re_index),re_index);
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    });
    //供货定价获取组织数据
    $("body").delegate(".btn-set-supplier-price",'click',function () {
        var channle_price = {};
        var last_supplier_id = 0;
        $.each($("input[type='number'][name='supplier_price']"),function (index,value) {


            if($(this).attr("data-suplier-id") == last_supplier_id){
                var type = $(this).attr("data-type");
                //组织加减p价格
                console.log(type);
                if (type == "start-price"){
                    channle_price[index-1]['start_price'] = $(this).val();
                }else{
                    channle_price[index-1]['add_price'] = $(this).val();
                }
                channle_price[index-1]['sku_sup_price_id'] = $(this).attr("data-price-id");
            }else{
                channle_price[index] = {};
                channle_price[index]['sup_id'] = $(this).attr("data-suplier-id");
                channle_price[index]['sku_sup_price_id'] = $(this).attr("data-price-id");

                var type = $(this).attr("data-type");
                //组织加减p价格
                console.log(type);
                if (type == "start-price"){
                    channle_price[index]['start_price'] = $(this).val();
                }else{
                    channle_price[index]['add_price'] = $(this).val();
                }



            }
            last_supplier_id = $(this).attr("data-suplier-id");


        });
        var only_flag = $(this).attr('data-flag');
        $("input[type='hidden'][name='supplier_price[]'][class='sales_price_"+only_flag+"']").val(JSON.stringify(channle_price));
        tip_note('保存成功','success');
        var index = $(this).attr("data-value");
        layer.close(index);
    });





    //更新商品
    $(".good-edit-cate-submit").click(function () {

        //商品名称
        var prod_name = $("#prod_name").val();
        if (prod_name == "")
        {
            tip_note("请先填写商品名称")
            $(this).attr("disabled",false);
            return;
        }
        //商品图片
        var prod_photo = $("input[type = 'hidden'][name = 'prod_photos']").val();
        if (prod_photo == ""){
            tip_note("请上传商品图片");
            $(this).attr("disabled",false);
            return;
        }

        //选择了采用物流模板方式计算运费,则物流模板必选
        var delivery_type = $("input[type='radio'][name='prod_express_type']:checked").val();
        if (delivery_type == '2')
        {
            if ($("#prod_express_tpl_id").val()==""){
                tip_note("请先选择运费模板");
                $(this).attr("disabled",false);
                return;
            }
        }

        //销售渠道判断
      /*  var sale_channle = $("input[type='checkbox'][name='sales_chanel[]']:checked").length;
        if (sale_channle == 0){
            tip_note("请至少勾选一个销售渠道");
            $(this).attr("disabled",false);
            return;
        }*/

        //供货商渠道判断
        var supplier_qu = $("input[type='checkbox'][name='supplier[]']:checked").length;
        if (supplier_qu == 0){
            tip_note("请至少勾选一个供货商");
            $(this).attr("disabled",false);
            return;
        }

        var personal_print = $(".is_personal_print").val();
        $(this).attr("disabled",true);
        var is_add_page = $("input[type='radio'][name='prod_is_add_page']:checked").val();
        //如果是个性影像类 则需要选择规格
        if (personal_print == '1'){
            if ($("#prod_size_id").val() == ""){
                tip_note("请先选择规格");
                $(this).attr("disabled",false);
                return;
            }
            //p数范围必填
            //获取最小p数范围
            var min_p = $("input[type='number'][name = prod_min_add_page]").val();
            //获取最大p数
            var max_p = $("input[type='number'][name = prod_max_add_page]").val();

            if((min_p=="" || max_p=="") && is_add_page == '1')
            {
                tip_note("请填写p数范围");
                $(this).attr("disabled",false);
                return;
            }
        }




        var $form = $(this).parents(".cate-main").find('form');
        var url = $form.attr('action');
        var that = $(this);
        $form.trigger("validate");



        if($(".product-sku-row")){
            if($(".pa-table").find('.pa-attr-input').length == 0){
                //找不到货品信息
                $(this).attr("disabled",false);
                tip_note("请先完成货品信息在提交")
                return false;
            }
        }
        //没有出现验证错误情况下才提交
        if($form.find('.pa-attr-input').hasClass('n-invalid')) {
            $(this).attr("disabled",false);
            return false;
        }

        //获取退货标识
        var prod_return_flag = $(".return_flag").val();
        //组织数据
        var prod_return_flag_str=prod_return_flag.join(',');
        $("input[name='prod_return_flag']").val(prod_return_flag_str);

        //获取售后服务标识
        var prod_aftersale_flag = $(".aftersale_flag").val();
        //组织数据
        var prod_aftersale_flag_str=prod_aftersale_flag.join(',');
        $("input[name='prod_aftersale_flag']").val(prod_aftersale_flag_str);

        //获取商品标签标识
        var prod_comment_flag = $(".comment_flag").val();
        //组织数据
        var prod_comment_flag_str=prod_comment_flag.join(',');
        $("input[name='prod_comment_flag']").val(prod_comment_flag_str);

        //如果是加减p则检查加减p数规则是否超过范围

        if (is_add_page == '1'){
            var add_page = $("input[type='number'][name='prod_add_page']").val();
            var min_page = $("input[type='number'][name='prod_min_add_page']").val();
            var max_page = $("input[type='number'][name='prod_max_add_page']").val();
            if (parseInt(add_page)> parseInt(max_page-min_page))
            {
                tip_note("p数规则超过了p数范围");
                $(this).attr("disabled",false);
                return;
            }

        }
        //


        var postData = $(this).parents(".cate-main").find('#form-save').serialize();


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
                    $(".good-edit-cate-submit").attr("disabled",false);
                    //错误验证,把错误显示和js的错误显示合并掉
                    for (var i in data.errors) {
                        var field = i;
                        layer.msg('', {
                            title: false,
                            content: data.errors[field][0],
                            closeBtn: 0,
                            offset: 'auto',
                            icon: 5,
                            zIndex: layer.zIndex, //重点1
                            success: function (layero) {
                                layer.setTop(layero); //重点2
                            }
                        });
                        return false;
                    }
                       /* var field = i;
                        //console.log(field);
                        $form.find("#"+field).removeClass('n-valid');
                        $form.find("#"+field).addClass('n-invalid');
                        $form.find("#"+field).parent().next().show();
                        $form.find("#"+field).parent().next().html(data.errors[field]);*/

                }else if (data.success== "false"){
                    $(".good-edit-cate-submit").attr("disabled",false);
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
                }else if(data.success == 'true'){
                    tip_success('/products','','保存成功');
                    return false;
                }
                var layerIndex = that.parents(".layui-layer").attr("times");
                console.log(that.parents(".layui-layer"));
                layer.close(layerIndex);
                $("#act-btn>.btn-refresh").trigger('click')
            },
            error:function(){
                tip_note("程序出现错误");
                $(".good-edit-cate-submit").attr("disabled",false);
            },
        });
        return false;
    });






})