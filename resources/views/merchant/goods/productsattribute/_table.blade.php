<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['attr_name']}}</td>
		<td>{{$v['category_attach']['cate_name']}}</td>
		<td>
			@php $attrValueCount = count($v['attribute_value_attach']) @endphp
			@for($i = 0;$i<$attrValueCount;$i++)
				@if($i%12==0 && $i!=0)
					<font class='btn-info btn-xs btn_attr_value'>{{$v['attribute_value_attach'][$i]['attr_val_name']}}</font>&nbsp;&nbsp;<br>
				@else
					<font class='btn-info btn-xs btn_attr_value'>{{$v['attribute_value_attach'][$i]['attr_val_name']}}</font>&nbsp;&nbsp;
				@endif
			@endfor
		</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p style="margin:4px 0 ;padding:0">
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/goods/products_attribute/form')}}?id={{$v['attr_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/goods/products_attribute/del')}}/{{$v['attr_id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
