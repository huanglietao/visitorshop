
$(function(){
    'use strict';


    // 点击切换是否处理
    $("body").delegate('.btn-change','click',function () {
        var flag = $(this).attr('data-flag');
        var id = $(this).attr('data-id');

        $.ajax({
            url : '/maintenance/exception/updateField',
            type: 'POST',
            data:{
                flag:flag,
                id:id,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){

                if(data.success == 'true'){
                    layer.msg("操作成功");
                    var tj = JSON.stringify(getFormData($("form#search-form")));
                    var obj = JSON.parse(tj);
                    obj.limit = $("#pages-limit").val();
                    obj.page = $("#pages-item").val();
                    loadTable(obj);

                }else {
                    layer.msg("操作失败");
                }

            },
            error : function(){
                console.log("操作失败");
            }
        });

    });









});
