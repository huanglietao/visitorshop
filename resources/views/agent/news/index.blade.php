<link rel="stylesheet" href="{{URL::asset('css/agent/news/news.css')}}">

@extends('layout.iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '消息管理  /  消息中心' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <!--  列表 start -->
    <div id="main">
        <!-- tab状态按钮 start  -->
        <div style="height: 100%">
            <div class="newsnav_tab">
                @component('component.navOperateTab',['navlist'=>['all'=>'全部消息','announce'=>'公告','notice'=>'通知'],'extendClass'=>'news_tab','extendPadding'=>'0 35'])
                @endcomponent
            </div>
            <!-- tab状态按钮 end  -->
            <!--  列表 start -->
            <div id="table">
                <table class="no-border-table" data-url="/news/list">
                    <thead>
                    <tr class="table-head">
                        <td style="width: 5%;text-align: center"><div class="works_checkBox" >
                                @component('component/checkbox',['checkbox'=>[''],'name'=>['checkall'],'custom_class'=>"checkall checkall checkbox",'left_distance'=>25,'right_distance'=>15])
                                @endcomponent

                            </div></td>
                        <td >标题内容</td>
                        {{--<td style="width:20%;">消息类型</td>--}}
                        <td style="width:33%;">发布时间</td>
                    </tr>
                    </thead>
                    <tbody class="tbl-content">

                    </tbody>
                </table>
                @component('component/paginate',['limit' => \Config::get('pageLimit')])
                @endcomponent
            </div>
            <!--  列表 end -->
        </div>

    </div>



@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/agent/news.js')}}"></script>
@endsection
@section("pages-js")

@endsection






