
<div style="width: 100%;height: auto">
    <div style="text-align: center;margin: 20px 0;font-size: 16px"> 作品操作日志</div>
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    @if(!$log_info)
        <div style="text-align: center;margin-top: 20px;color: #797777;"> 暂无操作日志</div>
    @else
        <div class="log">
            @foreach($log_info as $k=>$v)
                <div class="row">
                    <div class="col-lg-3 col-md-4 log_content">
                        <div class="log_content_first"></div>
                    </div>
                    <div class="col-lg-8 col-md-8 log_contents">
                        <div class="log_contents_first">
                            <div class="col-lg-3 col-md-3 log_contents_first_first">
                                {{$CommonPresenter->exchangeTime($v['createtime'])}}
                            </div>
                            <div class="log_contents_first_two">
                                【@if($v['operator']!=""){{$v['operator']}}@else 客户@endif】{{$v['action']}}
                                @if($v['note']!=""){{$v['note']}}@else @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$loop->last)
                    <div class="row">
                        <div class="col-lg-3 col-md-4 log_line">
                            <div class="log_line_first"></div>
                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    @endif
</div>

<div class="form-group layer-footer">
    <label class="control-label col-xs-12 col-sm-2"></label>
    <div class="col-xs-12 col-sm-8">
        <button class="btn-sure btn-3F51B5" id="del-cancel">确定</button>
    </div>
</div>

<style>
    .log_content{
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .log_content_first{
        width: 10px;
        height: 10px;
        border: 1px solid #FF6A01;
        border-radius: 50%;
        float: right;
    }
    .log_contents{
        color: #797777;
    }
    .log_contents_first{
        display: flex;
    }

    .log_contents_first_two{
        text-align: left;

    }
    .log_line{
        padding-right: 12px;
    }
    .log_line_first{
        width: 10px;
        height: 15px;
        border-right: 1px dashed #91959A;
        float: right;
    }
    .log{
        margin-bottom: 40px;
    }
    .icon img  {
        width: 100%;
    }
</style>
@section("pages-js")
@endsection
