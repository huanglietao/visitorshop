
$(function(){
    'use strict'
    //柱状图
    var zChart =$("#zchart");
    var zdatas = [
        {
            label:"去年销售额",
            backgroundColor:'rgb(183, 181, 181)',
            data: $data['order_amount']['last_year'],
            lineTension:0
        },
        {
            label:"今年销售额",
            backgroundColor:'rgb(63, 81, 181)',
            data: $data['order_amount']['this_year'],
            lineTension:0
        }
    ];
    var configs = {
        type:'bar',
        bezierCurve: false,
        data : {
            labels: ["01月", "02月", "03月", "04月", "05月", "06月", "07月", "08月", "09月", "10月", "11月","12月"],
            datasets: zdatas
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
                        min: 0,
                        // max:150,
                        stepSize: 20

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
            }
        }

    };
    new Chart(zChart,configs);


    //实例化折线图
    var cChart =$("#cchart");
    var datas = [
                    {
                        label:"交易金额",
                        fill:  false,
                        borderColor: "rgb(63, 81, 181)", //路径颜色
                        pointBackgroundColor: "rgb(63, 81, 181)", //数据点颜色
                        data: $info['totals'],
                        lineTension:0
                    },
                    {
                        label:"成交订单数",
                        fill:  false,
                        borderColor: "rgb(114, 204, 66)", //路径颜色
                        pointBackgroundColor: "rgb(114, 204, 66)", //数据点颜色
                        data: $info['orders'],
                        lineTension:0
                    },
                    {
                        label:"作品数",
                        fill:  false,
                        borderColor: "rgb(112, 33, 33)", //路径颜色
                        pointBackgroundColor: "rgb(112, 33, 33)", //数据点颜色
                        data: $info['works'],
                        lineTension:0
                    }
                ];
    var config = {
        type:'line',
        bezierCurve: false,
        data : {
            labels: $info['days'],
            datasets: datas
        },
        options: {
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
            }
        }

    };
    var myLineChart = new Chart(cChart,config);

    //复选框点击
    var brr = new Array();
    $(".check input[type='checkbox']").click(function () {
        config.data.datasets=[];
        var name = $(this).attr("name");
        var arr = name.split("-");
        var index = arr[arr.length-1];
        if($(this).prop('checked') == true){
            brr.push(index);
        }else{
            for(var j=0;j<brr.length;j++) {
                if (brr[j] == index) {
                    brr.splice(j, 1);
                }
            }
        }
        for(var i=0;i<brr.length;i++) {
            config.data.datasets.push(datas[brr[i]]);
        }
        if(brr.length==0){
            config.data.datasets=datas;
        }
        myLineChart.update()
    });




})