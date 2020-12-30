var searchTemplate;
$(function () {


    if($("body").find(".paginate").length > 0){
        page();
        function page() {
            var total = $("#page").val();
            var curPage = $("#pages-item").val();
            //渲染分页数据
            $("#list-total").html(total); //总数

            var limit = parseInt($("#pages-limit").val());
            if(isNaN(limit)) {
                limit = 16;
            }

            //页数
            var pages = Math.ceil(total/limit);
            $("#limit_pages").html(pages);
            $("#total_pages").val(pages);
            if(pages==1  || pages==0){
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
                    selected = "selected";
                } else {
                    selected = "";
                }
                option += "<option "+selected+" value='"+i+"'>第"+i+"页</option>";
            }

            $("#pages-item").html(option);
        }

    }

    //展示更多
    $('body').delegate('.hit_show_up','click',function () {
        var title_value = $('.showup').attr('data_title');
        if (title_value == 'h') {
            $(this).children('span').text('收起更多');
            $('.showup').attr('data_title', 's').css("display", "block");
            // $('#templatelist li:first').css('height','180px')
        } else {
            $(this).children('span').text('展示更多');
            $('.showup').attr('data_title', 'h').css("display", "none");
            // $('#templatelist li:first').css('height','50%')
        }
    });



    //模板或者便签点击选择
    $('body').delegate('.temlateLi','click',function () {
        $(this).addClass("active").siblings("li").removeClass("active");
        var parent_id = $(this).parent().attr('id');
        if(parent_id=='templatelist'){
            var temp_cate_value = $(this).attr('data-value');//模板id
            var temp_tagsvalue = $('#templatetags').children('.active').attr('data-value');//标签id
        }
        if(parent_id=='templatetags'){
            var temp_tagsvalue = $(this).attr('data-value');//标签id
            var temp_cate_value = $('#templatelist').children('.active').attr('data-value');//模板id
        }
        var limit= $("#pages-limit").val();//显示条数;//显示条数
        var page = 1;//当前页
        searchTemplate(temp_cate_value,temp_tagsvalue,limit,page);
    });

    //点击排序选择
    $('body').delegate('.selector','change',function () {
        var temp_tagsvalue = $('#templatetags').children('.active').attr('data-value');//标签id
        var temp_cate_value = $('#templatelist').children('.active').attr('data-value');//模板id
        var limit= $("#pages-limit").val();//显示条数;//显示条数
        var page = 1;//当前页
        searchTemplate(temp_cate_value,temp_tagsvalue,limit,page);
    });

    //搜索
    $('body').delegate('.fa-search','click',function () {
        var temp_tagsvalue = $('#templatetags').children('.active').attr('data-value');//标签id
        var temp_cate_value = $('#templatelist').children('.active').attr('data-value');//模板id
        var limit= $("#pages-limit").val();//显示条数;//显示条数
        var page = 1;//当前页
        searchTemplate(temp_cate_value,temp_tagsvalue,limit,page);
    });


    //==================分页相关js开始 =========//
    $("body").delegate("#pages-item","change",function () {
        var temp_tagsvalue = $('#templatetags').children('.active').attr('data-value');//标签id
        var temp_cate_value = $('#templatelist').children('.active').attr('data-value');//模板id
        var limit= $('#pages-limit').val();//显示条数
        var page = $("#pages-item option:selected").val();//当前页
        searchTemplate(temp_cate_value,temp_tagsvalue,limit,page);
    });
    $("body").delegate("#pages-limit","change",function () {
        var temp_tagsvalue = $('#templatetags').children('.active').attr('data-value');//标签id
        var temp_cate_value = $('#templatelist').children('.active').attr('data-value');//模板id
        var limit= $('#pages-limit').val();//显示条数
        var page = 1;//当前页
        searchTemplate(temp_cate_value,temp_tagsvalue,limit,page);

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
            var temp_tagsvalue = $('#templatetags').children('.active').attr('data-value');//标签id
            var temp_cate_value = $('#templatelist').children('.active').attr('data-value');//模板id
            var limit = $("#pages-limit").val();//显示条数
            var page = curPage;//当前页
            searchTemplate(temp_cate_value,temp_tagsvalue,limit,page);
        }
    });
    //======================分页js结束 =============//



    searchTemplate = function(temp_cate_value,temp_tagsvalue,limit,page) {
        var prod_id = $("#prod_id").val();
        var sku_id = $("#sku_id").val();
        var page_num = $("#page_num").val();
        var mid = $("#mid").val();
        var aid = $("#aid").val();
        var order_no = $("#order_no").val();

        var sel_value = $(".selector").val();//按热度或者按时间
        var temp_name = $(".temp_name").val();//搜索的内容


        $(".foreach-con .foreach-data").hide();
        $(".no-data").html("数据加载中....");
        $(".no-data").show();

        $.ajax({
            url :'/goods/get_template',
            type: 'POST',
            data : {
                mid:mid,
                aid:aid,
                order_no:order_no,
                prod_id:prod_id,
                sku_id:sku_id,
                page_num:page_num,
                cate_id:temp_cate_value,
                tag_id:temp_tagsvalue,
                sel_value:sel_value,
                temp_name:temp_name,
                limit:limit,
                page:page
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success:function(data) {
                if (data.status == 101) {
                    $(".no-data").html("该模版无数据");
                    $(".paginate").hide();
                } else {
                    $(".no-data").hide();
                    $(".paginate").show();
                    $(".foreach-con").empty();
                    $(".foreach-con .foreach-data").show();
                    $("#page").val(data.total);
                    var template = data.template;
                    for (var i = 1; i <= template.length; i++) {
                        //图片加载出错时的默认图
                        var err = "this.src='http://agent.my.com/images/home/moren.jpg'";
                        var j = i - 1;

                        var html = '<div class="col-lg-3 row-item foreach-data" data-type="free"><div class="addon-item"> ';
                        if (template[j].main_temp_use_times >= 100) {
                            html += '<div class="tags tags-recommend"></div>';
                        }
                        // if (template[j].restime <= Config.t) {
                        //     html += '<div class="tags tags-recommend new-tags"></div>';
                        // }

                        html += ' <div class="addon-img"><a href="'+template[j].url+'" title="' + template[j].main_temp_name + '" target="_blank"> ' +
                            '<img src="' + template[j].main_temp_thumb + '" alt="' + template[j].main_temp_name + '" onerror="' + err + '" class="img-responsive"></a> ' +
                            '</div><div class="addon-info"><div class="title"> ' +
                            '<a href="'+template[j].url+'" target="_blank" title="' + template[j].main_temp_name + '">' + template[j].main_temp_name + '</a></div> ';
                        if(!template[j].max_photo){
                            html +=  '<div class="title" style="min-height: 18px"></div>';
                        }else{
                            html += '<div class="title">推荐照片数:<span>' + template[j].min_photo + '张</span> - <span>' + template[j].max_photo + '张</span></div>';
                        }
                        html += '<div class="metas clearfix"  style="border-top:1px dotted #ddd;margin-top: 7px;>' +
                            '<a target="_blank" href="'+template[j].url+'" style="text-decoration: none">' +
                            '<span id="zz" style="cursor:pointer;background: #CBCBCB;padding:6px 15px;font-size:14px;color:#fff;border-radius: 5px;float: left">预览模板</span></a> ' +
                            '<a target="_blank" href="'+template[j].url+'" style="text-decoration: none">' +
                            '<span id="yl" style="cursor:pointer;background: #4DCE61;padding:6px 15px;font-size:14px;color:#fff;border-radius: 5px;float: right">开始制作</span>' +
                            '</a> </div> </div> </div> </div>';
                        $(".foreach-con").append(html);
                    }

                    //渲染分页数据
                    $("#list-total").html(data.total); //总数

                    var limit = parseInt($("#pages-limit").val());
                    if(isNaN(limit)) {
                        limit = 16;
                    }

                    //页数
                    var pages = Math.ceil(data.total/limit);
                    $("#limit_pages").html(pages);
                    $("#total_pages").val(pages);
                    if(pages==1  || pages==0){
                        $(".page-act").hide();
                        return;
                    }else {
                        $(".paginate").show();
                        $(".page-act").show();
                    }

                    //当前页
                    var  currentPage = page;

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

    };




});