
$(function(){
    'use strict';


  // 点击渠道获取广告位置数据
  $("body").delegate('#art_type','change',function () {

    var id = $(this).val();
    $.ajax({
      url : '/article/list/getArticleType',
      type: 'POST',
      data:{
        id:id,
      },
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(data){
        console.log(data);
        if(data.success=='true'){
          var list =data.data;

       /*   $('#channel_id').val(list.channel_name);
          $("input[name='channel_id']").val(list.channel_id);*/
          $('#art_sign').val(list.cate_flag);
          $("input[name='art_sign']").val(list.cate_flag);
        }

      },

      error : function(){
        console.log("error");
      }
    });

  });













});
