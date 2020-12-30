<link rel="stylesheet" href="{{URL::asset('css/agent/works/works.css')}}">
@inject('CommonPresenter','App\Presenters\CommonPresenter')
<!-- 作品标签form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/works/labelSave" onsubmit="return false;" autocomplete="off">
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
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['prj_outer_account']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">创建时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['created_at']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">货品编号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$project['prod_sku_sn']}}</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">作品标签：</label>
            <div class="col-xs-12 col-sm-10">
                <input  id="prj_label_list" hidden class="form-control col-sm-5" name="prj_label" type="text" value="{{$project['prj_label']}}"   placeholder="">
                @foreach($prjLabel as $k=>$v)
                    <div class="row check" style="margin-top: 7px">
                        @if(in_array($k,$project['prj_label_list'])==false)
                            @component('component/checkbox',['checkbox'=>[$v],'name'=>[$k],'custom_class'=>'checkedlabel'])
                            @endcomponent
                        @elseif(in_array($k,$project['prj_label_list'])==true)
                            @component('component/checkbox',['checkbox'=>[$v],'name'=>[$k],'custom_class'=>'checkedlabel','checked'=>'0'])
                            @endcomponent
                        @endif
                    </div>
                @endforeach
                <span class="msg-box" style="position:static;" for="prj_label_list"></span>
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

<style>
    .btn-checkbox + label:before{
        top:0px
    }
    .btn-checkbox + label:after{
        top:4px
    }
</style>

@section("pages-js")

@endsection


