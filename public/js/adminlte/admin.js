/**
 * ui框架处理的js
 * Created by yanxs on 2019/7/26.
 */

$(function(){
    'use strict'

    $(document).ready(function () {
        var b_height = ( parent.$(".sidebar").height()/2);
        console.log(parent.$(".header-next-btn"));
        parent.$(".header-next-btn").css("top",b_height+"px")
        parent.$(".header-next-btn").show();
    });

    //窗口大小变更事件
    $(window).resize(function() {
        var b_height = ( parent.$(".sidebar").height()/2);
        parent.$(".header-next-btn").css("top",b_height+"px")
        parent.$(".header-next-btn").show();

    });
    parent.$(".header-next-btn").click(function () {
        if (parent.$(".header-next-btn").attr("data-action")=="hide")
        {
            parent.$(".header-next-btn").find(".fa-angle-left").removeClass("fa-angle-left").addClass("fa-angle-right");
            parent.$(".header-next-btn").attr("data-action","show")
        }else{
            parent.$(".header-next-btn").find(".fa-angle-right").removeClass("fa-angle-right").addClass("fa-angle-left");
            parent.$(".header-next-btn").attr("data-action","hide")
        }
    });


    //重置菜单栏pdding值
    var hashUrl = window.location.hash;
    $(".os-padding").find(".os-content").css('padding',0);
    //获取当前hashurl
    if(hashUrl != '') {
        //获取实际路由
        var hashUrlNew = hashUrl.substring(1);
        $("#menuFrame").attr('src',hashUrlNew)

        $(".nav-item>.menu-link").each(function(i,v){
            var curUrl = $(this).attr('data-url');

            if(hashUrlNew == curUrl && !$(this).hasClass('active')) {
                changePages($(this),0);
            }

        })
    }else {
        //自动触发菜单第一项
        //$('.os-content').find(".nav-item>.menu-link")[0].click();
        $("#menuFrame").attr('src',$('.os-content').find(".nav-item>.menu-link").eq(0).attr('data-url'))
    }


    $(".menu-link").on('click', function (event,is_son) {
        if(is_son == 'true') {
            var is_replace = 0;
        } else {
            var is_replace = 1;
        }
        //切换页面
        changePages($(this),is_replace);
    })

    //第三级触发，第二级和第一级都是选中状态
    $(".menu-lv-2").find('.nav-link').click(function(event,is_son){
        //补充第二级选中的状态(待实现)
    })

    //切换面面，如果is_replace为1 ，则切换右侧内容，否则只切换菜单
    function changePages(obj,is_replace)
    {

        $('.menu-link').removeClass('active');
        $('.has-treeview').removeClass('menu-open');
        $('.nav-treeview').hide();

        obj.addClass('active');
        obj.parents('.has-treeview').addClass('menu-open');
        obj.parents('.has-treeview').find('.nav-treeview').show();


        //点击菜单时变动右侧内容
        if(is_replace == 1) {
            $("#menuFrame").attr('src',obj.attr('data-url'));
        }


        var curUrl = obj.attr('data-url');
        //改变url栏地址
        var stateObject = {};
        var title = obj.find('p').html();
        var newUrl = curUrl;
        history.replaceState(stateObject,title,'/#'+newUrl);

            //次每次切换把上一次的url记录起来

    }

    //平台菜单点击
    $("body").delegate(".oms_mode_tab",'click',function () {
        $(this).addClass("nav_status_current").siblings(".oms_mode_tab").removeClass("nav_status_current");
        var flag = $(this).attr('data-val')
        $.ajax({
            url : '/change_flag',
            type: 'POST',
            data:{flag:flag},
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                location.href=""
            },
            error : function(){
                tip_note('程序出现错误')
            }
        });


    })



})