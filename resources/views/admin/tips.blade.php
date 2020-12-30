<input type="button" value="成功提示" class="btn-succ" />

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn-sure">确定</button>
            <button type="reset" class="btn-reset">重置</button>
        </div>
    </div>

<style>
    .vmiddle{
        width: 580px;
        height: 112px;
        margin-left: 10px;
        margin-top: 9px;
        z-index: 2;
        background-color: rgb(255, 255, 255);
        box-shadow: rgb(170, 170, 170) 0px 0px 5px 0px;
        border-radius: 5px;
        font-size: 14px;
        padding: 0px;
        border-width: 0px;
        border-style: solid;
        text-align: center;
        line-height: 20px;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        display: flex;
    }
    .icon{
        width: 60px;
        height: 60px;
        margin-left: 54px;
        padding-top: 25px;
        z-index: 3;
        color: rgb(37, 155, 36);
        border-width: 0px;
        border-style: solid;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
    }
    .rich_text{
        margin-left: 42px;
        margin-top: 26px;
        z-index: 4;
        font-size: 14px;
        text-align: left;
        line-height: 0px;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
    }
    .mb-text{
        font-weight:700;
        font-size:16px;
        color:rgb(16, 16, 16);
        font-style:normal;
        letter-spacing:0px;
        line-height:24px;
        text-decoration:none;
    }
    .new-text{
        color:rgba(0, 0, 0, 0.88);
        font-style:normal;
        letter-spacing:0px;
        line-height:18px;
        text-decoration:none;
        font-weight:400;
        font-size:12px;
    }
    .user-text{
        font-weight:700;
        font-size:12px;
        color:rgb(63, 81, 181);
        font-style:normal;
        letter-spacing:0px;
        line-height:18px;
        text-decoration:none;
    }
    .icon img  {
        width: 100%;
    }
</style>
<script>
    $(".btn-succ").click(function () {
        tip_success();
    })
</script>
