var upload;
$(function(){
    'use strict'

    //职工列表 start

    //职位切换选择
    $("body").delegate('#salary_worker_position','change',function () {
        var position = JSON.parse($(".positions").val());
        var worker_id = $(this).val();
        $("input[id='salary_worker_rate']").val(position['rate'][worker_id]);
        $("input[id='salary_worker_money']").val(position['per_money'][worker_id]);

    });

    //职工列表 end


    //薪酬列表 start
    //导入
    $("body").delegate("#excel_btn",'click',function () {
        $("#excel_upload").click();
    });

    upload = function (obj){
        var files = obj.files;
        layer.alert('数据导入中，请勿关闭', {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
        });
        for(var i = 0;i<files.length;i++){
            var fd = new FormData();
            var file = files[i];
            fd.append('file',file);
            fd.append('_token',$('meta[name="_token"]').attr('content'));

            $("#excel_upload").val('');
            $.ajax({
                url: "/salary/detail/import",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function (ret) {
                    if(ret.status==200 && ret.success=='true'){
                        layer.msg(ret.data);
                        setTimeout(function () {
                            window.location.reload();//刷新当前页面.
                        },2000);
                    }else if(ret.status==404 && ret.success=='false'){
                        layer.msg(ret.data);
                    }
                },
                error:function () {
                    layer.msg("程序出现错误!");
                }
            });
        }
    };

    //导出
    //订单发货统计导出
    $("body").delegate("#export",'click',function () {
        var finish_time = $("#reservationtime").val();
        if(finish_time==""){
            layer.msg("请选择要导出的时间段");
            return;
        }
        var time = finish_time.split(" - ");

        var sDate = new Date(time[0]).getTime();
        var eDate = new Date(time[1]).getTime();

        var thisMothDays = 1000 * 3600 * 24 * 31;

        if (eDate - sDate > thisMothDays) {
            layer.msg("选择导出的时间段不能超过31天");
            return;
        } else {
            var obj = JSON.stringify(getFormData($("form#search-form")));
            // var obj = JSON.parse(tj);
            location.href = "/salary/detail/export?info="+obj;

        }
    });

    //薪酬列表 start



});