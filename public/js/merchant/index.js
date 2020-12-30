var putChart;
$(function(){

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
                var last_order_amount = res['order_amount']['last_year'];
                var this_order_amount = res['order_amount']['this_year'];
                //渲染销售额数据
                putChart(last_order_amount,this_order_amount);
                //今日销售额
                $(".today_amount").html(res.today_amount);
                //今日订单数
                $(".today_order_count").html(res.today_order_count);
                //今日订单评论数
                $(".today_order_comment").html(res.today_order_comment);
                //总商家数
                $(".merchant_count").html(res.merchant_count);
                //总会员数
                $(".user_count").html(res.user_count);
                //渲染平台公告
                $(".article-main").html("");
                $.each(res.platform_list,function (index,value) {
                    var head_html = '<div style="color:rgb(16, 16, 16);margin-top: 7px;"> ' +
                        '<a href="/news/detail?id='+value.art_id+'" style="color: #101010; width:60%;display: inline-block;overflow:hidden;text-overflow:ellipsis;white-space: nowrap;margin-right: 5%;">'+value.art_title+'</a> ';
                    var new_html = '';
                    if(value.link_news.length == 0)
                    {
                        new_html = '<div style="position: absolute;width: 12%;height:17px;display:inline-block;color:rgb(255,255,255);background-color:rgb(229,28,35);text-align: center;">NEW</div> ';
                    }
                    var bot_html = '<div style="float: right;margin-right: 2%;color: rgb(121, 119, 119)">'+value.create_time+'</div> ' +
                        '</div>';
                    var html = head_html + new_html + bot_html;
                    $(".article-main").append(html);



                    console.log(value);
                });
                //渲染商品列表
                $(".product-main").html("");
                $.each(res.products_list,function (index,value) {
                    var html = '<div style="display: table-cell;display: -webkit-flex;justify-content: space-between;padding-top: 20px"> ' +
                        '<div style="width: 50%;height:0;padding-bottom:50%;position: relative;"> ' +
                        '<img style="width:95%;height:100%;position: absolute; margin-right: 5%" src="'+value.prod_main_thumb+'" /> ' +
                        '</div> ' +
                        '<div style="width:50%;display: inline-block;position: relative;"> ' +
                        '<div style="margin-bottom: 20px"> ' +
                        '<div style="font-size: 12px;font-weight: 700;color: rgb(16, 16, 16);overflow:hidden;text-overflow:ellipsis;">'+value.prod_name+'</div> ' +
                        '<div style="font-size: 12px;color: rgb(121, 119, 119);padding-top: 2%">'+value.prod_title+'</div> ' +
                        '</div> ' +
                        '<div style="text-align: center;position: absolute;bottom:0;left: 0;right: auto;"> ' +
                        '<a href="/#/products?prod_id='+value.prod_id+'" target="_blank" class="btn  btn-primary btn-sm btn-3F51B5 btn_small">&nbsp;&nbsp;查看&nbsp;&nbsp;></a> ' +
                        '</div> ' +
                        '</div> ' +
                        '</div>';
                    $(".product-main").append(html);
                });
                //渲染商品数目
                $('.standard_print').html(res.products_count.standardCount.print_count);
                $('.standard_entity').html(res.products_count.standardCount.entity);
                $('.custom_print').html(res.products_count.customCount.print_count);
                $('.custom_entity').html(res.products_count.customCount.entity);
                //渲染订单数目
                $(".wait_confirm_count").html(res.order_count.wait_confirm_count);
                $(".wait_pay_count").html(res.order_count.wait_pay_count);
                $(".order_wait_produce").html(res.order_count.order_wait_produce);
                $(".wait_delivery_count").html(res.order_count.wait_delivery_count);
                $(".wait_receive_count").html(res.order_count.wait_receive_count);
                $(".wait_evaluate_count").html(res.order_count.wait_evaluate_count);
                //渲染url
                $(".merchant_url").html(res.mch_url);
                $(".merchant_url").attr('href','http://'+res.mch_url);
                $(".agent_url").html(res.agent_url);
                $(".agent_url").attr('href','http://'+res.agent_url);
                $(".scm_url").html(res.scm_url);
                $(".scm_url").attr('href','http://'+res.scm_url);



            }else{
                tip_note(data.msg);
            }
        },
        error : function(){
            tip_note("程序出现错误");
        }
    });

    putChart = function (last_year,this_year) {
        //柱状图
        var zChart =$("#zchart");
        /*var zdatas = [
            {
                label:"去年销售额",
                backgroundColor:'rgb(183, 181, 181)',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.5],
                lineTension:0
            },
            {
                label:"今年销售额",
                backgroundColor:'rgb(63, 81, 181)',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                lineTension:0
            }
        ];*/
        var zdatas = [
            {
                label:"去年销售额",
                backgroundColor:'rgb(183, 181, 181)',
                data: [last_year[0], last_year[1], last_year[2], last_year[3], last_year[4], last_year[5], last_year[6], last_year[7], last_year[8], last_year[9], last_year[10], last_year[11]],
                lineTension:0
            },
            {
                label:"今年销售额",
                backgroundColor:'rgb(63, 81, 181)',
                data: [this_year[0], this_year[1], this_year[2], this_year[3], this_year[4], this_year[5], this_year[6], this_year[7], this_year[8], this_year[9], this_year[10], this_year[11]],
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
    }




    'use strict'



















});
