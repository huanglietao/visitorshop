
$(function(){
    'use strict';

    // 点击切换同步队列状态
    $("body").delegate('.sync_status','change',function () {
        var id = $(this).attr('data-id');
        var status = $(this).val();
        $.ajax({
            url : '/queue/ordersyncqueue/changQueueStatus',
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
                console.log(data)
                if(data.success=='true'){
                    layer.msg("操作成功");
                }else {
                    layer.msg(data.message);
                }

            },
            error : function(){
                layer.msg("请求操作失败");
            }
        });

    });

    // 点击切换合成队列状态
    $("body").delegate('.comp_queue_status','change',function () {
        var id = $(this).attr('data-id');
        var status = $(this).val();
        $.ajax({
            url : '/queue/compoundqueue/changQueueStatus',
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
                console.log(data)
                if(data.success=='true'){
                    layer.msg("操作成功");
                }else {
                    layer.msg(data.message);
                }

            },
            error : function(){
                layer.msg("请求操作失败");
            }
        });

    });

    // 点击切换生产队列状态
    $("body").delegate('.produce_queue_status','change',function () {
        var id = $(this).attr('data-id');
        var status = $(this).val();
        $.ajax({
            url : '/queue/opqueue/changQueueStatus',
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
                console.log(data)
                if(data.success=='true'){
                    layer.msg("操作成功");
                }else {
                    layer.msg(data.message);
                }

            },
            error : function(){
                layer.msg("请求操作失败");
            }
        });

    });

    // 点击切换推送erp队列状态
    $("body").delegate('.order_push_status','change',function () {
        var id = $(this).attr('data-id');
        var status = $(this).val();
        $.ajax({
            url : '/queue/erppushqueue/changQueueStatus',
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
                console.log(data)
                if(data.success=='true'){
                    layer.msg("操作成功");
                }else {
                    layer.msg(data.message);
                }

            },
            error : function(){
                layer.msg("请求操作失败");
            }
        });

    });

    // 点击切换物流回写队列状态
    $("body").delegate('.delivery_push_status','change',function () {
        var id = $(this).attr('data-id');
        var status = $(this).val();
        $.ajax({
            url : '/queue/deliveryqueue/changQueueStatus',
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
                console.log(data)
                if(data.success=='true'){
                    layer.msg("操作成功");
                }else {
                    layer.msg(data.message);
                }

            },
            error : function(){
                layer.msg("请求操作失败");
            }
        });

    });



});
