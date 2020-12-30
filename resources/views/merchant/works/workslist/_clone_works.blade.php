<link rel="stylesheet" href="{{URL::asset('css/agent/works/works.css')}}">
@inject('CommonPresenter','App\Presenters\CommonPresenter')
<!-- 克隆作品form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/works/clone" onsubmit="return false;" autocomplete="off">
    <input hidden name="prj_id" value="{{$project['prj_id']}}"/>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">作品名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['prj_name']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">作品编号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['prj_sn']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">作者：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['prj_temp']['prj_outer_account']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">创建时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$CommonPresenter->exchangeTime($project['created_at'])}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">货品编号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['prod_sku']['prod_sku_sn']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">P数：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['prj_page_num']}}p</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-clone" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 克隆数量：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="clone" class="form-control col-sm-5" name="clone" type="text" value="1" placeholder="作品名称" data-rule="克隆数量:required">
                    <span class="col-sm-7 color-6A6969"> 数量只能为正整数。上限数量50份，作品名称为“当前作品名_1，2，3...”</span>
                </div>
                <span class="msg-box" style="position:static;" for="username"></span>
            </div>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>



@section("pages-js")
    $(".btn-reset").click(function () {
    alert(213213)
    $(".input_textbox").val();
    })
    <script>
        $(".btn-succ").click(function () {
            tip_success();
        })
        $(".btn-reset").click(function () {
            alert(213213)
            $(".input_textbox").val();
        })
    </script>
@endsection


