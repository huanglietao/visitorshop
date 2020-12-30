<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)

    <tr>
		<td><img src="{{$v['thumb']}}" style="height: 60px"/></td>
		<td>{{$v['temp_name']}}</td>
		<td>{{$v['cate_name']}} </td>
		<td>@if(isset($sizeList[$v['spec']])){{$sizeList[$v['spec']]}} @else 无 @endif</td>
		<td>
		<p style="margin:4px 0;">
			<span class="oa_operate op_tbl btn-dialog" data-area="['50%', '50%']" data-url="{{URL::asset('/templatecenter/commercialtemp/form')}}?id={{$v['tid']}}&cid={{$v['cid']}}" data-title = "绑定规格">绑定规格</span>
		</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=15>暂无记录</td>
    </tr>
@endforelse
<style>
	.btn-info {background-color: #3498db;
		border-color: #ddd;
		margin-left: 3% !important;
		height: 30px;
	}
	 td{ padding: 8px;}
</style>