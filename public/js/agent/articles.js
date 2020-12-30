
$(function(){
    'use strict';


  // 点击渠道获取广告位置数据
  $("body").delegate('.subNav','click',function () {
      $(this).toggleClass("currentDd").siblings(".subNav").removeClass("currentDd")
      $(this).toggleClass("currentDt").siblings(".subNav").removeClass("currentDt")
      $(this).next(".navContent").slideToggle(300).siblings(".navContent").slideUp(500)

  });

  //遍历每个选中的让其高亮提留
  $('.subNavBox ul li a').each(function(){
    if($($(this))[0].href==String(window.location)){
      $(this).parents(".navContent").prev(".subNav").toggleClass("currentDt").siblings(".subNav").removeClass("currentDt")
      // $('.subNavBox ul li a').removeClass("current-active");
      $(this).addClass('current-active').parents(".navContent").css("display","block");
      var cubval = $(this).parents(".navContent").prev(".currentDt").html();
      var titleval = $(this).text();
      var ahref = $(this).attr("href")

      $('.help-content .crumbs .cateli a').text(cubval);
      $('.help-content .crumbs .detaili span').text(titleval);
      $('.help-content .crumbs .cateli a').attr('href',ahref);
    }
  });


  $('.help-common-nav-list-more a').click(function () {

    if ($(this).prev('.help-common-nav-list-more ul').css('display')=="block"){
      $(this).prev('.help-common-nav-list-more ul').slideToggle(300);
      $(this).siblings('.help-common-nav-list-more i').attr('class','fa fa-chevron-down');
      $(this).html('更多');
    }
    if ($(this).prev('.help-common-nav-list-more ul').css('display')=="none"){
      $(this).prev('.help-common-nav-list-more ul').slideToggle(300);
      $(this).html('收起');
      $(this).siblings('.help-common-nav-list-more i').attr('class','fa fa-chevron-up');
    }
  })








});
