<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['area_name']}}</td>
		<td  id="area-level">{{$v['level']}}</td>
		<td>{{$v['area_code']}}</td>
		<td>{{$pidList[$v['pid']]}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td>
            <p style="margin-bottom: 0px">
                <span class="oa_operate op_tbl show-areabtn"  data-val="{{$v['area_id']}}" data-title = "展开下级" data-pid="{{$v['pid']}}">管理</span>
            </p>
        </td>
		{{--<td>
		<p style="margin-bottom: 0px">

            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/areaseting/form')}}?id={{$v['id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/areaseting/del')}}?id={{$v['id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>--}}
    </tr>
    @empty
    <tr>
        <td colspan=10>暂无记录</td>
    </tr>
@endforelse
