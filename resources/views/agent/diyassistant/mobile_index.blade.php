<!DOCTYPE html>
<html>
<head>
    <title>diy在线制作助手</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <meta name="data-spm" content="a1zaa" />
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-control" content="no-cache" />
    <meta http-equiv="Cache" content="no-cache" />
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body style="padding: 0;margin: 0;">
<link rel="stylesheet" href="{{URL::asset('css/agent/mobile_diy_assistant.css')}}">
<link rel="stylesheet" href="{{ URL::asset('assets/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{URL::asset('assets/bootstrap/css/bootstrap.min.css')}}" />

<nav role="navigation" style="height: 50px;background-color: black;color: white">
        <div class="container">
            <div  style="line-height: 50px;font-size: 18px">
                <span  style="padding:0">{{$shop_name}}</span>
            </div>
        </div>

</nav>
<div class="container">
        <div class="c-main" >
           <div class="cm-title">
                <h2>
                    diy在线制作助手
                </h2>
               <div class="cm-search">
                   <input type="text" class="cm-search-input" placeholder="请输入淘宝/天猫订单号" value="{{$order_no}}">
                   <span class="cm-search-confirm">确&nbsp;定</span>
               </div>
           </div>

            <div id="table">
                <table class="no-border-table">
                    <input type="hidden" class="agent_id" value="{{$agent_id}}">
                    <input type="hidden" class="order_no" value="{{$order_no}}">
                    <thead>

                    </thead>
                    <tbody class="tbl-content">
                    <tr style="text-align: center;">
                        <td colspan=20>暂无记录</td>
                    </tr>
                    </tbody>
                </table>




        </div>
    </div>
</div>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('js/agent/diy_assistant.js')}}"></script>
<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script type="text/javascript">

</script>
</body>
</html>