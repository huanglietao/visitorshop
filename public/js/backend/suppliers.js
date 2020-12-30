var areaAdd;
var chooses;
$(function(){
    'use strict';


    //新页面弹窗功能
    $('#main').delegate("#btn_area",'click',function () {
        setTimeout(function(){
            if($("body").find("select[name='area_province']").length > 0){
                var self = $("select[name='area_province']");
                //填充省份option
                $.ajax({
                    url : '/ajax',
                    type: 'POST',
                    data:{
                        id:0,
                    },
                    dataType : 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success : function(data){
                        var provinces_html = '';
                        for(var i=0; i<data.list.length; i++){
                            provinces_html += "<option value='"+data.list[i]['area_id']+"'>"+data.list[i]['area_name']+"</option>";

                        }
                        // $(".areas-province").append(provinces_html);
                        self.append(provinces_html);
                    },
                    error : function(){
                        console.log("error");
                    }
                });
            }
        },300);

    });

    areaAdd =function (obj) {

        var text = $(obj).parent().prev().children(":first-child").children("select").eq(2).find("option:selected").text();//选中地区的数据
        var area_id = $(obj).parent().prev().children(":first-child").children("select").eq(2).val();//选中地区的id

        if (area_id == '' || area_id == null || area_id == '区' || area_id == 0) {
            text = $(obj).parent().prev().children(":first-child").children("select").eq(1).find("option:selected").text();//选中地区的数据
            area_id = $(obj).parent().prev().children(":first-child").children("select").eq(1).val();//选中地区的id

            if (area_id == '' || area_id == null || area_id == -1 || area_id == 0) {
                text = $(obj).parent().prev().children(":first-child").children("select").eq(0).find("option:selected").text();//选中地区的数据
                area_id = $(obj).parent().prev().children(":first-child").children("select").eq(0).val();//选中地区的id
            }
        }

        if (area_id == '' || area_id == 0 || area_id == -1 || area_id == '区') {
            alert('请选择地区');
            return false;
        }

        var exists = $(obj).parent().parent().next().find('.ys-click').text();
        var is_add = $(obj).parent().parent().next().find('.ys-no').text();

        if (exists != '' || is_add != '') {

            //是否已经选中
            if (exists.indexOf(text) != -1) {
                alert('该地区已经选择');
                return false;
            }
            //是否已经添加
            if(is_add.indexOf(text) != -1){
                alert("该地区已添加");
                return false;
            }
        }
        var html = "<div class='ys-p  ys-no ys-click' data-id='"+area_id+"'>"+text+"<div style='display: inline-block'><i class='fa fa-trash fa-lg icon' aria-hidden='true' onclick='chooses(this)'></i></div></div>";
        $(obj).parent().parent().next().append(html);

        var exists_area_id =  $(obj).parent().prev().prev().val();

        if (exists_area_id != '') {
            area_id = exists_area_id+';'+area_id
        }


        $(obj).parent().prev().prev().val(area_id);
    };


    chooses = function(obj) {
        var exist_area_id = $(obj).parent().parent().parent().prev().find('input[name="sup_service_area"]').val();
        var area_id = $(obj).parent().parent().attr('data-id');
        var arr = exist_area_id.split(";");
        arr.splice($.inArray(area_id,arr),1);
        var n_area_id = arr.join(";");
        $(obj).parent().parent().parent().prev().find('input[name="sup_service_area"]').val(n_area_id);
        $(obj).parent().parent().remove();

    };


});