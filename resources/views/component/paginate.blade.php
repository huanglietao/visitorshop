<!-- 分页组件  -->
<div class="paginate">
    <span class="page-record">
        总共 <span id="list-total"></span> 条记录，分<span id="limit_pages"> </span> 页
    </span>
    &nbsp;&nbsp;
    <span class="page-act">
        <span>
            每页
        </span>
        &nbsp;
        <select id="pages-limit"  style="">
            @if(isset($pagesList))
               @foreach($pagesList as $k=>$v)
                    <option @if($limit == $v) selected  @endif value="{{$v}}">{!! $v !!}</option>
                @endforeach
            @else
                <option @if($limit == 10) selected  @endif value="10">10</option>
                <option @if($limit == 20) selected  @endif  value="20">20</option>
                <option @if($limit == 50) selected  @endif  value="50">50</option>
                <option @if($limit == 100) selected  @endif  value="100">100</option>
            @endif
        </select>
        &nbsp;&nbsp; &nbsp;&nbsp;
        <i class="fa fa-step-backward pages pages-first" data-flag="first" style="font-size:12px" title="首页"></i>
        &nbsp;&nbsp;&nbsp;
        <i class="fa fa-chevron-left pages pages-prev" data-flag="prev"  style="font-size:12px" title="上一页"></i>
        &nbsp;
        <select id="pages-item"  style="">
            <option selected="selected">第1页</option>
            <option>第2页</option>

        </select>
        &nbsp;
        <i class="fa fa-chevron-right pages pages-next" data-flag="next" style="font-size:12px" title="下一页"></i>
        &nbsp;&nbsp;&nbsp;
        <i class="fa fa-step-forward pages pages-last" data-flag="last" style="font-size:12px" title="末页"></i>

        <input type="hidden" name="total_pages" id="total_pages" value="0">
    </span>
</div>
