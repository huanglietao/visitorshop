<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="" autocomplete="off">
        @csrf
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">日志编号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969" style="margin-top: 7px"> 1907261</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">操作员：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969" style="margin-top: 7px">administrator</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">URL：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969" style="margin-top: 7px">order/list/download</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">

            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">操作IP：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-3 color-6A6969" style="margin-top: 7px">
                        113.67.159.54
                        <i class="fa fa-map-marker" style="color: red"></i>
                    </span>

                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">创建时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-7 color-6A6969" style="margin-top: 7px">2019-08-13 13:52</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">日志内容：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span style="margin-bottom: 5px;margin-top: 7px" class="col-sm-7 color-6A6969">操作员 [ admin ] 通过 common\models\OrderLog [ order_log ] 创建 序号 2215 记录: </span>
                    <span style="margin-bottom: 5px;margin-top: 2px" class="col-sm-7 color-6A6969">序号(id) => 2215,</span>
                    <span style="margin-bottom: 5px;margin-top: 2px" class="col-sm-7 color-6A6969">订单编号(order_id) => 1070,</span>
                    <span style="margin-bottom: 5px;margin-top: 2px" class="col-sm-7 color-6A6969">用户(user) => admin,</span>
                    <span style="margin-bottom: 5px;margin-top: 2px" class="col-sm-7 color-6A6969">操作(action) => 下载,</span>
                    <span style="margin-bottom: 5px;margin-top: 2px" class="col-sm-7 color-6A6969">创建时间(created_at) => 2019-06-26 13:32:12,</span>
                    <span style="margin-bottom: 5px;margin-top: 2px" class="col-sm-7 color-6A6969">Result(result) => 成功,</span>
                    <span style="margin-bottom: 5px;margin-top: 2px" class="col-sm-12 color-6A6969">备注(note) => 管理员【admin】下载订单【2030-05-29 10:28:5141148617】项目【2030-05-29 10:28:5141148617-1-1】生产文件</span>
                </div>
            </div>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="button" class="btn btn-write btn-reset close-detail">关闭</button>
        </div>
    </div>
</div>