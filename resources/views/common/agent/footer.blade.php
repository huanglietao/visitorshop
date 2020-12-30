<footer class="footer" style="clear:both;">
    <div class="container">
        <div class="row">
            <ul class="col-xs-6 col-md-4 row-first" style="font-size: 12px;">
                <li class="f-tit"><a href="/articles" style="color: #fff">帮助中心</a></li>
                @foreach($wtArtList as $k=>$v)
                <li><a href="/articles/detail?id={{$v['art_id']}}">{{$v['art_title']}}</a></li>
                @endforeach
            </ul>
            <ul class="col-xs-6 col-md-4 row-second" style="font-size: 12px;">
                <li class="f-tit">联系我们</li>
                <li>商务咨询：{{$deployInfo['deploy_seat_number']}}<span class="contact__blank"><br></span></li>
                <li>商务邮箱：{{$deployInfo['deploy_email']}}</li>
                <li>联系地址：{{$deployInfo['deploy_address']}}</li>
            </ul>
            <ul class="col-xs-12 col-md-4 row-third">
                <li class="f-tit">关注我们</li>
                <li><img class="wechat__image" src="{{$deployInfo['deploy_qr_code']}}" alt="分销平台"></li>
            </ul>

        </div>

    </div>
    <p class="address">
        © 2019 - 2022 长荣云印刷版权所有. 备案号:津ICP备08101169号-1
    </p>
</footer>