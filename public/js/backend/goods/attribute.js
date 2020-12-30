var upload;
$(function(){
    'use strict';
    //全选
    //添加属性值

    $("body").delegate(".btn-attr-add",'click',function () {
        var del = '<a href="javascript:;" class="btn btn-xs btn-danger btn-attr-del" title="删除"><i class="fa fa-trash"></i></a>';

        var html = "<tr><td><input data-rule='required'  type='text' name='attrValue[attr_val_name][]' value=''>";
        html += "<td> <input type='hidden' name='attrValue[attr_val_icon][]'  value=''> <img style='width:30px' src=''/> <input style='width:140px;display: inline-block' type='file' onchange='upload(this,0)'> </td>";
        html += "<td><input type='text' name='attrValue[sort][]' value=''></td>";
        html += "<td>"+del+"</td></td></tr>";

        $('.border_table tbody').append(html);
    });
        //标记性删除不需要的属性值
    $("body").delegate(".btn-attr-del",'click',function (e) {
        e.preventDefault();
        var that = this;
        layer.confirm('确定删除此项吗?', {
                btn: ['确定', '取消'] //可以无限个按钮
                ,cancel: function(index, layero){
                    //取消操作，点击右上角的X
                    //按钮【按钮二】的回调
                    layer.close(index);

                }
            },
            function(index, layero){
                //按钮【按钮一】的回调
                layer.close(index);
                var id = $(that).parents('tr').find('.attrid').val();
                if(typeof id!="undefined"){
                    $.ajax({
                        url : '/goods/products_attribute/del_attr_value',
                        type : 'POST',
                        dataType : 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        data : {id : id},
                        success : function (data) {
                            return true;
                        }
                    });
                }
                $(that).parents('tr').remove();


            }, function(index){
                //按钮【按钮二】的回调
                layer.close(index);


            });
    });
    upload = function(obj,is_multiple){
        var files = obj.files;
        for(var i = 0;i<files.length;i++){
            var fd = new FormData();
            //fd.append('dir',uploads.dir);
            var file = files[i];
            // console.log(file)
            fd.append('file',file);

            $.ajax({
                url : '/ajax/upload',
                type : 'POST',
                dataType : 'JSON',
                data : fd,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                cache: false,
                contentType: false,
                processData: false,
                success : function (data) {
                },
                error:function (e) {
                    if (e.readyState == 4 && e.status == 200) {

                        //alert(xhr.responseText.url); // handle response.
                        //console.log(json.data.url)
                        if(is_multiple == 0){
                            $(obj).prev().prev().val(e.responseText)
                            $(obj).prev().attr('src',e.responseText)
                        }

                    }
                }
            });

            /*var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    //alert(xhr.responseText.url); // handle response.
                    var json = JSON.parse( xhr.responseText );
                    //console.log(json.data.url)
                    if(is_multiple == 0){
                        $(obj).prev().prev().val(json.data.url)
                        $(obj).prev().attr('src',json.data.url)
                    }

                }
            };


            xhr.open("POST", "/ajax/upload");
            xhr.send(fd);*/
        }

    }




});