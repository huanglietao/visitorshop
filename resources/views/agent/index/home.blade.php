@extends('layout.agent_official')

@section("content")
    <!--轮播部分-->
    <div class="swiper-container">
        <div class="swiper-wrapper">
           @foreach($adList[0]['ad_images'] as $k=>$v)
            <div class="swiper-slide">
                <div class="item @if($k==ZERO)active @endif" style='background: url({{$v}}) no-repeat top center; background-size: cover;'>
                    <a class="car-a" href=""></a>
                </div>
            </div>
            @endforeach




        </div>
        <!-- 如果需要分页器 -->
        <div class="pagination"></div>
        <div class="swiper-button-prev swiper-button-white"></div><!--左箭头。如果放置在swiper-container外面，需要自定义样式。-->
        <div class="swiper-button-next swiper-button-white"></div><!--右箭头。如果放置在swiper-container外面，需要自定义样式。-->
    </div>
    <!--轮播部分结束-->
    <div class="addon-section">
        <div class="container">
            <div class="text-line">
                <h5><span>模板展示</span>
                </h5>
                <div class="subtitle">海量模板自由选择</div>
            </div>
            <div class="addon-filter">
            </div>
            <div class="addon-list">
                <div class="row">
                    @foreach($template as $k=>$v)
                        <div class="col-lg-3 col-md-4 col-xs-6 grid-item" data-type="free">
                            <div class="addon-item">
                                <div class="tags tags-recommend"></div>
                                <div class="addon-img">
                                    <a href="#" title="{{$v['main_temp_name']}}"> <img src="{{$v['main_temp_thumb']}}" onerror="this.src='{{URL::asset('images/home/moren.jpg')}}'"  alt="{{$v['main_temp_name']}}" class="img-responsive" style="height: 260px"></a>
                                </div>
                                <div class="addon-info">
                                    <div class="title" style="text-align: center;margin-top:0px;margin-bottom:10px"> <a href="#" target="_blank" title="{{$v['main_temp_name']}}">{{$v['main_temp_name']}}</a> </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <div class="feature-section hidden-xs">
        <div class="container">
            <div class="text-line">
                <h5><span>我们的优势</span>
                </h5>
                <div class="subtitle"></div>
            </div>
            <div class="row" style="padding-bottom:30px">
                <div class="col-sm-4 fadeInDown text-center animated">
                    <img class="rotate" src="{{URL::asset('images/home/1214e9851ce1809bd2a33157e8354ef6.png')}}" alt="Generic placeholder image" style="width:  134px;height:  134px;border-radius: 50%;">
                    <h3>灵活的分销机制</h3>
                    <p class="lead">无需加盟费,交易及时结算一本即可代发</p>
                </div>
                <div class="col-sm-4 fadeInDown text-center animated">
                    <img class="rotate" src="{{URL::asset('images/home/89c2b0ee5a00119b82988e8c2e0a60b5.png')}}" alt="Generic placeholder image" style="width:  134px;height:  134px;border-radius: 50%;">
                    <h3>丰富的自有产品</h3>
                    <p class="lead">自由国家级研发中心影像产品</p>
                </div>
                <div class="col-sm-4 fadeInDown text-center animated">
                    <img class="rotate" src="{{URL::asset('images/home/d2685f253284e4294db100f1e48bb625.png')}}" alt="Generic placeholder image" style="width:  134px;height:  134px;border-radius: 50%;">
                    <h3>领先的中中央工厂</h3>
                    <p class="lead">创业版上市公司背景，占地约40000平方数码基地</p>
                </div>
            </div>
        </div>
    </div>
    <div class="partner-section">
        <div class="container">
            <div class="text-line gray">
                <h5><span>合作伙伴</span></h5>
            </div>
            <div class="row partner-list">
                @foreach($hzAd as $k=>$v)
            <div class="col-xs-6 col-sm-2 @if($k==ZERO)col-sm-offset-2 @else col-sm-offset-1 @endif ">
                <a href="/" title="合作伙伴" target="_blank">
                    <img class="img-responsive img hzimg" src="{{$v}}" alt="">
                </a>
            </div>
                @endforeach
              {{--  <div class="col-xs-6 col-sm-2 col-sm-offset-1">
                    <a href="/" title="合作伙伴" target="_blank">
                        <img class="img-responsive img hzimg" src="http://static.meiin.com/uploads/20180907/40ed56487d84fce4cf88f537443bedd2.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-2 col-sm-offset-1">
                    <a href="/" title="合作伙伴" target="_blank">
                        <img class="img-responsive img hzimg" src="http://static.meiin.com/uploads/20180907/40ed56487d84fce4cf88f537443bedd2.png" alt="">
                    </a>
                </div>--}}
            </div>
        </div>
    </div>
    <div class="access-section">
        <div class="container">
            <div class="text-line gray">
                <h5><span>分销商户专属服务</span></h5>
                <div class="subtitle">为您提供最方便舒适的服务</div>
            </div>
            <div class="partner-list access-list">
                @foreach($zsAd as $k=>$v)
                    <div class="zsimg">
                        <a href="/" title="专属服务" target="_blank"><img class="img-responsive zsimg-re" src="{{URL::asset($v)}}" alt=""></a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="success-section">
        <div class="container">
            <div class="text-line gray">
                <h5><span>开始体验分销商户服务</span></h5>
                <div class="subtitle suctitle">
                    <a href="/index/register"  target="_blank">
                        <button class="sub" href="#">开&nbsp;&nbsp;始</button>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* 设置大小，根绝需要决定大小 */
        .swiper-container {
            width: 100%;
            min-height: 550px;
        }

        /* 分页器样式 */
        .pagination {
            position: absolute;
            z-index: 20;
            bottom: 10px;
            width: 100%;
            text-align: center;
        }

        .swiper-imagesize {
            position: relative;
            overflow: hidden;
            width: 100%;
            min-height: 550px;
            height: 100%;
        }

        .caret {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px dashed;
            border-top: 4px solid \9;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }
        body{
            font-size: 14px;
        }

    </style>
@endsection
<!--  加载js -->
@section("js-file")

@endsection
@section("pages-js")
    var mySwiper = new Swiper ('.swiper-container', {
    centeredSlides: true,
    loop: true,
    //自动播放
    autoplay: {
    delay: 3500,
    disableOnInteraction: false
    },
    //左右箭头
    navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev'
    },
    //分页器
    pagination: {
    el: ".pagination",
    clickable: true,
    dynamicBullets: true
    }
    });
    //轮播

@endsection
