$(function(){

    'use strict'

    var chart;

    //实例化首页的折线图
    var sChart =$("#schart");
    var datas;
    var myLineChart;
    var config;
    var final_data = new Array();
    var arr_data;

    $.ajax({
        url: "/dashboard/chart",
        type : 'POST',
        dataType : 'JSON',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function (ret) {
            if(ret.status==200 && ret.success=='true'){
                chart(ret);
            }
        },
        error:function () {
            layer.msg("程序出现错误!", {icon: 5});
        }
    });

    chart = function (ret) {
        datas = [
            {
                label:"交易金额",
                fill:  false,
                borderColor: "rgb(63, 81, 181)", //路径颜色
                pointBackgroundColor: "rgb(63, 81, 181)", //数据点颜色
                data: [0,ret['data']['money']['zero_four'],ret['data']['money']['four_eight'], ret['data']['money']['eight_twelve'], ret['data']['money']['twelve_sixteen'], ret['data']['money']['sixteen_twenty'], ret['data']['money']['twenty_twenty_four']],
                lineTension:0
            },
            {
                label:"成交订单数",
                fill:  false,
                borderColor: "rgb(114, 204, 66)", //路径颜色
                pointBackgroundColor: "rgb(114, 204, 66)", //数据点颜色
                data: [0,ret['data']['order']['zero_four'],ret['data']['order']['four_eight'], ret['data']['order']['eight_twelve'], ret['data']['order']['twelve_sixteen'], ret['data']['order']['sixteen_twenty'], ret['data']['order']['twenty_twenty_four']],
                lineTension:0
            },
            {
                label:"作品数",
                fill:  false,
                borderColor: "rgb(112, 33, 33)", //路径颜色
                pointBackgroundColor: "rgb(112, 33, 33)", //数据点颜色
                data: [0,ret['data']['work']['zero_four'],ret['data']['work']['four_eight'], ret['data']['work']['eight_twelve'], ret['data']['work']['twelve_sixteen'], ret['data']['work']['sixteen_twenty'], ret['data']['work']['twenty_twenty_four']],

                lineTension:0
            }
        ];
        arr_data = [
            {
                label:"交易金额",
                fill:  false,
                borderColor: "rgb(63, 81, 181)", //路径颜色
                pointBackgroundColor: "rgb(63, 81, 181)", //数据点颜色
                data: [0,ret['data']['money']['zero_four'],ret['data']['money']['four_eight'], ret['data']['money']['eight_twelve'], ret['data']['money']['twelve_sixteen'], ret['data']['money']['sixteen_twenty'], ret['data']['money']['twenty_twenty_four']],
                lineTension:0
            },
            {
                label:"成交订单数",
                fill:  false,
                borderColor: "rgb(114, 204, 66)", //路径颜色
                pointBackgroundColor: "rgb(114, 204, 66)", //数据点颜色
                data: [0,ret['data']['order']['zero_four'],ret['data']['order']['four_eight'], ret['data']['order']['eight_twelve'], ret['data']['order']['twelve_sixteen'], ret['data']['order']['sixteen_twenty'], ret['data']['order']['twenty_twenty_four']],
                lineTension:0
            },
            {
                label:"作品数",
                fill:  false,
                borderColor: "rgb(112, 33, 33)", //路径颜色
                pointBackgroundColor: "rgb(112, 33, 33)", //数据点颜色
                data: [0,ret['data']['work']['zero_four'],ret['data']['work']['four_eight'], ret['data']['work']['eight_twelve'], ret['data']['work']['twelve_sixteen'], ret['data']['work']['sixteen_twenty'], ret['data']['work']['twenty_twenty_four']],

                lineTension:0
            }
        ];
        final_data = datas;

        var config = {
            type:'line',
            bezierCurve: false,
            data : {
                labels: ["00:00", "04:00", "08:00", "12:00", "16:00", "20:00", "24:00"],
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
                // tooltips: {
                //     mode:'x',
                //     intersect:false,
                //     bodyFontSize:15,//提示正文大小     titleFontSize--提示标题字体大小  footerFontSize--提示底部字体大小
                //     bodySpacing:5,
                //     // displayColors:false,//为true时，显示提示正文的颜色块
                //     callbacks: {
                //         title:function () {
                //             return ""
                //         },
                //         label: function (tooltipItem, data) {
                //             var title = data.datasets[tooltipItem.datasetIndex].label;
                //             var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                //             return title+"："+value;  //返回数组的话，是多行显示
                //         },
                //         footer: function(tooltipItems) {
                //             var date = '';
                //             tooltipItems.forEach(function(tooltipItem) {
                //                 date = tooltipItem.xLabel
                //             });
                //             return  date;
                //         }
                //     }
                // }
                //动画
                // animation: {
                //     duration: 800 // general animation time
                // },
                // hover: {
                //     animationDuration: 800 // duration of animations when hovering an item
                // },
                // responsiveAnimationDuration: 800 // animation duration after a resize
            }

        };

        myLineChart = new Chart(sChart,config)

    }



    //复选框点击
    $(".check input[type='checkbox']").click(function () {
        var name = $(this).attr("name");
        var arr = name.split("-");
        var index = arr[arr.length-1];
        if($(this).prop('checked') == true){
            final_data.splice(index,1,arr_data[index]);
        }else{
            final_data.splice(index,1,{});
        }
        myLineChart.update()
    });

    //金额显示、隐藏
    var is_show;
    var available_money = ''; //可用金额
    var frozen_money = ''; //冻结金额

    $(".dashboard-money").click(function () {
        if(is_show){
            $(".money-text-available").text(available_money);
            $(".money-text-frozen").text(frozen_money);
            is_show = false;
            available_money = '';
            frozen_money = '';
        }else{
            is_show = true;
            available_money = $(".money-text-available").text();
            frozen_money = $(".money-text-frozen").text();
            $(".money-text-available").text('*****');
            $(".money-text-frozen").text('*****');
        }
    })











});
