
$(function(){
    'use strict';

  var curUrl = $("#menuFrame").attr('data-url');
  var stateObject = {};
  parent.history.replaceState(stateObject,null,'/#'+window.location.pathname);

  //点击添加或编辑按钮触发页面跳转
  $("body").delegate(".btn-inner-temp",'click',function () {
    var url = $(this).attr("data-url");
    // window.location.href = url;
    var stateObject = {};
    var newUrl = url;
    parent.history.replaceState(stateObject,null,'/#'+newUrl);
    parent.$("#menuFrame").attr('src',url);
  });
  //点击添加或编辑按钮触发页面判断规格参数是否设置
  $(document).ready(function () {
    var specDetail =  $(".width-p").html();

    if(specDetail == 0){
      $(".btn-sure").attr('disabled', 'disabled');
    }else {
      $(".btn-sure").attr('disabled', false);
    }
  });

  //点击子页数触发页面跳转到子页列表
  $("body").delegate(".btn-innerchild",'click',function () {
    var url = $(this).attr("data-url");
    window.location.href = url;
  });
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
        if(data.success == 'true'){
          tip_success('/templatecenter/inner');
        }else {
          var errorArray = new Array()
          errorArray['text'] = '数据保存出错了'
          errorArray['recover'] = 1
          errorArray['title'] = '警告'
          errorArray['is_confirm'] = 0
          tip_warn(errorArray)
        }
      }
    });
    return false;
  })






    //点击商品分类切换时联动规格
    $("body").delegate('#goods_type_id','change',function () {
      var id = $(this).val();
      var tgl =  $('#tgl').val();
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
            $('#spec-params').attr('data-url', '/templatecenter/inner/specdetail?id='+init);
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
    $('#spec-params').attr('data-url', '/templatecenter/face/specdetail?id='+id);
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

  // 点击切换审核状态
  $("body").delegate('.check_status','change',function () {
    var id = $(this).attr('data-id');
    var status = $(this).val();
    $.ajax({
      url : '/templatecenter/inner/checkstatus',
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
      },
      error : function(){
        layer.msg("请求操作失败");
      }
    });

  });




});
