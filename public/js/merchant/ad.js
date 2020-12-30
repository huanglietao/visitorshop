
$(function(){
    'use strict';


  //广告位置类目切换
  $("body").delegate('.channelTab','click',function () {
    var value = $(this).attr("data-val");

    $("#channel").val(value);
    var tj = JSON.stringify(getFormData($("form#search-form")));
    var obj = JSON.parse(tj);

    loadTable(obj);
    return false;

  });

  //广告列表tab切换
  $("body").delegate('.adlist_channel','click',function () {
    var value = $(this).attr("data-val");

    $("#channel").val(value);
    var tj = JSON.stringify(getFormData($("form#search-form")));
    var obj = JSON.parse(tj);

    loadTable(obj);
    return false;

  });

  // 点击渠道获取广告位置数据
  $("body").delegate('#channel_id','change',function () {

    var id = $(this).val();
    $.ajax({
      url : '/advertisement/adlist/getAdPosList',
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

          var positionhtml = '<option value="">请选择 </option>';
          if(id){
            for ( let [key,val] of Object.entries(list)){
              //console.log(key,val);
              positionhtml += "<option value='"+key+"'>"+val+"</option>";
            }
          }
          $('#ad_position').html(positionhtml);
        }

      },

      error : function(){
        console.log("error");
      }
    });

  });

  // 点击广告位置获取广告位置单一数据
  $("body").delegate('#ad_position','change',function () {

    var id = $(this).val();
    $.ajax({
      url : '/advertisement/adlist/getPositionInfo',
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
         // var list =data.data;
          $('#adthumb').attr('href', '/advertisement/adlist/posthumb?id='+id);
          if(id==1){
            $('.dis_style').show();
          }else {
            $('.dis_style').hide();
          }
        }

      },

      error : function(){
        console.log("error");
      }
    });

  });











});
