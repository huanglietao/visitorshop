$(function(){
    'use strict';

    var is_show;
    var total_money = ''; //总资产
    var available_money = ''; //可用金额
    var frozen_money = ''; //冻结金额

    //金额显示、隐藏
    $(".accounts-six").click(function () {
        if(is_show){
            $(".accounts-five-total").text(total_money);
            $(".accounts-five-available").text(available_money);
            $(".accounts-five-frozen").text(frozen_money);
            is_show = false;
            total_money = '';
            available_money = '';
            frozen_money = '';
        }else{
            is_show = true;
            total_money = $(".accounts-five-total").text();
            available_money = $(".accounts-five-available").text();
            frozen_money = $(".accounts-five-frozen").text();
            $(".accounts-five-total").text('*****');
            $(".accounts-five-available").text('*****');
            $(".accounts-five-frozen").text('*****');
        }
    })

    // //刷新
    // $("body").delegate("#re-btn",'click',function () {
    //     $.ajax({
    //         url : '/finance/accounts/index',
    //         type: 'GET',
    //         data:{},
    //         dataType : 'JSON',
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //         },
    //         success:function (data) {
    //             if(data.status===200){
    //                 var info = data.data;
    //                 $("#total").text(info.total_balance);
    //                 $("#now").text(info.now_balance);
    //                 $("#frozen").text(info.frozen_balance);
    //                 $("#remind").text(info.remind_balance);
    //             }else{
    //                 layer.msg('刷新失败!');
    //             }
    //
    //         },
    //         error:function () {
    //             layer.msg("程序出错了");
    //         }
    //     });
    // });

    $("body").delegate("input[type='checkbox'][name='cb_notice']",'change',function () {
        tip_note("请点击修改按钮使其生效");
    });

    //余额提醒阈值修改
    $("body").delegate("#remind_edit",'click',function () {

        var status = $("input[type='checkbox'][name='cb_notice']").is(':checked');
        var remind = $("#remind").val();

        $.ajax({
            url : '/finance/accounts/remind_status',
            type: 'POST',
            data:{
                'status':status,
                'remind_balance':remind
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function (data) {
                if(data.status===200){
                    tip_note("操作成功",'success');
                }else{
                    tip_note('操作失败');
                }
            },
            error:function () {
                layer.msg("程序出错了");
            }
        });
    });


    //收入支出Tab切换
    $(".nav_status_btn").on("click",function () {
        if($(this).attr("data-val") == 1) {
            $(".income").attr("style","display:none");
            $(".expenditure").attr("style","display:block");
        }else{
            $(".expenditure").attr("style","display:none");
            $(".income").attr("style","display:block");
        }
    })

    //伸缩栏
    $(".subNav").click(function(){
        $(this).toggleClass("currentDd").siblings(".subNav").removeClass("currentDd");
        $(this).toggleClass("currentDt").siblings(".subNav").removeClass("currentDt");
        $(this).next(".navContent").slideToggle(300).siblings(".navContent").slideUp(500);

        $(".subNavBox").find(".fa-lg").each(function () {
            $(this).removeClass("fa-angle-down")
            $(this).addClass("fa-angle-right")
        });
        if($(this).hasClass("currentDd")){
            $(this).find(".fa-lg").removeClass("fa-angle-right")
            $(this).find(".fa-lg").addClass("fa-angle-down")
        }else{
            $(this).find(".fa-lg").addClass("fa-angle-right")
            $(this).find(".fa-lg").removeClass("fa-angle-down")
        }
    })

    //实例化折线图
    var sChart =$("#echart");
    var datas = [
        {
            label:"账户充值（元)",
            fill:  false,
            borderColor: "rgb(54, 113, 238)", //路径颜色
            pointBackgroundColor: "rgb(54, 113, 238)", //数据点颜色
            data: $chartInfo['recharge'],
            lineTension:0
        },
        {
            label:"消费交易（元)",
            fill:  false,
            borderColor: "red", //路径颜色
            pointBackgroundColor: "red", //数据点颜色
            data: $chartInfo['trade'],
            lineTension:0
        },
        {
            label:"退款（元)",
            fill:  false,
            borderColor: "black", //路径颜色
            pointBackgroundColor: "black", //数据点颜色
            data: $chartInfo['refund'],
            lineTension:0
        }
    ];

    var final_data = new Array();
    final_data[0] = datas[0];

    var config = {
        type:'line',
        bezierCurve: false,
        data : {
            labels: $chartInfo['days'],
            datasets: final_data
        },
        options: {
            //图表的标题
            title : {
                // display: true,
                // text: '每月档案' //['nihao','haha'] 多行显示
            },
            scales: {
                yAxes: [{
                    ticks: {
                        //Y轴刻度线
                        display:true,
                        min: 0
                        // stepSize: 10,

                    },
                    //网格线
                    gridLines: {
                        color: 'rgb(187, 187, 187)',
                        borderDash:[5]
                    }
                }],
                xAxes: [{
                    //网格线
                    gridLines: {
                        color: 'rgb(187, 187, 187)',
                        borderDash:[5]
                    }
                }]
            },
            //图例
            legend: {
                display: false
            },
            //鼠标悬停时的提示
            tooltips: {
                mode:'x',
                intersect:false,
                bodyFontSize:14,//提示正文大小     titleFontSize--提示标题字体大小  footerFontSize--提示底部字体大小
                bodySpacing:5,
                // displayColors:false,//为true时，显示提示正文的颜色块
                callbacks: {
                    title:function () {
                        return ""
                    },
                    label: function (tooltipItem, data) {
                        var title = data.datasets[tooltipItem.datasetIndex].label;
                        var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        return title+"："+value;  //返回数组的话，是多行显示
                    },
                    footer: function(tooltipItems) {
                        var date = '';
                        tooltipItems.forEach(function(tooltipItem) {
                            date = tooltipItem.xLabel
                        });
                        return  date;
                    }
                }
            }
        }

    };
    var myLineChart = new Chart(sChart,config);

    //复选框点击
    $(".accounts-thirty-four input[type='checkbox']").click(function () {
        var name = $(this).attr("name");
        var arr = name.split("-");
        var index = arr[arr.length-1];
        if($(this).prop('checked') == true){
            final_data.splice(index,1,datas[index]);
        }else{
            final_data.splice(index,1,{});
        }
        myLineChart.update()
    })

    //折线图第一个checkbox默选中
    $("input[name='cb-recharge-0']").prop("checked",true);

})