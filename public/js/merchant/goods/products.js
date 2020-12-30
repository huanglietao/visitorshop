$(function(){
    'use strict';
    //全选
    $("body").delegate('.cc_checkall','click',function () {
        if ($(this).prop("checked")) {
            $("input[type='checkbox'][name='checkbox[]']").prop("checked",true);//全选
            $(".cc_checkall").prop("checked",true);//全选
        } else {
            $("input[type='checkbox'][name='checkbox[]']").prop("checked",false);  //取消全选
            $(".cc_checkall").prop("checked",false);  //取消全选
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
    });
    //添加页面跳转
    $("body").delegate(".goods-add",'click',function () {
        var url = $(this).attr("data-url");

        window.location.href = url;
    })
    //点击上下架
    $("body").delegate(".sw_checkbox",'click',function () {
        console.log($(this).is(':checked'));
        if ($(this).is(':checked')){
            var prod_onsale_status = 1;
        }else{
            var prod_onsale_status = 0;
        }
        var prod_id = $(this).attr('data-value');
        $.ajax({
            url : '/goods/products/onsale',
            type: 'POST',
            data:{
                prod_onsale_status: prod_onsale_status,
                prod_id:prod_id

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

                }

            },
            error : function(e){
                console.log(e);
            }
        });
    })

    //修改排序
    $("body").delegate(".s_o_prod_sort",'blur',function () {
        var prod_id = $(this).attr('data-gid');
        var sort = $(this).val();
        var re = new RegExp("^[0-9]*[1-9][0-9]*$");
        if (!re.test(sort)) {
            $(this).val("0")
        }
        sort = $(this).val();
        $.ajax({
            url : '/goods/products/change_sort',
            type: 'POST',
            data:{
                prod_id:prod_id,
                sort:sort
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },

            success : function(data){
                if (data.success == 'false')
                {

                }else{
                    tip_note("修改成功",'success');
                }

            },
            error : function(e){
                console.log(e);
            }
        });
    })


    //点击编辑
    $("body").delegate(".goods-edit",'click',function () {
        var url = $(this).attr("data-url");

        window.location.href = url;
    })

})