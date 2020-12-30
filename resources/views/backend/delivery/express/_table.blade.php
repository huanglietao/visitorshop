<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
        <td style="padding: 10px 0">
            <div style="display: inline-block">
                @if($v['express_logo'])<img style="width:150px;height: 50px" src="{{$v['express_logo']}}" >@endif
            </div>&nbsp;&nbsp;
            <div style="width:150px;text-align:left;display: inline-block">
                <span style="">{{$v['express_name']}}</span>
            </div>
        </td>
        <td>{{$v['express_code']}}</td>
        <td>{{$CommonPresenter->getExpressType($v['express_type'])}}</td>
        <td>{{$CommonPresenter->getEnabledOrDisabled($v['express_status'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td>{{$v['express_desc']}}</td>
        <td>
            <p style="margin:4px 0 ;padding:0">
                <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/delivery/express/form')}}?id={{$v['express_id']}}" data-title = "编辑">编辑</span>
                <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/delivery/express/del/'.$v['express_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            </p>
        </td>
    </tr>
@empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
