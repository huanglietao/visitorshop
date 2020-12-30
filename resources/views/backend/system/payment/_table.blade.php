<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td style="padding: 10px 0">
            <div style="display: inline-block">
                <img style="max-height: 75px" src="{{$v['pay_logo']}}" >
            </div>&nbsp;&nbsp;
            <div style="width:150px;text-align:left;display: inline-block">
                <span style="">{{$v['pay_name']}}</span>
            </div>
        </td>
		<td>{{$CommonPresenter->getEnabledOrDisabled($v['pay_status'])}}</td>
		<td>{{$v['pay_desc']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
            <p style="margin:4px 0 ;padding:0">
                <span id="edit" class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/system/payment/form')}}?id={{$v['pay_id']}}" data-title = "编辑">编辑</span>
                <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/system/payment/del/'.$v['pay_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=5>暂无记录</td>
    </tr>
@endforelse
