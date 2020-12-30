<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td>{{$v['pid']}}</td>
		<td>{{$v['name']}}</td>
		<td>{{$v['create_at']}}</td>
        @inject("CommonPresenter",'App\Presenters\CommonPresenter');
        <td>{{$CommonPresenter->getNormalOrHidden($v['status'])}}</td>
		<td>
		<p>
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/auth/group/form')}}?id={{$v['id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/auth/group/del')}}?id={{$v['id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=6>暂无记录</td>
    </tr>
@endforelse
