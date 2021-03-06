<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['cms_auth_rule_id']}}</td>
		<td>{!! $v['cms_auth_rule_title'] !!}</td>
		<td>{{$v['cms_auth_rule_name']}}</td>
		<td>{{$v['cms_auth_rule_weigh']}}</td>
		<td>
            <a href="javascript:;" data-flag="{{$v['cms_auth_rule_ismenu']}}" data-id="{{$v['cms_auth_rule_id']}}" class="btn-change addtabsit btn btn-xs @if($v['cms_auth_rule_ismenu']==ZERO)btn-default @else btn-info @endif" title="">{{$yn[$v['cms_auth_rule_ismenu']]}}</a>
        </td>
		<td>
            <span @if($v['cms_auth_rule_status']==ONE) style="color:green" @endif style="color:red" >
                {{$CommonPresenter->getEnabledOrDisabled($v['cms_auth_rule_status'])}}</span>
        </td>
		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/auth/rule/form')}}?id={{$v['cms_auth_rule_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/auth/rule/del/'.$v['cms_auth_rule_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=11>暂无记录</td>
    </tr>
@endforelse
<style>
    .btn-info {
        background-color: #3498db;
        border-color: #ddd;
        margin-left: 3% !important;
        height: 30px;
        padding: 3px 10px;
    }
</style>