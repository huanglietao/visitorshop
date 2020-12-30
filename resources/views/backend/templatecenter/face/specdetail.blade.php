<link rel="stylesheet" href="{{ URL::asset('css/backend/template/tempmain.css') }}">


<style>
    table tr{height:30px}
    table tr td{width:100px;}

</style>
<div class="spec-table">
    @if(isset($rowtip))
        <div> {{$rowtip}}</div>
    @else
    <table  class="table table-striped">
        <tr>
            <td style="width:80px;font-weight:bold;" align="right">设计区尺寸</td>
            <td style="width:120px;">宽：{{$row['size_design_w']}}mm </td><td>高：{{$row['size_design_h']}}mm</td>
        </tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">设计区定位</td>
            <td style="width:120px;">上：{{$row['size_location_top']}}mm </td> <td>左：{{$row['size_location_left']}}mm</td>
        </tr>
        <tr>
            <td>&nbsp;</td><td style="width:80px;"> 下：{{$row['size_location_bottom']}}mm </td><td>右：{{$row['size_location_right']}}mm</td></tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">提示线</td>
            <td style="width:120px;">上：{{$row['size_tip_top']}}mm </td> <td>左：{{$row['size_tip_left']}}mm</td>
        </tr>
        <tr>
            <td>&nbsp;</td><td style="width:120px;"> 下：{{$row['size_tip_bottom']}}mm </td><td>右：{{$row['size_tip_right']}}mm</td></tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">出血位</td>
            <td style="width:120px;">上：{{$row['size_cut_top']}}mm </td> <td>左：{{$row['size_cut_left']}}mm</td>
        </tr>
        <tr>
            <td>&nbsp;</td><td style="width:120px;"> 下：{{$row['size_cut_bottom']}}mm </td><td>右：{{$row['size_cut_right']}}mm</td></tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">DPI</td>
            <td style="width:120px;">{{$row['size_info_dpi']}}</td><td></td>
        </tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">单/双页印刷</td>
            <td style="width:120px;">{{$isTurn[$row['size_is_2faced']]}}</td><td></td>
        </tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">能否合成</td>
            <td style="width:120px;">{{$yn[$row['size_is_output']]}}</td><td></td>
        </tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">能否编辑</td>
            <td style="width:120px;">{{$yn[$row['size_is_locked']]}}</td><td></td>
        </tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">能否显示</td>
            <td style="width:120px;">{{$yn[$row['size_is_display']]}}</td><td></td>
        </tr>
        <tr>
            <td style="width:80px;font-weight:bold" align="right">是否跨页</td>
            <td style="width:120px;">{{$isCross[$row['size_is_cross']]}}</td><td></td>
        </tr>
    </table>
    @endif
</div>