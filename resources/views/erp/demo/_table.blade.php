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
		<td>2019-09-11</td>
    </tr>
@endforeach