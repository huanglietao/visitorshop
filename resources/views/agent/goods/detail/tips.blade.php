
    <div class="vmiddle">
        <div  class="rich_text">
            <p style="line-height:0; margin-bottom:5px;">
                <span class="mb-text">制作链接</span>
            </p>
            <p style="line-height:0; padding: 10px 0;margin-bottom: 0">
                <span>链接地址:</span>
            </p>
            <p style="line-height:0;margin-bottom: 0">
                <input type="text" style="width:100%;height: 35px;border: 0.5px solid lightgray;background-color: #eee"  value="{{$data['url']}}"/>
            </p>
        </div>
    </div>
    <div class="del-btn" style="">
        <div id="del-cancel" style="">
            <span class="del-text">关闭</span>
        </div>
    </div>


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
        padding: 20px 40px;
        border-width: 0px;
        border-style: solid;
        text-align: center;
        line-height: 20px;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        display: flex;
    }

    .rich_text{
        width:100%;
        z-index: 4;
        font-size: 14px;
        text-align: left;
        line-height: 0px;
        font-weight: normal;
        font-style: normal;
        opacity: 1;
        margin: auto 0;
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



