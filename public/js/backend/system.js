$(function(){
    'use strict';

    //表单提交处理
    $("#comfirm").click(function () {

        var form = $(this).parent().parent().parent();
        form.trigger("validate");
        //没有出现验证错误情况下才提交
        if(form.find('.form-control').hasClass('n-invalid')) {
            return false;
        }

        $(this).attr("disabled","disabled");

        $.ajax({
            type: 'POST',
            url: 'basics/save',
            dataType: "json",
            data: $("form").serializeArray(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(res){
                console.log(res)
            },
            success: function(data) {
                if(data.success == 'true'){
                    layer.msg('',{
                        title: false,
                        content: '操作成功',
                        closeBtn:0,
                        offset: 'auto',
                        icon:1,
                        zIndex: layer.zIndex, //重点1
                        success: function(layero){
                            layer.setTop(layero); //重点2
                        }
                    });
                }else {
                    var errorArray = new Array()
                    errorArray['text'] = '数据保存出错了'
                    errorArray['recover'] = 1
                    errorArray['title'] = '警告'
                    errorArray['is_confirm'] = 0
                    tip_warn(errorArray)
                }
            }
        });
    })

});