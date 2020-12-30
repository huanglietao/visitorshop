<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="" onsubmit="return false;" autocomplete="off">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                 所属系统：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" disabled value="{{$row[0]['sys']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                所属模块：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" disabled value="{{$row[0]['modules']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                操作路由：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" disabled value="{{$row[0]['router']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                操作人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" disabled value="{{$row[0]['operator_name']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                操作时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" disabled value=" {{$row[0]['add_time_val']}}" placeholder="">
                </div>
            </div>
        </div>

    </form>

</div>

