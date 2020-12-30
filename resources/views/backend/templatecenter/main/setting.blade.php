

<style>
    .item{display:inline-block;padding-bottom:50px}
    #left {width:25%;background:#e4e4e4}
    #right {width:70%}
    #content{margin-top:10px;}

    #left ul{list-style: none;padding:0;display: flex;display: -webkit-flex;flex-wrap: wrap;}
    #left ul li {margin-left:20px;width:35%;margin-top:10px}
    #main1 {display: flex;display: -webkit-flex;flex-wrap: wrap;}
    .temp-list{width:25%;text-align: center;cursor:pointer;box-sizing: border-box;}
    .fy{cursor: pointer;color:grey}
    .cate-active{font-weight:bold}
    .img-active{border:4px solid #3498db;}
    .cate-item{cursor:pointer}
</style>
<div id="content" style="padding: 15px" class="">
    <input type="hidden" id="page-type" value="{{$page_type}}">
    <input type="hidden" id="goodsType" value="{{$goodsType}}">
    <div id="left" class="item" style="min-height:450px">
        <p style="font-weight:bold;text-align: center;margin-top:20px;margin-bottom:5px">分类</p>
        <div style="height:1px;border-top:2px solid #cfcfcf;width:90%;margin-left:5%">  </div>
        <ul>
            <li data-id="" class="cate-item cate-active">全部</li>
            @foreach($tempCate as $k=>$v)
                <li data-id="{{$k}}" class="cate-item">{!! $v !!}</li>
            @endforeach
        </ul>

        @if($page_type == 1)
        <p style="font-weight:bold;text-align: center;margin-top:30px;margin-bottom:5px">规格</p>
        <div style="height:1px;border-top:2px solid #cfcfcf;width:90%;margin-left:5%">  </div>
        <select id="spec"  class="form-control" style="width:86%;margin-left:7%;margin-top:10px">
            <option value="">全部</option>
            @foreach($goodsSpec as $k=>$v)
                <option value={{$k}} @if($k == $specId) selected @endif >{!! $v !!}</option>
            @endforeach

        </select>
        @endif

        @if($page_type == 2)
        <p style="font-weight:bold;text-align: center;margin-top:30px;margin-bottom:5px">内页标签</p>
        <div style="height:1px;border-top:2px solid #cfcfcf;width:90%;margin-left:5%">  </div>
        <select id="inner_type"  class="form-control" style="width:86%;margin-left:7%;margin-top:10px">
            <option value="0">全部</option>
            @foreach($sizeType as $k=>$v)
                <option value={{$k}} @if($k == $size) selected @endif >{!! $v !!}</option>
            @endforeach
        </select>
        @endif
        <p style="font-weight:bold;text-align: center;margin-top:30px;margin-bottom:5px">查询</p>
        <div style="height:1px;border-top:2px solid #cfcfcf;width:90%;margin-left:5%">  </div>

        <select id="search-item"  class="form-control" style="width:86%;margin-left:7%;margin-top:10px">
            <option value="1">模板名称</option>
        </select>

        <input id="search-value" type="text" class="form-control" style="width:86%;margin-left:7%;margin-top:10px">

    </div>
    <div id="right" class="item" style="vertical-align:top">
        <div id="loading"  style="display: none;text-align: center">
            <img style="width:10%;" src="/assets/layer/src/theme/default/loading-0.gif">
        </div>
        <div id="main1">
            @foreach($list['list'] as $k=>$v)
                @if($page_type==1)
                    <div class="temp-list" data-id="{{$v['cover_temp_id']}}" data-name="{{$v['cover_temp_name']}}">
                        <img src="{{$v['cover_temp_thumb']}}" style="width:110px;">
                        <p style="text-align: center">{{$v['cover_temp_name']}}</p>
                    </div>
                @else
                    <div class="temp-list" data-id="{{$v['inner_temp_id']}}" data-name="{{$v['inner_temp_name']}}">
                        <img src="{{$v['inner_temp_thumb']}}" style="width:110px;">
                        <p style="text-align: center">{{$v['inner_temp_name']}}</p>
                    </div>
                @endif

            @endforeach
        </div>

        @if($list['limit'] < $list['total'])
        <div style="text-align: center;margin-top:10px" id="total_fy">
            <span class="page-record" style="margin-right:20px">总共<span id="list-total">{{$list['total']}}</span>条</span>
            <span  id="prev" class="fy" style="cursor: not-allowed">上一页</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <span id="next" class="fy" data-page="{{$page+1}}">下一页</span>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        @endif
    </div>
</div>

<div class="form-group layer-footer">
    <label class="control-label col-xs-12 col-sm-2"></label>
    <div class="col-xs-12 col-sm-8">
        <button id="setting" type="button" class="btn btn-success ">确定</button>
        <button type="reset" class="btn btn-default btn-embossed">重置</button>
    </div>
</div>

@section("js-file")
    <script src="{{ URL::asset('js/backend/tempcenter/setting.js')}}"></script>
@endsection