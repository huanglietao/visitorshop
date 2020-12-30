<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
        <td>{{$v['sup_name']}}</td>
        <td>{{$v['sup_code']}}</td>
        <td>{{$v['sup_contacts']}}</td>
        <td>{{$v['sup_telephone']}}</td>
        <td>{{$sup_region[$v['sup_region']]}}</td>
        <td>
            @if($v['sup_type']=='1')主力
            @elseif($v['sup_type']=='2')备选
            @endif
        </td>
        <td>{{$v['sup_capacity']}}</td>
        <td>{{$v['sup_allocation_quantity']}}</td>
        <td>{{$v['sup_capacity_unit']}}</td>
        <td>{{$CommonPresenter->getEnabledOrDisabled($v['sup_status'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
            <p>
                @if($v['is_create_scm']==0)
                    <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/suppliers/suppliers/account')}}?id={{$v['sup_id']}}" data-title = "创建账号">创建账号</span>
                @else
                    <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/suppliers/suppliers/account')}}?id={{$v['sup_id']}}" data-title = "账号编辑">账号编辑</span>
                @endif
                    <span id="btn_area" class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/suppliers/suppliers/form')}}?id={{$v['sup_id']}}" data-title = "编辑">编辑</span>
                <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/suppliers/suppliers/del/'.$v['sup_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=12>暂无记录</td>
    </tr>
@endforelse
