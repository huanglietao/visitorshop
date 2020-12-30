<!DOCTYPE html>
<!-- saved from url=(0327)http://123.232.42.138:8090/chos/makeReport/towBarCode?orgCode=25000000&zsbh=20000075523&nsukey=XHN%2F1sH%2FMRFOg%2FoefWWj%2BDO6pm9ODbjagCPkTA79m7JG%2FLhcHcqyRz6FVUlARBDhgK3BUeX9eUuDDZwwQmxUV8bsmeC3XDj7JJK68wAxoGe1xtTETOtPsy2TBrFbic2gZ8gF%2FJseLrvXdGekM2D0bfTuUnrrRNrhFBpAkoS2R2X2hlQQssKcvhXBXTYsUQIHjEPLLQzn5op9jWe7YikveQ%3D%3D -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><script>
        var jypath = '/chos';
    </script>
    <script src= "{{ URL::asset('assets/towBarCode_files/richTexts.js.下载')}}"></script>
    <script src= "{{ URL::asset('assets/towBarCode_files/util.js.下载')}}"></script>
    <link type="text/css" rel="stylesheet" href="{{ URL::asset('assets/towBarCode_files/global.css')}}">

    <style>
        div{
            width:96%;
            padding:0 2%;
            height:85px;
            border-bottom:1px solid #999;
        }
        p{
            line-height:85px!important;
            /*font-size:37px!important;*/
            font-size:32px;
            margin:0;
            padding:0;
        }
        p span,
        p span p{
            display:inline-block;
            width:70%;
        }
        p span p font,
        p span p font font,
        p span font font font{
            font-size:32px;
        }
        font{
            margin-right:5%;
        }
        #ggxh font{
            margin-right:0px;
        }
        .overflowSty{
            white-space: nowrap;
            overflow-x: scroll;
            display:inline-block;
            height:80px;
        }
        .addAlignVertical{
            vertical-align: text-bottom;
        }
        .fixedWidth{width:600px;}
    </style>
</head>
<body>
<div style="display:none;" id="Dclfw">{"richText":[{"style":{"font":"13px Times New Roman","foreColor":"rgb(0, 0, 0)","textDecoration":0},"text":"/"}]}</div>
<div style="display:none;" id="Djltx">{"richText":[{"style":{"font":"13px Times New Roman","foreColor":"rgb(0, 0, 0)","textDecoration":0},"text":"/"}]}</div>
<div style="display:none;" id="Dggxh">{"richText":[{"style":{"font":"13px Times New Roman","foreColor":"rgb(0, 0, 0)","textDecoration":0},"text":"GC6890"}]}</div>
<div style="display:none;" id="Dypmc">气相色谱仪</div>
<div style="display:none;" id="jcjg1">/</div>
<div><p><font color="green" class="addAlignVertical">委托单位:</font> <span class="overflowSty">济南兰光机电技术有限公司</span></p></div>
<div><p><font color="green" class="addAlignVertical">单位地址:</font> <span class="overflowSty">济南市</span></p></div>
<div><p><font color="green" class="addAlignVertical">证书单位:</font> <span class="overflowSty">天津长荣健康科技有限公司</span></p></div>
<div><p><font color="green">联&nbsp;&nbsp;系&nbsp;&nbsp;人:&nbsp;</font>孙</p></div>
<div><p><font color="green">联系电话:</font> 186 2216 1613</p></div>
<div><p><font color="green" class="addAlignVertical">样品名称:</font> <span id="ypmc" class="overflowSty"></span></p></div>
<div><p><font color="green" class="addAlignVertical">条&nbsp;&nbsp;形&nbsp;&nbsp;码:</font><span class="overflowSty fixedWidth">200002605681</span></p></div>
<div><p><font color="green" class="addAlignVertical">规格型号:</font> <span id="ggxh" class="overflowSty"></span></p></div>
<div><p><font color="green" class="addAlignVertical">出厂编号:</font> <span class="overflowSty">68-2006-328</span></p></div>
<div><p><font color="green" class="addAlignVertical">测量范围:</font> <span id="clfw" class="overflowSty"></span></p></div>
<div><p><font color="green" class="addAlignVertical">计量特性:</font> <span id="jltx" class="overflowSty"></span></p></div>
<div><p><font color="green">强检类型:</font> 非强检</p></div>
<div><p><font color="green">检测类型:</font> 校准</p></div>
<div><p><font color="green">检&nbsp;&nbsp;定&nbsp;&nbsp;员:</font> 胡宁</p></div>
<div><p><font color="green" class="addAlignVertical">备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注:</font> <span class="overflowSty fixedWidth"></span></p></div>
<div><p><font color="green" class="addAlignVertical">证&nbsp;&nbsp;书&nbsp;&nbsp;号:</font> <span class="overflowSty fixedWidth">20000115113</span></p></div>
<div><p><font color="green">检测日期:</font> 2020-07-29</p></div>
<div><p><font color="green">有效日期:</font> 2021-07-28</p></div>
<div><p><font color="green" class="addAlignVertical">检测结果:</font><span id="jcjg" class="overflowSty"></span></p></div>
<div><p><font color="green">检测状态:</font> 已检完</p></div>
<div><p><font color="green">送检时间:</font> </p></div>

<script>

    window.onload = function () {
        var	clfw=document.getElementById("Dclfw").innerHTML;
        clfw=changeBackString(RICHTEXT.JsonToHtml(clfw));
        document.getElementById("clfw").innerHTML=clfw.replace(/<p>/g,"").replace(/<\/p>/g,"");
        var	jltx=document.getElementById("Djltx").innerHTML;
        jltx=changeBackString(RICHTEXT.JsonToHtml(jltx));
        document.getElementById("jltx").innerHTML=jltx.replace(/<p>/g,"").replace(/<\/p>/g,"");
        var	ggxh=document.getElementById("Dggxh").innerHTML;
        ggxh=changeBackString(RICHTEXT.JsonToHtml(ggxh))
        document.getElementById("ggxh").innerHTML=ggxh.replace(/<p>/g,"").replace(/<\/p>/g,"");
        var	ypmc=document.getElementById("Dypmc").innerHTML;
        ypmc=changeBackString(RICHTEXT.JsonToHtml(ypmc))
        document.getElementById("ypmc").innerHTML=ypmc.replace(/<p>/g,"").replace(/<\/p>/g,"");
        var	jcjg=document.getElementById("jcjg1").innerHTML;
        jcjg=changeBackString(RICHTEXT.JsonToHtml(jcjg))
        document.getElementById("jcjg").innerHTML=jcjg.replace(/<p>/g,"").replace(/<\/p>/g,"");
    };
</script>

</body></html>