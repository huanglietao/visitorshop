<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/salary/worker/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['salary_worker_id']}}" name="salary_worker_id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 职位：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="salary_worker_position" name="salary_worker_position" class="form-control position" data-rule="职位:required">
                        <option value="0">-请选择-</option>
                        @foreach($positions['position'] as $k=>$v)
                            <option value={{$k}} @if($k == $row['salary_worker_position']) selected @endif >{{$v}}</option>
                        @endforeach
                    </select>
                </div>
                <span class="msg-box" style="position:static;" for="oms_adm_group_id"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 姓名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="salary_worker_name" class="form-control col-sm-7" name="salary_worker_name" type="text" value="{{$row['salary_worker_name']}}" placeholder="" data-rule="姓名:required">
                </div>
                <span class="msg-box" style="position:static;" for="salary_worker_name"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">职位系数：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input class="form-control col-sm-7" id="salary_worker_rate" type="text" value="{{$row['position']['rate']}}" readonly>
                </div>
            </div>
        </div>

        <div class="form-group row form-item" style="display: none">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">日计件工资：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input class="form-control col-sm-7" id="salary_worker_money" type="text" value="{{$row['position']['per_money']}}" readonly>
                </div>
            </div>
        </div>

        <input type="hidden" value="{{json_encode($positions)}}" class="positions">

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



