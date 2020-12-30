<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['smtp_address']}}</td>
		<td>{{$v['smtp_port']}}</td>
		<td>{{$v['smtp_username']}}</td>
		<td>{{$v['sender']}}</td>
		<td>{{$CommonPresenter->getEmailConnectType($v['connecttype'])}}</td>
		<td>{{$CommonPresenter->getEmailScene($v['scene'])}}</td>
		<td>
			<p style="margin:4px 0 ;padding:0">
				<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/system/smtp/form')}}?id={{$v['smtp_id']}}" data-title = "编辑">编辑</span>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/system/smtp/del/'.$v['smtp_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
