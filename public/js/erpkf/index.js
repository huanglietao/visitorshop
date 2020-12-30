$(function(){
    'use strict'

    $(document).ready(function () {
        if ( $(".main-search").find("#reservationtime"))
        {
            rangedatapicker();
        }
        $("#searchID .table-rest").attr('type','button');
        var day1 = new Date();
        day1.setTime(day1.getTime()-24*60*60*1000);
        var Mon1 = day1.getMonth()+1;if(Mon1<10){Mon1 = '0'+Mon1}
        var Dat1 = day1.getDate();   if(Dat1<10){Dat1 = '0'+Dat1}
        var s1 = day1.getFullYear()+"-" + Mon1 + "-" + Dat1;
        s1 = s1+" 08:00:00";
        var day2 = new Date();
        day2.setTime(day2.getTime());
        var Mon = day2.getMonth()+1;if(Mon<10){Mon = '0'+Mon}
        var Dat = day2.getDate();   if(Dat<10){Dat = '0'+Dat}
        var Hou = day2.getHours();  if(Hou<10){Hou = '0'+Hou}
        var Min = day2.getMinutes();if(Min<10){Min = '0'+Min}
        var Sec = day2.getSeconds();if(Sec<10){Sec = '0'+Sec}
        var s2 = day2.getFullYear()+"-" + Mon + "-" + Dat+" "+Hou+":"+Min+":"+Sec;

        var time = s1 + " - " + s2;
        $("#searchID input[type='text']").val(time);

    });

    $(document).ready(function () {
        //加入加载标示
        loadtable('1','10','1');
    });

    $(".s_analy_tab").click(function () {
        var cate_id = $(this).attr("data-val");
        loadtable(cate_id,'10','1');
    });

    function loadtable(cate_id,limit,curPage) {
        $('.statistics_loading').html("");
        var html = '<div class="new_loading" style="text-align: center;margin-top:10px;position: absolute;z-index: 2;left: 0;right: 0;margin-left: auto;margin-right: auto;"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>';
        $('.statistics_loading').append(html);
        var search = $("#searchID input[type='text']").val();
        var partner_code = $("#partner_code").val();
        var partner_real_name = $("#partner_real_name").val();
        var url ="";
        //对应模块视图地址
        switch (cate_id) {
            case '1' :
                url = "/reconciliation/list";
                break;
            case '2' :
                url = "/reconciliation/list";
                break;
            case '3' :
                url = "/reconciliation/list";
                break;
            default : url = "/reconciliation/list";break;

        }
        $.ajax({
            url : url,
            type: 'post',
            data : {"cate_id":cate_id,"search":search,"partner_code":partner_code,"partner_real_name":partner_real_name,"limit":limit,"curPage":curPage},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if (data.status==200){
                    $(".new_loading").hide();
                    $(".tbl-content").html(data.html);
                    //渲染分页数据
                    $("#list-total").html(data.total); //总数
                    var limit = parseInt($("#pages-limit").val());
                    if(isNaN(limit)) {
                        limit = 10;
                    }
                    //页数
                    var pages = Math.ceil(data.total/limit);
                    $("#count-total").html(pages);
                    $("#total_pages").val(pages);

                    if(pages==1 || pages==0){
                        $(".page-act").hide();
                        return;
                    }else {
                        $(".paginate").show();
                        $(".page-act").show();
                    }

                    //当前页
                    var  currentPage = curPage;
                    if(currentPage == 1) { //第1页时上一页和首页不能操作
                        $(".pages-first").addClass('disabled');
                        $(".pages-prev").addClass('disabled');
                        $(".pages-next").removeClass('disabled');
                        $(".pages-last").removeClass('disabled');
                    }else if(currentPage >=pages) {
                        $(".pages-next").addClass('disabled');
                        $(".pages-last").addClass('disabled');
                        $(".pages-first").removeClass('disabled');
                        $(".pages-prev").removeClass('disabled');
                    } else {
                        $(".pages-first").removeClass('disabled');
                        $(".pages-prev").removeClass('disabled');
                        $(".pages-next").removeClass('disabled');
                        $(".pages-last").removeClass('disabled');
                    }

                    var option = "";
                    var selected  = '';
                    for (var i=1; i<=pages; i++) {
                        if(currentPage == i) {
                            selected = "selected"
                        } else {
                            selected = ""
                        }
                        option += "<option "+selected+" value='"+i+"'>第"+i+"页</option>";
                    }
                    $("#pages-item").html(option);
                 }
            }
        });
    }

    //导出报表
    $("#download").click(function () {
        var search = $("#searchID input[type='text']").val();
        var partner_code = $("#partner_code").val();
        var partner_real_name = $("#partner_real_name").val();
        if(partner_code=='' && partner_real_name==''){
            alert("请选择查询的客户编号或客户简称");
            return;
        }
        location.href = "/reconciliation/export?search="+search+"&partner_code="+partner_code+"&partner_real_name"+partner_real_name;
    })

    //控制台金额显示、隐藏
    var is_show;
    var total_money = ''; //总资产
    var available_money = ''; //可用金额

    $(".accounts-show").click(function() {
        if (is_show) {
            $(".accounts-total").text(total_money);
            $(".accounts-available").text(available_money);
            $(".accounts-show").attr("src","/images/eyes_open.png")
            is_show = false;
            total_money = '';
            available_money = '';
        } else {
            is_show = true;
            total_money = $(".accounts-total").text();
            available_money = $(".accounts-available").text();
            $(".accounts-total").text('*****');
            $(".accounts-available").text('*****');
            $(".accounts-show").attr("src","/images/eyes_hide.png")
        }
    })

    //搜索功能
    $("#searchID").delegate(".btn-primary","click",function () {
        var search = $("#searchID input[type='text']").val();
        var time = search.split(" - ");
        var timeStart = new Date(time[0]).getTime();
        var timeEnd = new Date(time[1]).getTime();
        var result = parseInt((timeEnd - timeStart)/1000/3600/24);
        if(result>31){
            alert("查询时间请不要超过31天");
            return [];
        }
        var cate_id = $(".nav_status_current").attr("data-val");
        var limit= $('#pages-limit').val();
        loadtable(cate_id,limit,'1');
    });
    //重置按钮
    $("#searchID").delegate(".table-rest","click",function () {
        var day1 = new Date();
        day1.setTime(day1.getTime()-24*60*60*1000);
        var Mon1 = day1.getMonth()+1;if(Mon1<10){Mon1 = '0'+Mon1}
        var Dat1 = day1.getDate();   if(Dat1<10){Dat1 = '0'+Dat1}
        var s1 = day1.getFullYear()+"-" + Mon1 + "-" + Dat1;
        s1 = s1+" 08:00:00";
        var day2 = new Date();
        day2.setTime(day2.getTime());
        var Mon = day2.getMonth()+1;if(Mon<10){Mon = '0'+Mon}
        var Dat = day2.getDate();   if(Dat<10){Dat = '0'+Dat}
        var Hou = day2.getHours();  if(Hou<10){Hou = '0'+Hou}
        var Min = day2.getMinutes();if(Min<10){Min = '0'+Min}
        var Sec = day2.getSeconds();if(Sec<10){Sec = '0'+Sec}
        var s2 = day2.getFullYear()+"-" + Mon + "-" + Dat+" "+Hou+":"+Min+":"+Sec;
        var time = s1 + " - " + s2;
        $("#searchID input[type='text']").val(time);
        $("#partner_code").val("");
        var cate_id = $(".nav_status_current").attr("data-val");
        var limit= $('#pages-limit').val();
        loadtable(cate_id,limit,'1');
    });

    //时间
    function rangedatapicker() {
        var ranges = {};

        ranges['今天'] = [moment().startOf('day'), moment().endOf('day')];
        ranges['昨天'] = [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')];
        ranges['最近7天'] = [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')];
        ranges['最近30天'] = [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')];
        ranges['这个月'] = [moment().startOf('month'), moment().endOf('month')];
        ranges['上个月'] = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')];

        var options = {
            timePicker: false,
            autoUpdateInput: false,
            timePickerSeconds: true,
            timePicker24Hour: true,
            autoApply: true,
            maxDate: moment(new Date()), //设置最大日期
            dateLimit : {
                days : 30
            },
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss',
                customRangeLabel: "Custom Range",
                applyLabel: "Apply",
                cancelLabel: "Clear",
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'
                ],
            },
            /* ranges: ranges,*/
        };
        var origincallback = function (start, end) {
            var starts = start.format(options.locale.format);
            var ends = end.format(options.locale.format);
            var data = changetime(starts,ends);
            $(this.element).val(data['start'] + " - " + data['end']);
            $(this.element).trigger('blur');
        };

        $(".datetimerange").each(function () {
            var callback = typeof $(this).data('callback') == 'function' ? $(this).data('callback') : origincallback;
            $(this).on('apply.daterangepicker', function (ev, picker) {
                callback.call(picker, picker.startDate, picker.endDate);
            });
            $(this).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('').trigger('blur');
            });
            $(this).daterangepicker($.extend({}, options, $(this).data()), callback);
        });
    }

    //选择查询天数
    $("#main").delegate(".search-data-num","click",function () {
        var num = $(this).attr("data-num");
        var str;
        if (num == 1)
        {
            var starts = moment().subtract(1, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss');
        }else if (num == 7){
            var starts = moment().subtract(6, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss');
        }else{
            var starts = moment().subtract(29, 'days').startOf('day').format('YYYY-MM-DD HH:mm:ss');
        }
        var ends = moment().subtract('days').endOf('day').format('YYYY-MM-DD HH:mm:ss');
        var data = changetime(starts,ends);
        str = [data['start'], data['end']];
        str = str.join(" - ");
        $(this).parent(".data-num").siblings(".reservationtime").find("#reservationtime").val(str);

    })

    function changetime($start,$end) {
        var starts = $start.split(" ");
        var ends = $end.split(" ");
        var data = [];
        data['start'] = starts[0]+" 08:00:00";
        data['end'] = ends[0]+" 08:00:00";
        return data;
    }


    //控制台刷新
    $("body").delegate(".refresh_dash","click",function () {
        location.reload()
    })

    //==================分页相关js开始 =========//
    $("body").delegate("#pages-item","change",function () {
        var cate_id = $(".nav_status_current").attr("data-val");
        var limit= $('#pages-limit').val();
        var curPage = $("#pages-item option:selected").val();
        loadtable(cate_id,limit,curPage);
    });
    $("body").delegate("#pages-limit","change",function () {
        var cate_id = $(".nav_status_current").attr("data-val");
        var limit= $('#pages-limit').val();
        loadtable(cate_id,limit,'1');

    });
    //首页
    $("body").delegate(".paginate .pages","click",function () {
        if($(this).hasClass("disabled")){
            return ;
        }
        var curPage = $("#pages-item").val();

        var flag = $(this).attr('data-flag');

        var pageSize = parseInt($("#total_pages").val());

        switch (flag) {
            case "prev" :
                curPage--;
                break;
            case "next" :
                curPage++;
                break;
            case 'first' :
                curPage = 1;
                break;
            case 'last' :
                curPage = pageSize;
                break;
            default : curPage = 1;break;

        }

        if(curPage < 1 || curPage > pageSize) {
            return ;
        } else {
            var limit = $("#pages-limit").val();
            var page = curPage;
            var cate_id = $(".nav_status_current").attr("data-val");
            loadtable(cate_id,limit,page);
        }
    })
    //======================分页js结束 =============//

});
