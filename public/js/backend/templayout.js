
$(function(){
    'use strict';

  //===========选择商品分类联动操作规格标签数据 start =====================//
  $("body").delegate('#goods_type_id','change',function () {

    var id = $(this).val();

    $.ajax({
      url : '/templatelayout/main/getGoodsSpecLink',
      type: 'POST',
      data:{
        id:id,
      },
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(data){

        if(data.status==200){
          var list =data.data;

          var productSizehtml = '<option value="">请选择 </option>';
          if(id){
            for ( let [key,val] of Object.entries(list)){
              //console.log(key,val);
              productSizehtml += "<option value='"+key+"'>"+val+"</option>";
            }
          }
          $('.product-size').html(productSizehtml);
        }

      },

      error : function(){
        console.log("error");
      }
    });

  });
  //===========选择商品分类联动操作规格标签数据 end =====================//



  //===========选择规格标签联动获取规格数据 start =====================//
  $("body").delegate('#specifications_id','change',function () {

    var id = $(this).val();

    $.ajax({
      url : '/templatelayout/main/getSpecdetail',
      type: 'POST',
      data:{
        id:id,
      },
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(data){

        if(data.success=='true'){

          var style_list =data.data.size_style;
          var list =data.data.sdetail;

          //var sizeStylehtml = '<option value="">请选择 </option>';
          if(id){
            $('#layout_spec_style').val(style_list.size_name);
            $("input[name='layout_spec_style']").val(style_list.size_type); 
            /*for ( let [key,val] of Object.entries(style_list)){
              //console.log(key,val);
               sizeStylehtml+ = "<option value='"+key+"'>"+val+"</option>";
            }*/
          }
          //$('.size-type').html(sizeStylehtml);//规格标签渲染
          //渲染规格详情参数
          if(JSON.stringify(list) !== '[]'){

           $('.temp-page-spec').css('border','1px solid #d2d6de');
            var ys = '是';
            var yn = '否';
            var dpi = list.size_info_dpi;

            if(list.size_is_2faced==1){
              list.size_is_2faced = '双页';
            }else {
              list.size_is_2faced = '单页';
            }
             if(list.size_is_output==1){
               list.size_is_output = ys;
             }else {
               list.size_is_output = yn;
             }
            if(list.size_is_locked==1){
              list.size_is_locked = ys;
            }else {
              list.size_is_locked = yn;
            }
            if(list.size_is_display==1){
              list.size_is_display = ys;
            }else {
              list.size_is_display = yn;
            }
            if(list.size_is_cross==1){
              list.size_is_cross = '跨页';
            }else {
              list.size_is_cross = '不跨页';
            }

            var productSizehtml =
              ' <tr class="odd"><td style="width:80px;" align="right">设计区尺寸：</td><td style="width:120px;">宽:  '+list.size_design_w+' mm </td><td>高:  '+list.size_design_h+' mm</td></tr>' +
              '<tr><td style="width:80px;" align="right">设计区定位：</td><td style="width:120px;">上:  '+list.size_location_top+' mm </td> <td>左:  '+list.size_location_left+' mm</td></tr>' +
              '<tr class="odd"><td>&nbsp;</td><td style="width:80px;"> 下:  '+list.size_location_bottom+' mm </td><td>右:  '+list.size_location_right+' mm</td></tr>' +
              '<tr><td style="width:80px;" align="right">提示线：</td><td style="width:120px;">上:  '+list.size_tip_top+' mm </td> <td>左:  '+list.size_tip_left+' mm</td></tr>' +
              '<tr class="odd"><td>&nbsp;</td><td style="width:120px;"> 下:  '+list.size_tip_bottom+' mm </td><td>右: '+list.size_tip_right+' mm</td></tr>' +
              '<tr><td style="width:80px;" align="right">出血位：</td><td style="width:120px;">上:  '+list.size_cut_top+' mm </td> <td>左:  '+list.size_cut_left+' mm</td></tr>' +
              '<tr class="odd"><td>&nbsp;</td><td style="width:120px;"> 下:   '+list.size_cut_bottom+' mm </td><td>右:  '+list.size_cut_right+' mm</td></tr>' +
              '<tr><td style="width:80px;font-weight:bold" align="right">页面特性：</td><td style="width:120px;"></td><td></td></tr>' +
              '<tr class="odd"><td style="width:80px;" align="right">单/双页：</td><td style="width:120px;">'+list.size_is_2faced+'</td><td></td></tr>' +
              '<tr><td style="width:80px;" align="right">能否合成：</td><td style="width:120px;">'+list.size_is_output+'</td><td></td></tr>' +
              '<tr class="odd"><td style="width:80px;" align="right">能否编辑：</td><td style="width:120px;">'+list.size_is_locked+'</td><td></td></tr>' +
              '<tr><td style="width:80px;" align="right">能否显示：</td><td style="width:120px;">'+list.size_is_display+'</td><td></td></tr>' +
              '<tr class="odd"><td style="width:80px;" align="right">是否跨页：</td><td style="width:120px;">'+list.size_is_cross+'</td><td></td></tr>';

          }
          $('.table-striped').html(productSizehtml);
          $('#layout_dpi').val(dpi);
        }

      },

      error : function(){
        console.log("error");
      }
    });

  });
  //===========选择规格标签联动获取规格数据 end =====================//

// 点击切换审核状态
  $("body").delegate('.check_status','change',function () {
    var id = $(this).attr('data-id');
    var status = $(this).val();
    $.ajax({
      url : '/templatelayout/main/checkstatus',
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

   // 点击克隆布局
  $("body").delegate('.templayout-copy','click',function () {

    var id = $(this).attr('data-id');

    $.ajax({
      url : '/templatelayout/main/copy',
      type: 'POST',
      data:{
        id:id,
      },
      dataType : 'JSON',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success : function(data){
        if(data.success=='true'){
          layer.msg("操作成功");

        }
      },
      error : function(){
        layer.msg("请求操作失败");
      }
    });

  });
   // 点击克隆模板布局结束//






});
