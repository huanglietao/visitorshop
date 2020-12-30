
$(function(){
    'use strict';

    //类目切换
    $("body").delegate('.nav_status_btn','click',function () {
        var value = $(this).attr("data-val");
        $(".type_f").val(value);
        if (value == 'goods'){
            $(".btn-add").hide();
        }else{
            $(".btn-add").show();
        }
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        loadTable(obj);
        return false;

    });





});
