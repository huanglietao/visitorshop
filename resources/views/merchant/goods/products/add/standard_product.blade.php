

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
    .cate-active{color: #FF6A01}
    .img-active{border:3px solid #FF6A01;padding: 0 15px;}
    .cate-item{cursor:pointer}
    .main-temp-list{}


</style>
<div id="content" style="padding: 15px" class="">

    <div id="left" class="item" style="min-height:500px">
        <p style="font-weight:bold;text-align: center;margin-top:20px;margin-bottom:5px">分类</p>
        <div style="height:1px;border-top:2px solid #cfcfcf;width:90%;margin-left:5%">  </div>
        <ul>
            <li data-id="all" class="cate-item cate-active">全部</li>
            @foreach($categoryList as $k=>$v)
                <li data-id="{{$k}}" class="cate-item">{!! $v !!}</li>
            @endforeach
        </ul>


            <p style="font-weight:bold;text-align: center;margin-top:30px;margin-bottom:5px">规格</p>
            <div style="height:1px;border-top:2px solid #cfcfcf;width:90%;margin-left:5%">  </div>
            <select id="spec"  class="form-control" style="width:86%;margin-left:7%;margin-top:10px">
                <option value="all">全部</option>
                @foreach($productSizeList as $k=>$v)
                    <option value={{$k}}>{!! $v !!}</option>
                @endforeach
                {{-- <option value="">全部</option>--}}
            </select>
        <p  style="font-weight:bold;text-align: center;margin-top:30px;margin-bottom:5px">查询</p>
        <div style="height:1px;border-top:2px solid #cfcfcf;width:90%;margin-left:5%">  </div>



        <div style="display: flex;">
            <input id="search-value" type="text" class="form-control" placeholder="请输入商品名称" style="width:60%;margin-left:7%;margin-top:10px">
            <button id="search-value-search" class="btn btn-primary btn-3F51B5 btn-sm" style="height: 28px; margin-left: 7%;margin-top: 10px;">搜索</button>
        </div>


    </div>
    <div id="right" class="item" style="vertical-align:top">
            <div class="item-loading" style="text-align: center;margin-top:10px;display: none"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>
        <div id="main1">
            @foreach($productList['data'] as $k=>$v)

                    <div class="temp-list" data-id="{{$v['prod_id']}}" data-name="{{$v['prod_name']}}">
                        <div class="main-temp-list" style="display: inline-block;padding-top: 20px;width: 100%;overflow: hidden">
                        <img src="{{$v['prod_main_thumb']}}" style="width:110px;">
                        <p style="text-align: center">{{$v['prod_name']}}</p>
                        </div>
                    </div>
            @endforeach
        </div>
        @if($limit < $productList['total'])
            <div style="text-align: center;margin-top:10px;" >
                <span  id="prev" class="fy" style="cursor: not-allowed;">上一页</span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span id="next" class="fy"  data-page="2">下一页</span>&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
        @endif
    </div>
</div>
</div>

<div class="form-group layer-footer">
    <label class="control-label col-xs-12 col-sm-2"></label>
    <div class="col-xs-12 col-sm-8">
        <button id="setting" type="button" class="btn btn-primary btn-3F51B5 btn-sure">添加</button>
    </div>
</div>

