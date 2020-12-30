/**
 * 消息管理处理的js
 * Created by daiyd on 2019/8/8
 */

$(function(){
    'use strict'

    // 全选/反选
    $("body").delegate('.checkall','click',function () {
        if ($(this).prop("checked")) {
            $("input[type='checkbox'][name='checkworks[]']").prop("checked",true);//全选
        } else {
            $("input[type='checkbox'][name='checkworks[]']").prop("checked",false);  //取消全选
        }
    })


    //列表数据中单独单击复选框
    $("body").delegate(".checkedres","click",function () {

        var sumcheckbox = $("input[type='checkbox'][name='checkworks[]']").length; //获取所有复选框的总个数
        var sumcheckedbox = $("input[type='checkbox'][name='checkworks[]']:checked").length; //获取选中的总个数
        //对比是否相等就全部选中，否则非全选
        if (sumcheckbox == sumcheckedbox) {
            $(".checkall").prop("checked",true);//全选
        }else{
            $(".checkall").prop("checked",false);//取消全选
        }
    });

    //tab 作品状态标签切换数据
    $(".news_tab").click(function () {
        var newsType = $(this).attr("data-val");
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);

        obj.limit = $("#pages-limit").val();
        obj.art_sign = newsType;
        loadTable(obj);
    });





    /*var newsAttr = '';//定义变量
    $(".left_newstitle ul li").on('click',function(){
        $(this).addClass("currentHo").siblings(".news_subNav").removeClass("currentHo");
        newsAttr = $(this).children("span").attr("attrVal");
        $('.newslist ul').css("display","block");
        $('.newslist .detail_main').css("display","none");
        $(".paginate").show();
        //点击改变列表输出的url
        $(".news_table").attr("data-url","/news/list?newstype="+newsAttr)
        var obj = {};
        obj.newstype = newsAttr;
        loadTable(obj);

        var newsTabVal = $(this).children("span").html();
        var newsKind = $(".newscont_title").html();
        if(newsTabVal!=newsKind){
            $(".newscont_title").html(newsTabVal);
            $(".newscont_title").removeClass("detail_cont_til").css("text-align","left");
        }

    })

    //消息详情页
    $("body").delegate(".news_table ul li a","click",function () {
        var news_id = $(this).attr("data-nid");
        loaddetail(news_id);
    })

    function loaddetail(news_id) {
        $('.newslist ul').css("display","none");
       /!* var html = '<div class="new_loading" style="text-align: center;margin-top:10px;position: absolute;z-index: 2;left: 0;right: 0;margin-left: auto;margin-right: auto;"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>';
        $('.newslist').append(html);*!/
        var url ="/news/detail/"+news_id;

        $.ajax({
            url : url,
            type: 'GET',
            data : {},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                console.log(data)
                if (data.status==200){
                    $(".paginate").hide();
                    //$(".new_loading").hide();
                    $(".newslist .news_detail_box").html(data.html);
                    var title = $(".detail_title").html();
                    console.log(title)
                    $(".newscont_title").html(title);
                    $(".newscont_title").addClass("detail_cont_til").css("text-align","center")
                }
            },
            error : function(){
                $(".loading").show();
            }
        });
    }*/



})