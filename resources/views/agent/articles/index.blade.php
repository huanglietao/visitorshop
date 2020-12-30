<link rel="stylesheet" href="/css/agent/articles/maintemplate.css">
<link rel="stylesheet" href="/css/agent/articles/articlelist.css">
@extends('layout.agent_official')

@section("content")


    <div class="help-top">
        <h1>帮助文档</h1>

    </div>
    <section class="addon-section" style="padding-top: 0px;min-height: 500px">
        <div class="help-index clearfix">

            @foreach($artType as $k=>$v)
            <div class="help-index-cell" data-spm="">
                @if($k%2 == ZERO)
                <div class="help-index-cell-icon"> <img src="/images/home/userques.png"></div>
                @else
                <div class="help-index-cell-icon"> <img src="/images/home/helpcate.png"></div>
                @endif

                <div class="help-index-cell-content">
                    <h2>{{$v['cate_name']}}</h2>
                    <ul class="help-common-nav-list ce_hide">
                        @foreach($typeArticles as $ak=>$av)
                            @foreach($av as $akk=>$avv)
                                @if($avv['art_type'] == $v['cate_id']&& ($akk<4))
                                <li><a href="/articles/detail?id={{$avv['art_id']}}" target="_blank">{{$avv['art_title']}}</a></li>
                                    @php $num = $akk+1;@endphp
                                @endif
                            @endforeach
                        @endforeach
                    </ul>

                    <div class="help-common-nav-list-more">
                        <ul class="help-common-nav-list" style="display: none">
                            @foreach($typeArticles as $ak=>$av)
                                @foreach($av as $akk=>$avv)
                                    @if($avv['art_type'] == $v['cate_id']&& ($akk>3))
                                        <li><a href="/articles/detail?id={{$avv['art_id']}}" target="_blank">{{$avv['art_title']}}</a></li>
                                        @php $num = $akk+1;@endphp
                                    @endif
                                @endforeach
                            @endforeach

                        </ul>
                        @if($num >=4)
                            <a href="javascript:void(0);">更多</a><i class="fa fa-chevron-down"></i>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach

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

