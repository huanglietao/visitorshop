var putChart;
var getMerchantChart;
var getWorkCount;
var getDeliveryCount;
var getOrderSalesCount;
$(function(){
    $(document).ready(function () {
        //渲染订单销售额
        getOrderSalesCount();
        //渲染订单销量
        getMerchantChart();
        //渲染作品销量
        getWorkCount();
        //物流/发货统计
        getDeliveryCount();

    });
    //获取控制台基础数据
        $.ajax({
            url : '/dashboard/get_base_data',
            type: 'POST',
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if (data.code != 0){
                    var res = data.data;
                    //今日销售额
                    $(".today_amount").html(res.today_amount);
                    //昨日销售额
                    $(".yesterday_amount").html(res.yesterday_amount);
                    //今日订单数
                    $(".today_order_count").html(res.today_order_count);
                    //昨日订单数
                    $(".yesterday_order_count").html(res.yesterday_order_count);
                    //今日订单发货数
                    $(".today_order_shipping").html(res.today_order_shipping);
                    //昨日订单发货数
                    $(".yesterday_order_shipping").html(res.yesterday_order_shipping);
                    //总商家数
                    $(".merchant_count").html(res.merchant_count);
                    //总会员数
                    $(".user_count").html(res.user_count);

                    //渲染商品数目
                    $('.standard_print').html(res.products_count.standardCount.print_count);
                    $('.standard_entity').html(res.products_count.standardCount.entity);
                    $('.custom_print').html(res.products_count.customCount.print_count);
                    $('.custom_entity').html(res.products_count.customCount.entity);
                    //模板数
                    $(".template_count").html(res.template_count);
                    //布局数
                    $(".layout_count").html(res.layout_count);
                    //素材数
                    $(".material_count").html(res.material_count);
                    //渲染订单数目
                    $(".wait_confirm_count").html(res.order_status_count.wait_confirm_count);
                    $(".wait_pay_count").html(res.order_status_count.wait_pay_count);
                    $(".order_wait_produce").html(res.order_status_count.order_wait_produce);
                    $(".wait_delivery_count").html(res.order_status_count.wait_delivery_count);
                    $(".wait_receive_count").html(res.order_status_count.wait_receive_count);
                    $(".wait_evaluate_count").html(res.order_status_count.wait_evaluate_count);

                }else{
                    tip_note(data.msg);
                }
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    //填充区option
    $.ajax({
        url : '/dashboard/get_console_data',
        type: 'POST',
        dataType : 'JSON',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success : function(data){
            if (data.code != 0){
                var res = data.data;
                /*var last_order_amount = res['order_amount']['last_year'];
                var this_order_amount = res['order_amount']['this_year'];
                //渲染销售额数据
                putChart(last_order_amount,this_order_amount);*/

                //商品销量排行
                $(".sale-products-main").html("");
                $.each(res.sale_products_list,function (index,value) {
                    var new_index = index+1;
                    var head_html = '<div style="color:rgb(16, 16, 16);margin-top: 7px;"> ' +
                        '<span style="color: #101010; width:60%;display: inline-block;overflow:hidden;text-overflow:ellipsis;white-space: nowrap;margin-right: 5%;">'+new_index+'.'+value.prod_name+'</span> ';
                    var new_html = '';
                    var bot_html = '<div style="float: right;margin-right: 2%;color: rgb(121, 119, 119)">'+value.prod_sale_num+'</div> ' +
                        '</div>';
                    var html = head_html + new_html + bot_html;
                    $(".sale-products-main").append(html);
                });
                //模板排行
                $(".template-main").html("");
                $.each(res.template_list,function (index,value) {
                    var new_index = index+1;
                    var head_html = '<div style="color:rgb(16, 16, 16);margin-top: 7px;"> ' +
                        '<span style="color: #101010; width:60%;display: inline-block;overflow:hidden;text-overflow:ellipsis;white-space: nowrap;margin-right: 5%;">'+new_index+'.'+value.main_temp_name+'</span> ';
                    var new_html = '';
                    var bot_html = '<div style="float: right;margin-right: 2%;color: rgb(121, 119, 119)">'+value.use_times+'</div> ' +
                        '</div>';
                    var html = head_html + new_html + bot_html;
                    $(".template-main").append(html);
                });

                //大客户销量排行
                $(".left-sale").html("");
                $(".right-sale").html("");
                var i = 0;
                $.each(res.agent_sale_order,function (index,value) {
                    ++i;
                    /*var html = '<span style="padding-left:10%;line-height: 2"><span class="agent_name">'+i+'.'+value.agent_name+'</span>: <span class="agent_order_count">'+value.sale_num+'</span> </span><br>';*/
                    var html = '';
                    if (i<=3){
                        $(".left-sale").append(html);
                    }
                    if (i>3 && i<=6){
                        $(".right-sale").append(html);
                    }
                });

            }else{
                tip_note(data.msg);
            }
        },
        error : function(){
            tip_note("程序出现错误");
        }
    });
    //渲染订单销售额
    getOrderSalesCount = function () {
        $.ajax({
            url : '/dashboard/get_order_sale_count',
            type: 'POST',
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if (data.code != 0){
                    var res = data.data;
                    var last_order_amount = res['order_amount']['last_year'];
                     var this_order_amount = res['order_amount']['this_year'];
                     //渲染销售额数据
                     putChart(last_order_amount,this_order_amount);
                }else{
                    tip_note(data.msg);
                }
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    }



    putChart = function (last_year,this_year) {
        var Chart1 = echarts.init(document.getElementById('zchart'), 'walden');
        var option = {
            title : {
                /*text: '每月销售额',
                subtext: '单位:万元'*/
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                display:false
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: false, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: false},
                    saveAsImage : {show: false}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    data : ["01月", "02月", "03月", "04月", "05月", "06月", "07月", "08月", "09月", "10月", "11月","12月"],

                },
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'去年销售额',
                    type:'bar',
                    itemStyle : { normal: {label : {show: true, position: 'inside'}, color:'rgb(183, 181, 181)'}},
                    data:[last_year[0], last_year[1], last_year[2], last_year[3], last_year[4], last_year[5], last_year[6], last_year[7], last_year[8], last_year[9], last_year[10], last_year[11]],
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            {type : 'average', name: '平均值'}
                        ]
                    }
                },
                {
                    name:'今年销售额',
                    type:'bar',
                    itemStyle : { normal: {label : {show: true, position: 'inside'}, color:'rgb(63, 81, 181)'}},
                    data:[this_year[0], this_year[1], this_year[2], this_year[3], this_year[4], this_year[5], this_year[6], this_year[7], this_year[8], this_year[9], this_year[10], this_year[11]],
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            {type : 'average', name: '平均值'}
                        ]
                    }
                },

            ]
        };
        Chart1.setOption(option);
    }

    $('body').delegate('.follow-order-time,.follow-order-merchant','change',function () {
        getMerchantChart();
    })

    //商户订单走势
    getMerchantChart = function () {
        var time_type = $(".follow-order-time").val();
        var merchant = $(".follow-order-merchant").val();
        $.ajax({
            url : '/dashboard/get_order_trend',
            type: 'POST',
            dataType : 'JSON',
            data:{
                time_type:time_type,merchant:merchant
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                //折线图
                var str_data = [];
                var count_data = []
                $.each(data.order_trend_info,function (index,value) {
                    str_data.push(value.time_str);
                    count_data.push(value.order_count);
                });
                var Chart2 = echarts.init(document.getElementById('mid-order'), 'walden');
                var option = {
                    title : {
                        text: '订单量',
                         subtext: '单位:单'
                    },
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        display:false
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: false, readOnly: false},
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: false},
                            saveAsImage : {show: false}
                        }
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : str_data,
                        },
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'订单量',
                            type:'bar',
                            itemStyle : { normal: {label : {show: true, position: 'inside'}, color:'#d26b6b'}},
                            data:count_data,
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            },
                            markLine : {
                                data : [
                                    {type : 'average', name: '平均值'}
                                ]
                            }
                        },

                    ]
                };
                Chart2.setOption(option);

                //饼状图
                var mid_str_data = [];
                var mid_count_data = [];
                var i =0;
                $.each(data.mid_order_count,function (index,value) {
                    mid_str_data.push(value.mch_name);
                    mid_count_data[i]={};
                    mid_count_data[i]['value'] = value.order_count;
                    mid_count_data[i]['name'] = value.mch_name;
                    ++i;
                });
                var Chart3 = echarts.init(document.getElementById('mid-order-pie'), 'walden');
                option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b}: {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 10,
                        data: mid_str_data
                    },

                    series: [
                        {
                            name: '订单量',
                            type: 'pie',
                            radius: ['50%', '70%'],
                            avoidLabelOverlap: false,
                            label: {
                                show: false,
                                position: 'center'
                            },
                            emphasis: {
                                label: {
                                    show: true,
                                    fontSize: '30',
                                    fontWeight: 'bold'
                                }
                            },
                            labelLine: {
                                show: true
                            },
                            data: mid_count_data,
                            itemStyle:{
                                normal:{
                                    label:{
                                        show: true,
                                        formatter: '{b} : {c} ({d}%)'
                                    },
                                    labelLine :{show:true}
                                }
                            }
                        }

                    ]
                };
                Chart3.setOption(option);

            },
            error : function(){
                tip_note("程序出现错误");
            }
        });


    }

    $('body').delegate('.work-monitor','change',function () {
        getWorkCount();
    })
    //作品监控
    getWorkCount = function () {
        var merchant = $(".work-monitor").val();
        $.ajax({
            url : '/dashboard/get_work_monitor',
            type: 'POST',
            dataType : 'JSON',
            data:{
                merchant:merchant
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                //作品处理实时监控折线图
                var Chart4 = echarts.init(document.getElementById('work-handle'), 'walden');
                var option = {
                    title : {
                        text: '作品处理实时监控',
                        subtext: '单位:本'
                    },
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                       data:['待处理']
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: false, readOnly: false},
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: false},
                            saveAsImage : {show: false}
                        }
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : ['','>2小时','>4小时','>6小时','>12小时','>24小时'],
                            boundaryGap: false,//使x轴的第一个点在x轴上
                        },
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'待处理',
                            type:'line',
                            itemStyle : { normal: {label : {show: true, position: 'inside'}, color:'#d26b6b'}},
                            data:[data['works_monitor']['now_hours'],data['works_monitor']['two_hours'],data['works_monitor']['four_hours'],data['works_monitor']['six_hours'],data['works_monitor']['tew_hours'],data['works_monitor']['tf_hours']],
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            },
                            /*markLine : {
                                data : [
                                    {type : 'average', name: '平均值'}
                                ]
                            }*/
                        },

                    ]
                };
                Chart4.setOption(option);

                //作品合成实时监控折线图
                var Chart5 = echarts.init(document.getElementById('work-compound'), 'walden');
                var options = {
                    title : {
                        text: '作品合成实时监控',
                    },
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['未合成']
                    },

                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: false, readOnly: false},
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: false},
                            saveAsImage : {show: false}
                        }
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : ['','>2小时','>4小时','>6小时','>12小时','>24小时'],
                            boundaryGap: false,//使x轴的第一个点在x轴上
                        },
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'未合成',
                            type:'line',
                            itemStyle : { normal: {label : {show: true, position: 'inside'}}},
                            data:[data['work_compound']['waiting']['now_hours'],data['work_compound']['waiting']['two_hours'],data['work_compound']['waiting']['four_hours'],data['work_compound']['waiting']['six_hours'],data['work_compound']['waiting']['tew_hours'],data['work_compound']['waiting']['tf_hours']],
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            }
                        }
                    ]
                };
                Chart5.setOption(options);

                //折线图
                var Chart6 = echarts.init(document.getElementById('push-monitor'), 'walden');
                var options2 = {
                    title : {
                        text: '推送工厂实时监控',
                    },
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['未推送订单数','未推送作品数']
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: false, readOnly: false},
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: false},
                            saveAsImage : {show: false}
                        }
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : ['','>2小时','>4小时','>6小时','>12小时','>24小时'],
                            boundaryGap: false,//使x轴的第一个点在x轴上
                        },
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'未推送订单数',
                            type:'line',
                            itemStyle : { normal: {label : {show: true, position: 'inside'}}},
                            data:[data['push_monitor']['order']['now_hours'],data['push_monitor']['order']['two_hours'],data['push_monitor']['order']['four_hours'],data['push_monitor']['order']['six_hours'],data['push_monitor']['order']['tew_hours'],data['push_monitor']['order']['tf_hours']],
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            }
                        },
                        {
                            name:'未推送作品数',
                            type:'line',
                            itemStyle : { normal: {label : {show: true, position: 'inside'}}},
                            data:[data['push_monitor']['order_prod']['now_hours'],data['push_monitor']['order_prod']['two_hours'],data['push_monitor']['order_prod']['four_hours'],data['push_monitor']['order_prod']['six_hours'],data['push_monitor']['order_prod']['tew_hours'],data['push_monitor']['order_prod']['tf_hours']],
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            }
                        }
                    ]
                };
                Chart6.setOption(options2);
            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    }

    //物流/发货统计
    $('body').delegate('.follow-delivery-time','change',function () {
        getDeliveryCount();
    });


    getDeliveryCount = function () {
        var time = $(".follow-delivery-time").val();
        $.ajax({
            url : '/dashboard/get_delivery_monitor',
            type: 'POST',
            dataType : 'JSON',
            data:{
                time_type:time
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){


                //折线图
                var str_data = [];
                var count_data = [];
                $.each(data.delivery_count,function (index,value) {
                    str_data.push(value.time_str);
                    count_data.push(value.delivery_count);
                });
                //物流/发货监控折线图
                var Chart7 = echarts.init(document.getElementById('delivery-monitor'), 'walden');
                var option = {
                    title : {
                        text: '订单发货统计',
                        subtext: '单位:单'
                    },
                    tooltip : {
                        trigger: 'axis'
                    },
                    legend: {
                        data:['发货单量']
                    },
                    toolbox: {
                        show : true,
                        feature : {
                            mark : {show: true},
                            dataView : {show: false, readOnly: false},
                            magicType : {show: true, type: ['line', 'bar']},
                            restore : {show: false},
                            saveAsImage : {show: false}
                        }
                    },
                    calculable : true,
                    xAxis : [
                        {
                            type : 'category',
                            data : str_data,
                            boundaryGap: false,//使x轴的第一个点在x轴上
                        },
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            name:'发货单量',
                            type:'line',
                            itemStyle : { normal: {label : {show: true, position: 'inside'}, color:'#d26b6b'}},
                            data:count_data,
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值'},
                                    {type : 'min', name: '最小值'}
                                ]
                            },
                            markLine : {
                                data : [
                                    {type : 'average', name: '平均值'}
                                ]
                            }
                        },

                    ]
                };
                Chart7.setOption(option);
                //发货交期饼状图
                var date_pie_data = [];
                var date_pie_str = [];
                var j = 0;
                $.each(data.delivery_date,function (index,value) {
                    date_pie_str[j] = [];
                    date_pie_str[j] = index;
                    date_pie_data[j] = {};
                    date_pie_data[j]['name'] = index;
                    date_pie_data[j]['value'] = value;
                    ++j;
                });
                //订单交期饼状图
                var Chart8 = echarts.init(document.getElementById('order-delivery-date'), 'walden');
                var option1 = {
                    title: {
                        text: '订单交期',
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left:'left',
                        bottom: 'bottom',
                        data: date_pie_str
                    },
                    series: [
                        {
                            name: '交期',
                            type: 'pie',
                            radius: '70%',
                            center: ['50%', '60%'],
                            data: date_pie_data,
                            itemStyle:{
                                normal:{
                                    label:{
                                        show: true,
                                        formatter: '{b} : {c} ({d}%)'
                                    },
                                    labelLine :{show:true}
                                }
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };
                Chart8.setOption(option1);
                //发货区域饼状图
                var pie_data = [];
                var i = 0;
                $.each(data.delivery_area,function (index,value) {
                    pie_data[i] = {};
                    pie_data[i]['value'] = value.area_count;
                    pie_data[i]['name'] = value.province_name;
                    ++i;
                });
                //发货区域饼状图
                var Chart9 = echarts.init(document.getElementById('order-delivery-area'), 'walden');
                var option2 = {
                    title: {
                        text: '发货区域统计',
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                    },
                    series: [
                        {
                            name: '区域',
                            type: 'pie',
                            radius: '70%',
                            center: ['50%', '60%'],
                            data: pie_data,
                            itemStyle:{
                                normal:{
                                    label:{
                                        show: true,
                                        formatter: '{b} : {c} ({d}%)'
                                    },
                                    labelLine :{show:true}
                                }
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };
                Chart9.setOption(option2);



            },
            error : function(){
                tip_note("程序出现错误");
            }
        });
    }

    $(".all-screen").click(function () {
        var html = $(".monitor-main");
        layer.open({
            title:"数据监控台",
            type: 1,
            content: html,
            area: ['100%', '100%'],
            maxmin: false
        });
    })
    'use strict'



















});
