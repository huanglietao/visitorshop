/**
 * 消息管理处理的js
 * Created by daiyd on 2019/8/8
 */

$(function(){
    'use strict';


    // 管理显示下级地区
    $("body").delegate('.show-areabtn','click',function () {
        //有下级地区时显示返回按钮
        $('.btn-reback').css('display','inline-block');
        //获取该条数据的id赋值给pid搜索框搜索所属下级的数据
        var areasPid = $(this).attr("data-val");
        $('#pid').val(areasPid);

        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        obj.limit = $("#pages-limit").val();
        obj.pid = areasPid;
        loadTable(obj);
    })



    //======================返回上级js开始 =============//
    $("body").delegate('.btn-reback','click',function () {
        //var reply = $(this).attr('data-back');
       //var areasPid = $('.show-areabtn').attr("data-pid");
        var level = $('#area-level').text();
        var areasId =$('#pid').val();

        $.ajax({
            url :'/areaseting/getAreasPid',
            type: 'POST',
            data : {id:areasId},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(res){
                $('#pid').val(res.pid);

                var tj = JSON.stringify(getFormData($("form#search-form")));
                var obj = JSON.parse(tj);
                obj.limit = $("#pages-limit").val();

                loadTable(obj);
            }
        });
        //判断等级隐藏返回按钮
        if(level==2){
            $('.btn-reback').css('display','none');
        }

    });


});