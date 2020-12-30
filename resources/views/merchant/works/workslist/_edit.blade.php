<link rel="stylesheet" href="{{URL::asset('css/agent/works/works.css')}}">
@inject('CommonPresenter','App\Presenters\CommonPresenter')
<!-- 修改作品form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/works/workslist/save" onsubmit="return false;" autocomplete="off">
        <input hidden name="prj_id" value="{{$project['prj_id']}}"/>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-worksname" class="control-label col-xs-12 col-sm-2">作品名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="worksname" class="form-control col-sm-5" name="prj_name" type="text" value="{{$project['prj_name']}}" placeholder="作品名称" data-rule="作品名称:required">
                </div>
                <span class="msg-box" style="position:static;" for="username"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control col-sm-5"  name="prj_status">
                        @foreach($statusList as $k=>$v)
                            <option value="{{$k}}" @if($project['prj_status']==$k) selected @endif>{{$v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">作者名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  class="form-control col-sm-5" name="prj_rcv_user" id="nickname" type="text" value="{{$project['prj_temp']['prj_outer_account']}}" placeholder="">
                </div>
                <span class="msg-box" style="position:static;" for="nickname"></span>
            </div>
        </div>

        <div class="form-group row form-item">

            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">联系电话：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="email" class="form-control col-sm-5" name="prj_rcv_phone" type="text" value="{{$project['prj_temp']['prj_rcv_phone']}}" placeholder="" >
                </div>
                <span class="msg-box" style="position:static;" for="email"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">关联单号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="order_no" type="text" value="{{$project['prj_temp']['order_no']}}" placeholder="">
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
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">创建时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969 label-content">{{$CommonPresenter->exchangeTime($project['created_at'])}}</span>
                </div>
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



