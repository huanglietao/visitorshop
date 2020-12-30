<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
        <td>{{$CommonPresenter->exchangeTime($v['finish_time'])}}</td>
		<td>{{$v['workers_name']}}</td>
		<td>{{$v['position']['name']}}</td>
        <td>{{$v['position']['rate']}}</td>
		<td>{{$shift[$v['shift']]}}</td>
		<td>{{$v['output_totals']}}</td>
		<td>{{$v['univalence']}}</td>
		<td>{{$v['salary']}}</td>

		{{--<td>--}}
            {{--<p >--}}
                {{--<span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/salary/detail/form')}}?id={{$v['salary_calc_id']}}" data-title = "编辑">编辑</span>--}}
                {{--<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/salary/detail/del')}}?id={{$v['salary_calc_id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>--}}
            {{--</p>--}}
		{{--</td>--}}
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
