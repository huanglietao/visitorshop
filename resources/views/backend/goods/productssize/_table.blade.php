<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['size_name']}}</td>
		<td>{{$v['category_attach']['cate_name']}}</td>
		<td>{{$v['size_dpi']}}</td>
		<td>
            <span style="display: inline-block;text-align: left !important;">
                @foreach($v['design_size'] as $kk=>$vv)
                    {{$vv}}<br>
                @endforeach
            </span>

        </td>
        <td>{{$CommonPresenter->getEnabledOrDisabled($v['size_status'])??""}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p  style="margin:4px 0 ;padding:0">
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/goods/products_size/form')}}?id={{$v['size_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/goods/products_size/del')}}/{{$v['size_id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
