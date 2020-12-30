<div id="main">
    @csrf
    <div id="checkImg">
        <p style="padding-top: 12px;">
            @if($data['count'] != 0)
                <span style="margin-right: 15px;">作品名称:{{$data['workInfo']['prj_name']}}</span><span>作品编号:{{$data['workInfo']['prj_sn']}}</span>
            @endif
            <span style="margin-left: 15px;margin-right: 15px;">总页数:{{$data['count']}}</span>
            <input type="button" value="切换显示" data="1" id="change">
            <input type="button" value="重新出图" data="{{$project_no}}" id="create_queue">
            <input type="button" value="刷新" data="181028212104981984-1-1" id="refresh">
        </p>
    </div>


    <div id="img-main" style="text-align: left;">
        <ul>
            @foreach($data['pageList'] as $k=>$v)
                <li style="display: inline-block;">
                    <img src="{{$v['path']}}" data="{{$v['path']}}" onerror="this.src='/images/nopic.jpg'" style="height: 285px;">
                    <p class="int-page">{{$v['prj_page_name']}}</p>
                </li>
            @endforeach
        </ul>

    </div>
</div>
<style>
    body{overflow-y:scroll;background: white;}
    #ribbon{display: none}
    .content{padding: 0}
    #checkImg{width:100%;height:50px;background-color: #F1F1F1;}
    #checkImg p{font-size:18px;color:#666;margin-left: 70px}
    #img-main{text-align:left;}
    #img-main li img{height:300px;margin-top:20px;border:1px solid #eaeaea}
    #img-main ul li{display:inline-block;margin-left:20px}
    .int-page{font-size:28px;text-align:center;color:#666}
    @media (max-width: 1200px) {
        #img-main{width: 100%}
        #img-main ul li{margin-left:0}
        #img-main ul{margin:0;padding: 0}
    }
    @media (max-width: 990px) {
        #img-main li img{width:100%;}
    }

    @media (max-width: 500px) {
        #checkImg p{font-size:20px;color:#666}
    }

</style>
<script type="text/javascript" src="/js/jquery-3.3.1.min.js "></script>
<script>
    $(function(){
        //切换显示
        $('#change').click(function(){
            var flag = $('#change').attr('data');
            if(flag == 1){
                $('#main').find('ul li').css('display', 'block');
                $('#main').find('ul li img').css('height', '450');
                $('#img-main').css('text-align','center');
                $('#change').attr('data',2);
            }else{
                $('#main').find('ul li').css('display', 'inline-block');
                $('#main').find('ul li img').css('height', '285px');
                $('#img-main').css('text-align','left');
                $('#change').attr('data',1);
            }

        });

        //重新加载
        $("#refresh").click(function(){
            window.location.reload();
        });

        //点击重新出图更新合成状态字段后刷新页面
        $("#create_queue").click(function(){
            var sn = $("#create_queue").attr('data');
            $.ajax({
                url : '/order/list/reload',
                type : 'POST',
                data : {'project_no':sn},
                dataType : 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success : function (res) {
                    if(res.success == 'true'){
                        window.location.reload()
                    }else{
                        alert(res.message)
                    }
                }

            });
        });
    })

</script>