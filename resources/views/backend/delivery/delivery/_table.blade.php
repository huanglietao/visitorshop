<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['delivery_name']}}</td>
		<td>{{$v['delivery_show_name']}}</td>
		<td>{{$v['delivery_express_list_name']}}</td>
		<td>{{$v['delivery_desc']}}</td>
        <td>{{$CommonPresenter->getYesOrNo($v['delivery_is_cash'])}}</td>
        <td>{{$CommonPresenter->getEnabledOrDisabled($v['delivery_status'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
            <p style="margin:4px 0 ;padding:0">
                <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/delivery/delivery/form')}}?id={{$v['delivery_id']}}" data-title = "编辑">编辑</span>
                <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/delivery/delivery/del/'.$v['delivery_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
