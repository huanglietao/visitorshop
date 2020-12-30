var upload;
$(function(){
    'use strict';
    $("body").delegate(".page_type_checkbox","click",function () {
        var value = $(this).val();
        if ($(this).prop("checked") == true) {
            $(".item-"+value).show();
        }else{
            $(".item-"+value).hide();
        }
    });






});