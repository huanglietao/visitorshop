@extends('layout.mch_iframe')

@section("main-content")
<link rel="stylesheet" href="{{URL::asset('css/agent/news/news.css')}}">

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '消息管理  /  消息中心  /  消息详情' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <!--  详情 start -->
@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <div id="main">
        <div class="detail_main" >
            <div class="detail_title" style="">{{$content['art_title']}}</div>
            <div class="cont_author_time"><span class="content_left">时间:{{$CommonPresenter->exchangeTime($content['created_at'])}}</span><span class="content_middle">发布者:{{$content['art_author']}} </span><span class="content_right">浏览次数:{{$content['art_views']}} </span></div>
            <hr />
            <div class="news_content">

                <div class="detail_content">{!! $content['art_content'] !!}</div>
            </div>
        </div>
        <div class="news_retrun">
            <a href="{{URL::asset('/news')}}"><button class="btn btn-write "   data-url="" data-title = "返回列表">返回列表</button></a>
        </div>
    </div>
    <!--  详情 end -->


@endsection









