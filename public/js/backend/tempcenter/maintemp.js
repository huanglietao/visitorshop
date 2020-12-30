
$(function(){
    'use strict';


  var curUrl = $("#menuFrame").attr('data-url');
  var stateObject = {}
  parent.history.replaceState(stateObject,null,'/#'+window.location.pathname);

  //点击添加或编辑按钮触发页面跳转
  $("body").delegate(".btn-temp",'click',function () {
     var url = $(this).attr("data-url");
    // window.location.href = url;
    var stateObject = {};
    var newUrl = url;
    parent.history.replaceState(stateObject,null,'/#'+newUrl);
    parent.$("#menuFrame").attr('src',url);

  });

  //点击子页数触发页面跳转到子页列表
  $("body").delegate(".btn-tempchild",'click',function () {
    var url = $(this).attr("data-url");
    window.location.href = url;
  });

 /* $(document).ready(function () {
    var specDetail =  $(".width-p").html();

    if(specDetail == 0){
      $(".btn-sure").attr('disabled', 'disabled');
    }else {
      $(".btn-sure").attr('disabled', false);
    }
  });*/
  //提交按钮触发
  $("body").delegate(".btn-temp-submit",'click',function () {

    var $form = $(this).parents(".templayui-layer").find('form');
    var url = $form.attr('action');
    var that = $(this);
    $form.trigger("validate");

    //没有出现验证错误情况下才提交
    if($form.find('.form-control').hasClass('n-invalid')) {
      return false;
    }
    var postData = $(this).parents(".templayui-layer").find('#form-save').serialize();
    //提交前摧毁台挂历的变量值
    $('#tgl').val('');

    $.ajax({
      url : url,
      type : 'POST',
      data : postData,
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function (data) {
        if(data.code == 442 && data.success== "false") {
          //错误验证,把错误显示和js的错误显示合并掉
          for (var i in data.errors) {
            var field = i;
            //console.log(field);
            $form.find("#"+field).removeClass('n-valid');
            $form.find("#"+field).addClass('n-invalid');
            $form.find("#"+field).parent().next().show();
            $form.find("#"+field).parent().next().html(data.errors[field]);
          }
          return false;
        }else if (data.success== "false"){
          layer.msg('',{
            title: false,
            content: data.message,
            closeBtn:0,
            offset: 'auto',
            icon:5,
            zIndex: layer.zIndex, //重点1
            success: function(layero){
              layer.setTop(layero); //重点2
            }
          });
          return false;
        }
        tip_success('/templatecenter/main');
    /*    var layerIndex = that.parents(".templayui-layer").attr("times");
        console.log(that.parents(".templayui-layer"));
        //layer.close(layerIndex);
        $("#act-btn>.btn-refresh").trigger('click')*/
      }
    });
    return false;
  })






    //点击商品分类切换时联动规格
    $("body").delegate('#goods_type_id','change',function () {
      var id = $(this).val();
      var tgl = $('#tgl').val();
      var goodsFlag = $(this).find("option:selected").attr("data-flag");
      var face =  $('#face_temp').val();

      $.ajax({
        url : '/templatecenter/main/getGoodsSpecLink',
        type: 'POST',
        data:{
          id:id,
          tempf:face,
        },
        dataType : 'JSON',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success : function(data){
          if(data.status==200){

            var linkArr = data.list[id];
            var html = '';
            var init ='';

            for(var i in linkArr) {
              if(init == '') {
                init = i;
              }
              html += "<option value='"+i+"'>"+linkArr[i]+"</option>";
            }

            $('#specifications_id').html(html);
            //规格参数的data-url变化
            $('#spec-params').attr('data-url', '/templatecenter/main/specdetail?id='+init);
            //封面配置和内页的配置的url
            $("#face-temp").attr('data-url','/templatecenter/main/setting?type=1&goods_type='+id+"&spec="+$('.gg').val());
            $("#inner-temp").attr('data-url','/templatecenter/main/setting?type=2&goods_type='+id+"&spec="+$('.gg').val());
             //获取背景尺寸
            getBackPx(init);
          }

        },
        error : function(){
          console.log("error");
        }
      });

      //商品分类为台历时年份显示，其他隐藏
      if(goodsFlag == tgl)
      {
        $('#calendar-year').show();
      }
      else
      {
        $('#calendar-year').hide();
      }

    });

  //选择产品规格触发
  $("body").delegate('#specifications_id','change',function () {
    var id = $(this).val();
    $('#spec-params').attr('data-url', '/templatecenter/main/specdetail?id='+id);
    $("#face-temp").attr('data-url','/templatecenter/main/setting?type=1&goods_type='+$('select[name="goods_type_id"]').val()+"&spec="+id);
    $("#inner-temp").attr('data-url','/templatecenter/main/setting?type=2&goods_type='+$('select[name="goods_type_id"]').val()+"&spec="+id);
    //获取背景尺寸
    getBackPx(id);
  });

  //获取背景尺寸
  function getBackPx(id)
  {
    $.ajax({
      url : '/templatecenter/main/getBackPx',
      type : 'POST',
      data : {sizeId:id},
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function (data) {
        console.log(data);
        if(data.success = 'true') {

          if(data.data.width == 0 || data.data.height == 0)
          {
            $("#have").hide();
            $("#back-tips").show();
            $("#back-tips").html("(该规格未设置参数值)");
            $(".btn-sure").attr('disabled', 'disabled');
          } else {
            $("#back-tips").hide();
            $("#have").show();
            $('.width-p').html(data.data.width);
            $('.height-p').html(data.data.height);
            $(".btn-sure").attr('disabled', false);
          }
        }
      }
    });

  }

  // 点击切换vip模板或前端显示
  $("body").delegate('.btn-change','click',function () {
    var flag = $(this).attr('data-flag');
    var id = $(this).attr('data-id');

    $.ajax({
      url : '/templatecenter/main/updateField',
      type: 'POST',
      data:{
        flag:flag,
        id:id,
      },
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(data){
        layer.msg("操作成功");
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);
        obj.limit = $("#pages-limit").val();
        obj.page = $("#pages-item").val();
        loadTable(obj);
       /* setTimeout(function () {
          window.location.reload();
        }, 1000);*/
      },
      error : function(){
        console.log("error");
      }
    });

  });

  // 点击切换审核状态
  $("body").delegate('.check_status','change',function () {
    var id = $(this).attr('data-id');
    var status = $(this).val();
    $.ajax({
      url : '/templatecenter/main/checkstatus',
      type: 'POST',
      data:{
        status:status,
        id:id,
      },
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(data){
        layer.msg("操作成功");
       /* setTimeout(function () {
          window.location.reload();
        }, 1000);*/
      },
      error : function(){
        console.log("error");
      }
    });

  });

  // setting配置js开始 //
  $('body').delegate('.temp-list','click', function() {
    $('.temp-list').removeClass('img-active');
    $(this).addClass('img-active');

  });

  //点击模板分类触发
  $('body').delegate('.cate-item','click', function() {
    $('.cate-item').removeClass('cate-active');
    $(this).addClass('cate-active');
    getData(0);
  });

  //规格选择
  $('body').delegate('#spec','change', function() {
    getData(0);
  });
  //内页标签
  $('body').delegate('#inner_type','change', function() {
    getData(0);
  });
  //输入查询
  $('body').delegate('#search-value','keyup', function() {
    getData(0);
  });

  //翻页
  $('body').delegate('.fy','click', function() {
    var page = $(this).attr('data-page');
    if(typeof(page)  == 'undefined' || page=='') {
      return false;
    }
    getData(page);

  });


  //获取ajax请求数据
  function getData(page)
  {
    var cate_id = $('.cate-active').attr('data-id');
    var type = $("#page-type").val();
    var goodsType = $("#goodsType").val();
    var data = {
      page    : page,
      cate_id :cate_id,
     // search_type : $('#search-item').val(),
      search_value : $('#search-value').val(),
      type: type,
      goods_type: goodsType,
      spec :$('#spec').val(),
      inner_type :$('#inner_type').val()
    };

    $("#main1").html('');
    $('.fy').hide();
    $("#loading").show();
    //ajax请求数据
    $.ajax({
      url : '/templatecenter/main/tempdata',
      type : 'POST',
      data : data,
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(ret){
        console.log(ret);
          $("#loading").hide();
          var html = createList(ret.data);
          $("#main1").html(html);
          $('#total_fy').remove();
          if(ret.data.total > ret.data.limit)
          {
              if($('#total_fy').length==0){
                  var pages = page==0 ? 1 : page;
                  var new_page = parseInt(pages)+1;
                  var fy_html = "<div style='text-align: center;margin-top:10px' id='total_fy'>" +
                      "<span class='page-record' style='margin-right:20px'>总共<span>"+ret.data.total+"</span>条</span>" +
                      "<span  id='prev' class='fy' style='cursor: not-allowed'>上一页</span>&nbsp;&nbsp;&nbsp;&nbsp;" +
                      "<span id='next' class='fy' data-page='+new_page+'>下一页</span>&nbsp;&nbsp;&nbsp;&nbsp;</div>";

                  $('#right').append(fy_html);
              }else {
                  $('.fy').show();
              }
          } else {
              $('.fy').hide();
          }

        if(ret.data.pagesize  <= ret.data.page) {
          $("#next").css('cursor' , 'not-allowed');
          $("#next").attr('data-page', '');
          $("#prev").css('cursor' , 'pointer');
          $("#prev").attr('data-page', parseInt(ret.data.page)-1);
        }else if(ret.data.page >1 && ret.data.page <ret.data.pagesize ){
          $("#prev").css('cursor' , 'pointer');
          $("#prev").attr('data-page', parseInt(ret.data.page)-1);
          $("#next").css('cursor' , 'pointer');
          $("#next").attr('data-page', parseInt(ret.data.page)+1);
        }

        if(ret.data.page <= 1) {
          $("#prev").css('cursor' , 'not-allowed');
          $("#prev").attr('data-page', '');
          $("#next").css('cursor' , 'pointer');
          $("#next").attr('data-page', parseInt(ret.data.page)+1);
        }

      },
      error :function () {
        return '<div style="margin-top:20%;margin-left:40%">无相关记录</div>';
      }

    });

  }

  //列表数据渲染
  function createList(data)
  {
    var html = "";
    if(data.total == 0) {
      return '<div style="margin-top:20%;margin-left:40%">无相关记录</div>';
    }

    if(data.type == 1){
      $.each(data.list, function(k,v) {
        html += '<div class="temp-list" data-id="'+v.cover_temp_id+'" data-name="'+v.cover_temp_name+'"><img src="'+v.cover_temp_thumb+'" style="width:110px;"><p style="text-align: center">'+v.cover_temp_name+'</p></div>';
      });
    }else {
      $.each(data.list, function(k,v) {
        html += '<div class="temp-list" data-id="'+v.inner_temp_id+'" data-name="'+v.inner_temp_name+'"><img src="'+v.inner_temp_thumb+'" style="width:110px;"><p style="text-align: center">'+v.inner_temp_name+'</p></div>';
      });
    }


    return html;
  }


  //点击配置dialog的确定
  $('body').delegate('#setting','click', function() {

    var that = $(this);
    var type = $("#page-type").val();
    if(type == 1) {
      //取选中的
      var tid = $('.img-active').attr('data-id');

      if(typeof(tid) == 'undefined')
      {
        $('body').find("#face-setting").val('');
        $('body').find("#face-name").html('');
        parent.$("#face-setting").val('');
        parent.$("#face-name").html('');
      } else {
        $('body').find("#face-setting").val(tid);
        $('body').find("#face-name").html($(".img-active").attr('data-name'));
      }
      var layerIndex = that.parents(".layui-layer").attr("times");
      layer.close(layerIndex);
    } else {
      var tid = $('.img-active').attr('data-id');

      if(typeof(tid) == 'undefined')
      {
        $('body').find("#inner-setting").val('');
        $('body').find("#inner-name").html('');
      } else {
        $('body').find("#inner-setting").val(tid);
        $('body').find("#inner-name").html($(".img-active").attr('data-name'));
      }
      var layerInner = that.parents(".layui-layer").attr("times");
      layer.close(layerInner);

    }
  });
  //  setting配置js结束//

  // 点击克隆模板
  $("body").delegate('.temp-copy','click',function () {

    var id = $(this).attr('data-id');

    $.ajax({
      url : '/templatecenter/main/copy',
      type: 'POST',
      data:{
        id:id,
      },
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(data){
        console.log(data)
       if(data.success=='true'){
         tip_success();
       }else {
         layer.msg(data.message);
       }
      },

    });

  });
  // 点击克隆模板 结束//





});
