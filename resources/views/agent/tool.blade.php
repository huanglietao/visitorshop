<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>客服小工具</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="renderer" content="webkit">
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">


    <script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
    <script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>


</head>

    <body>
        <div class="content">
            <input id="tb_order_no" class="c_input" placeholder="请输入订单号">
            <div class="btn_operater synchronization">二次同步</div>
            <div class="btn_operater revoke">撤销生产状态</div>
        </div>
    </body>

</html>

<script>
    var tb_order_no;

    //二次同步
    $(".synchronization").click(function () {
        //检查参数
        if(!checkParam()){
            return false;
        }

        //加载Loading
        var index = layer.msg('操作进行中......', {
            icon: 16
            ,shade: 0.01
        });

        $.ajax({
            type: 'POST',
            url: "/tool/synchronization",
            dataType: "json",
            data: {
                tb_order_no:tb_order_no,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(res) {
                layer.close(index);
                if(res['status'] == 200 && res['success']){
                    layer.msg(res['data'], {icon: 6});
                }else{
                    layer.msg(res['message'], {icon: 5});
                }
            },
            error:function(res){
                layer.close(index);
                layer.msg('出错了......', {icon: 5});
            }

        });


    })

    //撤销生产
    $(".revoke").click(function () {
        //检查参数
        if(!checkParam()){
            return false;
        }

        //加载Loading
        var index = layer.msg('正在撤销中......', {
            icon: 16
            ,shade: 0.01
        });

        $.ajax({
            type: 'POST',
            url: "/tool/revoke",
            dataType: "json",
            data: {
                order_no:tb_order_no,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function(res) {
                layer.close(index);
                if(res['status'] == 200 && res['success']){
                    layer.msg(res['data'], {icon: 6});
                }else{
                    layer.msg(res['message'], {icon: 5});
                }
            },
            error:function(res){
                layer.close(index);
                layer.msg('出错了......', {icon: 5});
            }

        });
    })

    function checkParam() {
        tb_order_no = $("#tb_order_no").val();
        if(tb_order_no == ''){
            layer.tips('这儿得输入订单号', '#tb_order_no', {
                tips: [1, '#3388FF']
            });
            return false;
        }else{
            return true;
        }

    }
</script>

<style>
    .content{
        display: flex;
        justify-content: center;
        margin-top: 100px;
    }

    .c_input{
        width: 500px;
        margin-right: 10px;
        padding-left: 10px;
    }

    .btn_operater{
        width: 100px;
        height: 40px;
        background-color: #3388FF;
        color: white;
        text-align: center;
        line-height: 40px;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 10px;
        font-size: 14px;
    }
</style>