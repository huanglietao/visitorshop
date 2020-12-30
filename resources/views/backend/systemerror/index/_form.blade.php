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
                错误编码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" disabled value="{{$row[0]['code']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                错误提示：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" disabled value="{{$row[0]['message']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                错误代码处：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-5"  disabled rows="10" style="background: #e9ecef;">
                        {{$row[0]['line']}}
                    </textarea>
                </div>
            </div>
        </div>

    </form>

</div>

