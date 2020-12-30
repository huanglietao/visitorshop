<!-- table列表数据显示  -->
@foreach ($list as $k=>$v)
    <tr>
        <td>{{$v['username']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['email']}}</td>
		@inject("CommonPresenter",'App\Presenters\CommonPresenter');
		<td>{{$CommonPresenter->getNormalOrHidden($v['status'])}}</td>
		<td>{{$v['createtime']}}</td>
		<td>{{$v['updatetime']}}</td>

		<td>
			<p >
				<span class="oa_operate op_tbl">编辑</span>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/admin/del/123')}}" data-title="删除记录" data-text="您确定要删除administrtor管理员账号吗?" style="">删除</span>
			</p>
		</td>
    </tr>
@endforeach