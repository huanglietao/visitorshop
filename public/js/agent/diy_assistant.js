var loadTableList;
var getOrderNo;
var changeWorksNum;
var tip_note;
$(function(){
    'use strict'
    $(document).ready(function () {
        //判断url中是否有订单号，有则直接调用table方法
        var order_no = $(".order_no").val();
        var agent_id = $(".agent_id").val();
        if(order_no!="")
        {
            loadTableList(order_no,agent_id)
        }


    })

    //table列表加载
    $(".cm-search-confirm").click(function () {
        var order_no = $(".cm-search-input").val();

        var reg = /^\w+$/;//正则表达，只能为数字字母下划线
        if (order_no == "")
        {
            layer.msg("请输入订单号");
            return;
        }else if (!reg.test(order_no)) {
            layer.msg("请输入正确的订单号");
            return;
        }else{
            var agent_id = $(".agent_id").val();
            loadTableList(order_no,agent_id)
        }
    })

    //加载记录列表
    loadTableList = function(order_no,agent_id){
        $(".tbl-content").html('');
        //加入加载标示
        var html = '<div class="loading" style="text-align: center;margin-top:10px"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>';

        $('.no-border-table').after(html);
        $.ajax({
            url :'/diy_assistant/table',
            type: 'POST',
            data : {order_no:order_no,agent_id:agent_id},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                var data = res['data'];
                $(".loading").hide();
                // console.log(data);
                $(".tbl-content").html(data.html);
                $(".order_no").val(order_no);
            },
            error : function(){

            }
        });
    };

    //查看作品链接
    $("body").delegate(".s_o_work_eye",'click',function () {
        /*var sku_id = $(this).parents(".s_o_works_spec").siblings(".sku_id").val();*/
        var sku_id = $(this).attr("data-sku-id");
        var sku_sn = $(this).attr("data-sku-sn");
        //获取订单号
        var order_no = $(".order_no").val();
        //获取分销id
        var agent_id = $(".agent_id").val();
        $.ajax({
            url :'/diy_assistant/works_operate',
            type: 'POST',
            data : {sku_id:sku_id,order_no:order_no,agent_id:agent_id,sku_sn:sku_sn},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
               console.log(res['data'])
                layer.open({
                    type:1,
                    title:"作品列表",
                    closeBtn: 1,
                    shade:0,
                    area:['60%','70%'],
                    skin:"order-skin",
                    content: res['data'].html,
                    success: function(layero, index){
                        var that = this;
                        var frame = layero;

                        var layerfooter = frame.find(".layer-footer");
                        layer.layerfooter(layero, index, that);
                    },
                    end:function (index) {

                    }

                })
            },
            error : function(){

            }
        });



    });

    //商品数量加减点击
    $("body").delegate(".s_reduce,.s_addition","click",function () {
        var action = $(this).attr("data-value");
        var num = 0;
        num = $(this).siblings(".s_num_d").find(".s_num_input").val();
        if (action == "reduce")
        {
            //减数操作
            num = parseInt(num) - 1;
            if (num<=0)
            {
                num = 1;
            }
            $(this).siblings(".s_num_d").find(".s_num_input").val(num)
        }else {
            //加数操作
            num = parseInt(num) + 1;
            if (num<=0)
            {
                num = 1;
            }
            var unqiue  = $(this).parents(".s_works_spec").find(".unique").val();
            var all_num  = $(this).parents(".s_works_spec").find(".project_all_num").val();
            //判断是否超过作品的可制作数量
            var res = changeWorksNum(all_num,unqiue);
            if (res == 1){
                $(this).siblings(".s_num_d").find(".s_num_input").val(num)
            }
        }

    })


    //商品数量输入验证
    $("body").delegate(".s_num_input","blur",function () {
        var num = $(this).val();

        var re = new RegExp("^[0-9]*[1-9][0-9]*$");
        if (!re.test(num)) {
            $(this).val("1")
        }

        var defalut_value = $(this).attr('data-defalut-value');
        if (num == defalut_value){
            //只是点了一下输入框，没有改变数量
            return;
        }
        var unqiue  = $(this).parents(".s_works_spec").find(".unique").val();
        var all_num  = $(this).parents(".s_works_spec").find(".project_all_num").val();
        //判断是否超过作品的可制作数量
        var res = changeWorksNum(all_num,unqiue);
        if (res == 3){
            tip_note("数量超过该商品可做的作品数量，请先删除原有作品在进行数量调整");
            $(this).val($(this).attr('data-defalut-value'));
        }

        //为手机端时blur需要改变商品数量
        if ($(this).hasClass("s_num_mobile") && $(this).val() != $(this).attr('data-defalut-value') && re.test(num))
        {
            mobileChangeWorkNum(unqiue);
        }

    });

    function changeWorksNum(all_num,unqiue) {
        var now_num = 0;
        $(".works_num_"+unqiue).each(function (index,value) {
            now_num = parseInt($(this).val()) + now_num;
        });
        if (now_num>all_num){
            //超过了最大数量（数量超过商品可做作品数量）
            return 3;
        }else if(now_num == all_num){
            //等于最大数量（该商品已做完）
            return 2;
        }else{
            //小于最大数量（还能继续做）
            return 1
        }
    }
    //手机端修改数量
    function mobileChangeWorkNum(unique) {
        var form_data = {};
        var order_no ="";
        var agent_id = "";
        //获取每个作品的数量
        $(".works_num_"+unique).each(function (index,value) {
            var pro_info_id  = $(this).parents(".s_works_spec").find(".prj_info_id").val();
            order_no  = $(this).parents(".s_works_spec").find(".order_no").val();
            agent_id  = $(this).parents(".s_works_spec").find(".agent_id").val();
            form_data[pro_info_id] = {};
            form_data[pro_info_id] = $(this).val();
        });
        var that = $(this);
        $.ajax({
            url : '/diy_assistant/change_works_num',
            type: 'POST',
            data:{
                project_info:form_data,
                order_no:order_no,
                agent_id:agent_id,
                is_confirm:0
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if (data.code == 0){

                    layer.confirm(data.msg, {
                            btn: ['确定', '取消'] //可以无限个按钮
                            ,cancel: function(index, layero){
                                //取消操作，点击右上角的X
                                //按钮【按钮二】的回调
                                layer.close(index);
                            }
                        },
                        function(index, layero){
                            //按钮【按钮一】的回调
                            $.ajax({
                                url : '/diy_assistant/change_works_num',
                                type: 'POST',
                                data:{
                                    project_info:form_data,
                                    order_no:order_no,
                                    agent_id:agent_id,
                                    is_confirm:1
                                },
                                dataType : 'JSON',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                },
                                success : function(data){
                                    if(data.code == 1){
                                        //成功修改
                                        getOrderNo();
                                        var name = that.parents(".layui-layer").attr("times");
                                        //先得到当前iframe层的索引
                                        layer.close(name);
                                        layer.close(index);
                                    }else{
                                        //异常情况
                                        tip_note(data.msg);
                                    }

                                },
                                error : function(){
                                    tip_note("程序出现错误");
                                }
                            });
                        }, function(index){
                            //按钮【按钮二】的回调
                            getOrderNo();
                        });
                }else if(data.code == 1){
                    //成功修改
                    getOrderNo();
                }else{
                    //异常情况
                    tip_note(data.msg);
                }
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    }



    $("body").delegate(".diy-work-del",'click',function () {

        var url      = $(this).attr("data-url");
        var aid      = $(this).attr("data-agent-id");
        var order_no = $(this).attr("data-order-no");

        layer.confirm('确定要删除该作品吗？', {
                btn: ['确定', '取消'] //可以无限个按钮
                ,cancel: function(index, layero){
                    //取消操作，点击右上角的X
                    //按钮【按钮二】的回调
                    layer.close(index);
                }
            },
            function(index, layero){
                //按钮【按钮一】的回调
                var del_index = index;
                $.ajax({
                    url : url,
                    type: 'GET',
                    dataType : 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success : function(res){
                        if (res.success == "false")
                        {
                            layer.msg(res.message);
                        }else{
                            layer.msg("删除成功");
                        }
                        setTimeout(function () {
                            var name = $(".diy-wt-sure").parents(".layui-layer").attr("times");
                            //先得到当前iframe层的索引
                            layer.close(name);
                            getOrderNo();
                        },1000)
                    },
                    error : function(){
                        layer.msg("程序出现错误");
                    }
                });

            }, function(index){
                //按钮【按钮二】的回调
                layer.close(index);
                return;
            });

    });
    $("body").delegate(".diy-wt-sure",'click',function () {
        var is_order = $(".is_order").val();
        if (is_order == '1'){
            getOrderNo();
            var name = $(this).parents(".layui-layer").attr("times");
            //先得到当前iframe层的索引
            layer.close(name);
        }else{
            var unique = $(this).attr("data-num");//避免多开弹窗数据混淆，添加唯一标识
            var form_data = {};
            var order_no ="";
            var agent_id = "";
            //获取每个作品的数量
            $(".works_num_"+unique).each(function (index,value) {
                var pro_info_id  = $(this).parents(".s_works_spec").find(".prj_info_id").val();
                order_no  = $(this).parents(".s_works_spec").find(".order_no").val();
                agent_id  = $(this).parents(".s_works_spec").find(".agent_id").val();
                form_data[pro_info_id] = {};
                form_data[pro_info_id] = $(this).val();
            });
            var that = $(this);
            $.ajax({
                url : '/diy_assistant/change_works_num',
                type: 'POST',
                data:{
                    project_info:form_data,
                    order_no:order_no,
                    agent_id:agent_id,
                    is_confirm:0
                },
                dataType : 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success : function(data){
                    if (data.code == 0){

                        layer.confirm(data.msg, {
                                btn: ['确定', '取消'] //可以无限个按钮
                                ,cancel: function(index, layero){
                                    //取消操作，点击右上角的X
                                    //按钮【按钮二】的回调
                                    layer.close(index);
                                }
                            },
                            function(index, layero){
                                //按钮【按钮一】的回调
                                $.ajax({
                                    url : '/diy_assistant/change_works_num',
                                    type: 'POST',
                                    data:{
                                        project_info:form_data,
                                        order_no:order_no,
                                        agent_id:agent_id,
                                        is_confirm:1
                                    },
                                    dataType : 'JSON',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    },
                                    success : function(data){
                                        if(data.code == 1){
                                            //成功修改
                                            getOrderNo();
                                            var name = that.parents(".layui-layer").attr("times");
                                            //先得到当前iframe层的索引
                                            layer.close(name);
                                            layer.close(index);
                                        }else{
                                            //异常情况
                                            tip_note(data.msg);
                                        }

                                    },
                                    error : function(){
                                        tip_note("程序出现错误");
                                    }
                                });
                            }, function(index){
                                //按钮【按钮二】的回调
                                layer.close(index);
                            });
                    }else if(data.code == 1){
                        //成功修改
                        getOrderNo();
                        var name = that.parents(".layui-layer").attr("times");
                        //先得到当前iframe层的索引
                        layer.close(name);
                    }else{
                        //异常情况
                        tip_note(data.msg);
                    }
                },
                error : function(){
                    tip_note("程序出现错误");
                }
            });
        }
    })

    //获取订单号并重新加载列表
    getOrderNo = function () {
        //判断输入框是否有订单号
        var order_no = $(".cm-search-input").val();
        if (order_no == "")
        {
            //判断带过来的url中是否有订单号
            var u_order_no = $(".order_no").val();
            var agent_id = $(".agent_id").val();
            if(u_order_no!="")
            {
                loadTableList(u_order_no,agent_id)
            }else{
                //两者都没有
                loadTableList(0,0)
            }
        }else{
            //输入框中有订单号
            $(".cm-search-confirm").trigger('click');
        }

    }

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

})