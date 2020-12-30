
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
          url : '/template/material/getMaterialCate',
          type: 'POST',
          data:{
            id:material_id,
          },
          dataType : 'JSON',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          },
          success : function(data){
            if(data.data.list.length>0){
              var materialhtml = '<option value="">请选择 </option>';
              if(material_id != 0){
                for(var j=0; j<data.data.list.length; j++){
                  materialhtml += "<option value='"+data.data.list[j]['cate_id']+"'>"+data.data.list[j]['cate_name']+"</option>";
                }
              }
              $('select[name="material_cateid"]').html(materialhtml);
            console.log(data.data.list[0]['cate_flag']);
              if(data.data.list[0]['cate_flag']=='frame'){
                $('#upload-decorate').hide();
                $('#upload-frame').show();
                $('#upload-special').hide();
              }else if(data.data.list[0]['cate_flag']=='decorate'){
                $('#upload-decorate').show();
                $('#upload-frame').hide();
                $('#upload-special').hide();
              }else if(data.data.list[0]['cate_flag']=='special'){
                $('#upload-decorate').hide();
                $('#upload-frame').hide();
                $('#upload-special').show();
              }
            }

          },
          error : function(){
            layer.msg("请求操作失败");
          }
        });

      });
//===========素材分类一级联动操作二级数据 end =====================//


    //类目切换
    $("body").delegate('#mater_flag','change',function () {
        var value = $(this).val();

        $.ajax({
            url : '/template/material/getMaterCateFlag',
            type: 'POST',
            data:{
                flag:value,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                var materialhtml = '<option value="">请选择 </option>';
                for(var j=0; j<data.data.length; j++){
                    materialhtml += "<option value='"+data.data[j]['cate_id']+"'>"+data.data[j]['cate_name']+"</option>";
                }

                $('.mater-catelist').html(materialhtml);
            },
            error : function(){
                layer.msg("请求操作失败");
            }
        });

        return false;

    });




});
