<!DOCTYPE html>
<!-- saved from url=(0081)http://www.meiin.com/index.php?controller=mdiynew&action=temp_list&gid=103&uid=18 -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>模板</title>
    <style>
        body{background:#eee}
        ul{list-style-type: none;margin:0px;padding:0px}
        .temp-list ul li{
            display: inline-block;margin-top:10px;width:46%;margin-left:1.5%;background:#fff
        }
        .temp-list ul li img{
            width:100%
        }
        #cate{background:#fff;text-align:center;padding:5px 0}
        #cate ul li{ display: inline-block;color:#fff;
            font-size: 14px;padding-right:3px;padding-left:3px;
            margin: -2px;border-right:1px solid gainsboro;font-weight:500}
        #cate ul li a{text-decoration: none;color:#545454
        }
        .temp{cursor:pointer}
        #cate ul .xz a{
            color: #87D7D5;
        }
    </style></head>

<body>

<div style="max-width:640px;margin:0 auto;overflow: hidden">
    <div id="tips">
        <img src="./DIY EDITOR_files/Screenshot_20170616-122306_02.jpg" style="width:100%;display:none">
    </div>
    <header class="bar bar-nav" style="height:40px;line-height:40px;text-align:center;background:#87D7D5;position:relative">
        <input hidden type="text" id="prod_id" value="{{$product_info['prod_id']}}"/>
        <input hidden type="text" id="sku_id" value="{{$product_info['sku_id']}}"/>
        <input hidden type="text" id="page_num" value="{{$product_info['prod_p_num']}}"/>
        <input hidden type="text" id="mid" value="{{$product_info['mid']}}"/>
        <input hidden type="text" id="aid" value="{{$product_info['aid']}}"/>
        <input hidden type="text" id="order_no" value="{{$product_info['order_no']}}"/>
        <span style="display:inline-block;color:#fff;font-weight: bold;font-size:16px;white-space: nowrap;">{{$product_info['prod_name']}}</span>




    </header>

    <div style="top: 2.0rem;" class="content">

        <div id="cate">
            <ul class="list-line">
                <li  class="xz"  data-value="all"> <a  class="nav-link" href="#">全部</a></li>
                @foreach($category as $key=>$value)
                <li class="" data-value="{{$value[0]}}"><a class="nav-link" href="#">{{$value[1]}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="no-data" style="display: none;text-align: center">数据加载中...</div>
        <div class="temp-list ">
            <ul class="foreach-con" >
                @foreach($template as $key=>$value)

                <li class="foreach-data">
                    <a href="{{$value['url']}}" title="{{$value['main_temp_name']}}" >
                        <img src="{{$value['main_temp_thumb']}}" alt="{{$value['main_temp_name']}}" onerror="this.src='{{URL::asset('images/home/moren.jpg')}}'" class="img-responsive">
                    </a>

                    <p class="title"  style="text-align: center;padding:0px;margin:0px;color:red"> {{$value['main_temp_name']}} </p>
                </li>

                @endforeach
            </ul>
        </div>
    </div>

</div>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script>

    $(function(){
        $('.list-line li').click(function(){
            $(this).addClass("xz").siblings("li").removeClass("xz");
            var prod_id = $("#prod_id").val();
            var dv = $(this).attr("data-value");
            var loc = window.location.href;
            var sku_id = $("#sku_id").val();
            var page_num = $("#page_num").val();
            var mid = $("#mid").val();
            var aid = $("#aid").val();
            var order_no = $("#order_no").val();
            $(".foreach-con .foreach-data").hide();
            $(".no-data").html("数据加载中...");
            $(".no-data").show();
            $.ajax({
                url:'/goods/get_m_template',
                type:"POST",
                dataType:"JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                data:{
                    cate_id:dv,
                    mid:mid,
                    aid:aid,
                    order_no:order_no,
                    prod_id:prod_id,
                    sku_id:sku_id,
                    page_num:page_num,
                    loc:loc
                },
                success:function (data) {
                    if(data.status==101)
                    {
                        $(".no-data").html("该模版无数据");
                    }else if(data.status==200)
                    {
                        $(".no-data").hide();
                        $(".foreach-con").empty();
                        $(".foreach-con .foreach-data").show();
                        for (var i=1;i<=data.template.length;i++)
                        {
                            //图片加载出错时的默认图
                            var err = "this.src='/images/home/moren.jpg'";
                            var j = i-1;
                            var html ='<li class="foreach-data"><a href="'+data.template[j].url+'" title="'+data.template[j].main_temp_name+'">' +
                                '<img src="'+data.template[j].main_temp_thumb+'" alt="'+data.template[j].main_temp_name+'" onerror="'+err+'" class="img-responsive"></a>' +
                                '<p class="title"  style="text-align: center;padding:0px;margin:0px;color:red"> '+data.template[j].main_temp_name+' </p> </li>';
                            $(".foreach-con").append(html);
                        }
                    }
                }
            })



        })
    })
</script>
</body></html>