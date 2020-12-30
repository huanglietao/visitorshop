<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/salary/detail/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['salary_calc_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 所属详情id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="salary_detail_id" class="form-control col-sm-5" name="salary_detail_id" type="text" value="{{$row['salary_detail_id']}}" placeholder="" data-rule="所属详情id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="salary_detail_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 姓名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="workers_name" class="form-control col-sm-5" name="workers_name" type="text" value="{{$row['workers_name']}}" placeholder="" data-rule="姓名:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="workers_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 职位,取config->salary中配置：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="salary_worker_position" class="form-control col-sm-5" name="salary_worker_position" type="text" value="{{$row['salary_worker_position']}}" placeholder="" data-rule="职位,取config->salary中配置:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="salary_worker_position"></span>
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

