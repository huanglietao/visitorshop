<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr style="height: 80px">
		<td>
            @if($v['material_cate_flag']=='frame')
                <div style="position: relative;height: 80px">
                    <img src="{{$tpurl}}/{{$v['attach_path']}}" style="height:60px;position:absolute;top:10%;z-index:100"/>
                    <img src="{{$tpurl}}/{{$v['attach_paths']}}" style="height:60px;position:absolute;top:10%;z-index:10"/>
                </div>

            @else
                <img src="{{$tpurl}}/{{$v['attach_path']}}" style="height:60px;"/>
            @endif
        </td>
        <td>@if(isset($materFlag[$v['material_cate_flag']])){{$materFlag[$v['material_cate_flag']]}}@else - @endif</td>
        <td>@if(isset($materialCateList[$v['material_cateid']])){{$materialCateList[$v['material_cateid']]}} @else 无 @endif</td>
		<td>@if($v['material_use_type']==1)公共通用 @else设计师用@endif</td>
		<td>@if($v['specification_style']==0)- @else{{$specStyle[$v['specification_style']]}}@endif</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p style="margin:4px 0;">
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/template/material/form')}}?id={{$v['material_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/template/material/del/'.$v['material_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=16>暂无记录</td>
    </tr>
@endforelse
