
$(function(){
    'use strict';


   // 点击切换是否显示菜单功能
  $("body").delegate('.btn-change','click',function () {
    var flag = $(this).attr('data-flag');
    var id = $(this).attr('data-id');

    $.ajax({
      url : '/merchant/omsrule/updateField',
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

        if(data.success == 'true'){
          layer.msg("操作成功");
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        }else {
          layer.msg("操作失败");
        }

      },
      error : function(){
        console.log("操作失败");
      }
    });

  });
















});
