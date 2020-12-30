$(function(){
    'use strict';
    //tab 标签切换
    $(".fund_tab").click(function () {
        var status = $(this).attr("data-val");
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        $(".tab_val").val(status)

        obj.limit = $("#pages-limit").val();
        obj.status = status;

        loadTable(obj);
    });

    //确定按钮
    $("body").delegate('.btn-confirm-close','click',function (event) {
        $(".layui-layer-close1").click()
    })

    //导出按钮
    $(".btn-fund-export").click(function () {
        var status = $('.tab_val').val();
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);

        obj.limit = $("#pages-limit").val();
        obj.status = status;

        var str = JSON.stringify(obj);
        window.location.href = '/finance/fund/export/'+str
    })

})