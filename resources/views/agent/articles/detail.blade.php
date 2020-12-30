<link rel="stylesheet" href="/css/agent/articles/maintemplate.css">
<link rel="stylesheet" href="/css/agent/articles/articlelist.css">
@extends('layout.agent_official')

@section("content")
    <!--轮播部分-->

    @inject('CommonPresenter','App\Presenters\CommonPresenter');

    <section class="addon-section" style="padding-top: 80px;min-height:700px">

        <div class="container">
            <div class="help-menu-box">
                <div class="menu-back">
                    <a href="/articles" class="menu-back-link">&lt; 文档首页</a>
                </div>
                <div class="menu-splitline"></div>

                <div class="subNavBox">
                    @foreach($artType as $k=>$v)
                    <div class="subNav currentDd ">{{$v['cate_name']}}</div>
                    <ul class="navContent ">
                        @foreach($typeArticles as $ak=>$av)
                            @foreach($av as $akk=>$avv)
                                @if($avv['art_type']==$v['cate_id'])
                                <li><a href="/articles/detail?id={{$avv['art_id']}}">{{$avv['art_title']}}</a></li>
                                @endif
                            @endforeach
                        @endforeach
                    </ul>

                    @endforeach
                </div>
            </div>
            <div class="help-content">
                <!--导航栏面包屑--->
                <ul class="crumbs">
                    <li><a href="/articles">帮助文档</a> &gt;</li>
                    <li class="cateli"><a href=""></a> &gt;</li>
                    <li class="detaili"><span></span></li>
                </ul>

                <!--文章详细内容--->
                <div class="acontet">
                    <h1>{{$artInfo['art_title']}}</h1>
                    <div class="help-detail-title">
                        <div class="last-page">
                            <span class="last-page-btn">发布者<span class="last-page-title">：{{$artInfo['art_author']}}</span></span>
                        </div>
                        <div class="next-page">
                            <span class="next-page-btn">发布时间<span class="next-page-title last-page-title">：{{$CommonPresenter->exchangeTime($artInfo['created_at'])}}</span></span>
                        </div>
                    </div>
                    {!! $artInfo['art_content'] !!}
                </div>
                <!--文章的上下篇排版-->
                <div class="help-detail-page-turner">
                    @if(!empty($artPage['front']['art_id']))
                    <div class="last-page">
                        <a href="/articles/detail?id={{$artPage['front']['art_id']}}">
                            &lt;
                            <span class="last-page-btn">上一篇<span class="last-page-title">：@if(isset($artPage['front']['art_title'])) {{$artPage['front']['art_title']}} @else 已经是第一篇@endif</span></span>
                        </a>
                    </div>
                    @else

                    <div class="last-page">
                        <a href="#">
                            <span class="last-page-btn"></span>
                        </a>
                    </div>
                  @endif

                    @if(!empty($artPage['after']['art_id']))
                    <div class="next-page">
                        <a href="/articles/detail?id={{$artPage['after']['art_id']}}">
                            <span class="next-page-btn">下一篇<span class="next-page-title">：{{$artPage['after']['art_title']}}</span></span>
                            &gt;
                        </a>
                    </div>
                     @else
                    <div class="next-page">
                        <a href="#">
                            <span class="last-page-btn"></span>
                        </a>
                    </div>
                     @endif


                </div>
                <!--文章分页结束-->
            </div>

        </div>
    </section>


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
    <script src="/js/agent/articles.js"></script>
@endsection

