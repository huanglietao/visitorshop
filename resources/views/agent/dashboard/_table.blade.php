<!-- table列表数据显示  -->
@foreach ($list as $k=>$v)
    <tr>
        <td>{{$v['username']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['email']}}</td>
		<td>开启</td>
		<td>2019-09-11</td>
		<td>2019-09-11</td>
<td>2019-09-11</td>
    </tr>
@endforeach