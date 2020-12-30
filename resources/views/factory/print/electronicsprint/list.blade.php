<h3>集货单</h3>
<table cellspacing="0" cellpadding="5" border="0" class="info_table" id="tborder_table">
    <tbody>
    <tr>
        <td class="header-row">订单编号</td>
        <td class="header-row">数量</td>
        <td class="header-row">集货状态</td>
    </tr>
    @foreach ($list['item'] as $k => $v)
        <tr>

            <td class="center-row">{{ $v['ord_prj_item_no'] }}</td>
            <td class="center-row">{{ $v['prod_num']}}</td>
            <td class="center-row">
                <font color="green">{{ $v['is_stocked']}}</font>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<h3>配送信息</h3>
<table cellspacing="0" cellpadding="5" border="0" class="info_table" id="tborder_table">
    <tbody>
    <tr>
        <td class="header-row">配送方式</td>
        <td class="center-row" style="color: red;">
            {{$company}}
        </td>
    </tr>
    <tr>
        <td class="header-row">收货地址</td>
        <td class="center-row">{{$list['prod_name']}}  {{$list['city_name']}} {{$list['dist_name']}}  {{$list['order_rcv_address']}}
        </td>
    </tr>
    <tr>
        <td class="header-row">收货人</td>
        <td class="center-row">{{$list['order_rcv_user']}}
        </td>
    </tr>
    <tr>
        <td class="header-row">联系电话</td>
        <td class="center-row">{{$list['order_rcv_phone']}}
        </td>
    </tr>
    <tr>
        <td class="header-row">邮编</td>
        <td class="center-row">
        </td>
    </tr>
    </tbody>
</table>
<h3>打单记录</h3>
<table cellspacing="0" cellpadding="5" border="0" class="info_table" id="tborder_table">
    <tbody>
    <tr>
        <td class="header-row">物流方式</td>
        <td class="header-row">单号</td>
        <td class="header-row">打印次数</td>
        <td class="header-row">打单时间</td>
        <td class="header-row">操作</td>
    </tr>
    @foreach ($plist as $p)
        <tr>

            <td class="center-row">{{ $p['company_name']}}</td>
            <td class="center-row">{{ $p['waybill_code'] }}</td>
            <td class="center-row">{{ $p['print_times'] }}</td>
            <td class="center-row">
                @php
                $c_time = date("Y-m-d H:i:s",$p['created_at'])
                @endphp
                {{$c_time}}
            </td>
            <td class="center-row"><input type="button" value="再打一单(旧单号)" onclick="doPrint({{$p['id']}},'{{$p['company']}}')"  ></td>
        </tr>
    @endforeach

    </tbody>
</table>