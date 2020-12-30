
$(function(){
    'use strict';


    //类目切换
    $("body").delegate('.nav_status_btn','click',function () {
        var value = $(this).attr("data-val");

        $(".mtype_f").val(value);
        var tj = JSON.stringify(getFormData($("form#search-form")));
        var obj = JSON.parse(tj);

        loadTable(obj);
        return false;

    });


  //===========素材分类一级联动操作二级数据 start =====================//

        $("body").delegate(".mater-cate","change",function () {
          var material_id = $(this).val();
          if(material_id!=''){
            $('.material-upload').show();
          }else{
            $('.material-upload').hide();
          }

        $.ajax({
          url : '/templatecenter/material/getMaterialCate',
          type: 'POST',
          data:{
            id:material_id,
          },
          dataType : 'JSON',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          },
          success : function(data){
            console.log(data);
            if(data.data.list.length>0){
              var materialhtml = '<option value="">请选择 </option>';
              if(material_id != 0){
                for(var j=0; j<data.data.list.length; j++){
                  materialhtml += "<option value='"+data.data.list[j]['cate_id']+"'>"+data.data.list[j]['cate_name']+"</option>";
                }
              }
              $('select[name="material_cateid"]').html(materialhtml);

              if(data.data.list[0]['cate_flag']=='frame'){
                $('#upload-decorate').hide();
                $('#upload-frame').show();
              }else{
                $('#upload-decorate').show();
                $('#upload-frame').hide();
              }
            }

          },
          error : function(){
            layer.msg('请求操作失败');
          }
        });

      });
//===========素材分类一级联动操作二级数据 end =====================//




});
