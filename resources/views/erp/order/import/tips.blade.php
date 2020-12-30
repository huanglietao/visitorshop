@if ($data['num'] == 1)
    <div class="vmiddle">
        <div class="icon">
            <span>
               <img src="{{URL::asset('images/chilun.png')}}" alt="">
            </span>
        </div>
        <div  class="rich_text">
            <p style="line-height:0; margin-bottom:5px;">
                <span class="mb-text">数据导入提示</span>
            </p>
            <p style="line-height:0; margin-bottom:5px;">
                <span class="new-text">{{$data['filename']}}</span>
                <span class="warn-text">正在导入系统，请稍后...</span>
            </p>
            <p style="line-height:0; margin-bottom:5px;">
                <span class="warn-text" style="margin-top: 10px">
                    数据未导入成功，请勿关闭此页面！
                </span>
            </p>
        </div>
    </div>
    <div class="del-btn" style="">
    </div>
@elseif ($data['num'] == 2)
    <div class="vmiddle">
        <div class="icon">
        <span>
            <img src="{{URL::asset('images/border-success.png')}}" alt="">
        </span>
        </div>
        <div  class="rich_text">
            <p style="line-height:0; margin-bottom:5px;">
                <span class="mb-text">数据导入提示</span>
            </p>
            <p style="line-height:0; margin-bottom:5px;">
                <span class="new-text">{{$data['filename']}}</span>
                <span class="warn-text">导入成功！共导入{{$data['total']}}条记录。</span>
            </p>
            <p style="line-height:0; margin-bottom:5px;">
                <span class="warn-text" style="margin-top: 10px">
                    请核对导入数据是否正确，如有异议，请与客服联系！
                </span>
            </p>
        </div>
    </div>
    <div class="del-btn" style="">
        <div id="sure-comfirm" style="" data-url="/import">
                <span class="del-text">确定</span>
        </div>
        <div id="del-cancel" style="">
            <span class="del-text">取消</span>
        </div>
    </div>
@endif

<style>
    .vmiddle{
        width: 580px;
        height: 112px;
        margin-left: 10px;
        margin-top: 13px;
        z-index: 2;
        background-color: rgb(255, 255, 255);
        box-shadow: rgb(170, 170, 170) 0px 0px 5px 0px;
        border-radius: 5px 5px  0 0;
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
        z-index: 4;
        font-size: 14px;
        text-align: left;
        line-height: 0px;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        margin: auto 0 auto 42px;
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
        color:blue;
        font-style:normal;
        letter-spacing:0px;
        line-height:18px;
        text-decoration:none;
        font-weight:400;
        font-size:12px;
        margin-right: 10px;
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
    .warn-text{
        font-weight: 400;
        font-size: 12px;
        color: rgb(121, 119, 119);
        font-style: normal;
        letter-spacing: 0px;
        line-height: 18px;
        text-decoration: none;
    }
    .del-btn{
        width: 580px;
        height: 45px;
        margin-left: 10px;
        z-index: 7;
        background-color: rgb(240, 242, 245);
        border-radius: 0 0 5px 5px;
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
    #sure-comfirm{

        width: 80px;
        height: 28px;
        position: absolute;
        right: 100px;
        margin-top: 10px;
        color: rgb(255, 255, 255);
        background-color: rgb(229, 28, 35);
        border-radius: 4px;
        font-size: 12px;
        border-width: 0px;
        border-style: solid;
        text-align: center;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        line-height: 26px;
        margin-right: 10px;
        cursor: pointer;

    }
    #sure-comfirm:hover{
        background-color: rgb(236, 49, 56);
    }
    #del-cancel{
        position: absolute;
        right: 20px;
        width: 80px;
        height: 28px;
        margin-top: 10px;
        border-radius: 4px;
        font-size: 12px;
        border-width: 1px;
        border-style: solid;
        border-color: #91959A;
        text-align: center;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        background: #fff;
        line-height: 26px;
        cursor: pointer;
    }
    #del-cancel:hover{
        background-color: #eeeeee;
    }

</style>



