
$(function(){
    'use strict';

    //日志页面，table的日志选择 start
    //全选
    $(".no-border-table").delegate('.checkall','click',function () {
        if ($(".checkall").prop("checked")) {
            $("input[type='checkbox'][name='checkedres']").prop("checked",true);//全选
        } else {
            $("input[type='checkbox'][name='checkedres']").prop("checked",false);  //取消全选
        }
        var chsub = $("input[type='checkbox'][name='checkedres']").length; //获取subcheck的个数

        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数

        if (checkedsub == chsub) {
            $("#checkall").prop("checked", true);
        }else{
            $(".checkall").prop("checked", false);
        }

    });

    //单击单选框
    $(".tbl-content").delegate(".checkedres","click",function () {
        if (!$(this).checked) {
            $(".checkall").prop("checked", false);
        }
        var chsub = $("input[type='checkbox'][name='checkedres']").length; //获取subcheck的个数

        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数

        if (checkedsub == chsub) {
            $(".checkall").prop("checked", true);
        }else{
            $(".checkall").prop("checked", false);
        }

    });
    //日志详情页关闭
    $("body").delegate(".close-detail","click",function () {
        //详情页关闭
        $(".layui-layer").hide();
    });

    //日志页面，table的日志选择 end


    //角色组添加页面JS  start
    //全选
    $("body").delegate('.allcheck','click',function () {
        if ($(".allcheck").prop("checked")) {
            $("input[type='checkbox'][name='checkedres']").prop("checked",true);//全选
        } else {
            $("input[type='checkbox'][name='checkedres']").prop("checked",false);  //取消全选
        }
        var chsub = $("input[type='checkbox'][name='checkedres']").length; //获取subcheck的个数

        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数

        if (checkedsub == chsub) {
            $(".allcheck").prop("checked", true);
        }else{
            $(".allcheck").prop("checked", false);
        }
    });
    //单击单选框
    $("body").delegate(".checkedres","click",function () {
        if (!$(this).checked) {
            $(".allcheck").prop("checked", false);
        }

        //点击下面的可选框,下级的全选框全部选中或者全部不选
        if($(this).parent().next().length>0){
            var ids = $(this).parent().next().attr("id");
            if($(this).prop("checked")){
                $("#"+ids+" input[type='checkbox'][name='checkedres']").prop("checked",true);
            }else{
                $("#"+ids+" input[type='checkbox'][name='checkedres']").prop("checked",false);
            }
        }

        //下级的多选框各自选中后，上级复选框选中，反之不选中
        if($(this).parent().parent().parent().prev().length>0){
            var ids = $(this).parent().parent().parent().attr("id");//获取本身父元素的父元素的父元素的ID
            var faids = $(this).parent().parent().parent().prev().attr("id");//获取本身父元素的父元素的父元素的前一个同级元素的ID
            var chsub = $("#"+ids+" input[type='checkbox'][name='checkedres']").length;//获取下级复选框的个数
            var checkedsub = $("#"+ids+" input[type='checkbox'][name='checkedres']:checked").length;//获取选中的复选框的个数
            if (checkedsub == chsub) {
                $("#"+faids+" input[type='checkbox'][name='checkedres']").prop("checked",true);
            }else{
                $("#"+faids+" input[type='checkbox'][name='checkedres']").prop("checked", false);
            }
            //存在两级的话，继续判断，待改进
            var parent = $(this).parent().parent().parent().prev();
            if(parent.parent().parent().prev().length>0){
                var pids = parent.parent().parent().attr("id");
                var fapids = parent.parent().parent().prev().attr("id");
                var pchsub = $("#"+pids+" input[type='checkbox'][name='checkedres']").length;
                var pcheckedsub = $("#"+pids+" input[type='checkbox'][name='checkedres']:checked").length;
                if (pcheckedsub == pchsub) {
                    $("#"+fapids+" input[type='checkbox'][name='checkedres']").prop("checked",true);
                }else{
                    $("#"+fapids+" input[type='checkbox'][name='checkedres']").prop("checked", false);
                }
            }
        }

        var chsub = $("input[type='checkbox'][name='checkedres']").length; //获取subcheck的个数

        var checkedsub = $("input[type='checkbox'][name='checkedres']:checked").length; //获取选中的subcheck的个数

        if (checkedsub == chsub) {
            $(".allcheck").prop("checked", true);
        }else{
            $(".allcheck").prop("checked", false);
        }


    });

    //添加角色时，权限树展开全部
    $("body").delegate(".allopen",'click',function () {
        var action = $(this).attr("data-action");
        if($(".allopen").prop("checked")){
            if (action=="show")
            {
                $(".jstree-checked").show();
                $('.tubiao').attr('style','margin-left:-4px;color:black');
                $('.tubiao').attr('class','fa fa-caret-down tubiao');
                $('.tubiao').attr('data-action','down');
                $(this).attr("data-action","hide");
            }
        }else{
            if (action!="show"){
                $(".jstree-checked").hide();
                $('.tubiao').attr('style','margin-left:-2px;color:#bbb');
                $('.tubiao').attr('class','fa fa-caret-right tubiao');
                $('.tubiao').attr('data-action','right');
                $(this).attr("data-action","show");
            }
        }
    });

    $("body").delegate(".tubiao",'click',function () {
        var action = $(this).attr("data-action");
        if(action=='right'){
            $(this).parent().next().nextAll().show();
            $(this).attr('style','margin-left:-4px;color:black');
            $(this).attr('class','fa fa-caret-down tubiao');
            $(this).attr("data-action","down");
        }else{
            $(this).parent().next().nextAll().hide();
            $(this).attr('style','margin-left:-2px;color:#bbb');
            $(this).attr('class','fa fa-caret-right tubiao');
            $(this).attr("data-action","right");
        }

        // //当图标都关闭或者打开时，展开全部复选框随着变化
        // var is = $("i[name='tubiao']").length;//获取图标的个数
        // var chsub = $("i[data-action='right']").length; //获取关闭图标的个数
        // var checkedsub = $("i[data-action='down']").length; //获取打开的个数
        // if (chsub==is) {
        //     $(".allopen").prop("checked", false);
        // }else if(checkedsub==is){
        //     $(".allopen").prop("checked", true);
        // }

    });
    //角色组添加页面JS  end


    //管理员添加页面 头像上传预览 start
    $("body").delegate("#avatar",'change',function () {
        var reads = new FileReader();
        var f = document.getElementById("avatar").files[0];
        reads.readAsDataURL(f);
        reads.onload = function () {
            $(".background").attr("style","display:none");
            $('#imgs').attr("style","display:block");
            $('#img').attr("src" , this.result);
        };
    })
    //管理员添加页面 头像上传预览 end









})


