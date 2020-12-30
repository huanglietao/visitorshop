$(function () {



    //导出按钮
    $("#prod_export").click(function () {

        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);

        if(obj['send_time'] == ''){
            layer.msg('请在搜索框交易时间项筛选所需导出的订单');
            return false;
        }

        var create_time = obj['send_time'].split(" - ");
        var start_time = create_time[0];
        var end_time = create_time[1];
        var differ_date = Math.floor((new Date(end_time).getTime() - new Date(start_time).getTime())/(24*3600*1000));

        if(differ_date < 0){
            layer.msg('请选择正确的时间区间');
            return false;
        }
        /*else if(differ_date > 30)
        {
            layer.msg('最多只能导出一个月内的订单');
            return false;
        }*/

        delete(obj['order_no']);

        var str = JSON.stringify(obj);
        window.location.href = '/reportform/produce/export/'+str;
    })


});
