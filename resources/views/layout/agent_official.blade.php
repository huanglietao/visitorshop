<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{isset($title)?$title:'首页'}}</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="renderer" content="webkit">
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ URL::asset('assets/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{URL::asset('assets/bootstrap/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('css/agent/index/base.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('css/agent/index/index.css')}}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/swiper/css/swiper.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('css/backend.css') }}">

    <script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
    <script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
    <script src="{{ URL::asset('assets/plupload/plupload.full.min.js')}}"></script>
    <script src="{{ URL::asset('assets/nice-validator/dist/jquery.validator.js?local=zh-CN')}}"></script>
    <script src="{{ URL::asset('js/agent/index/dropdown.js')}}"></script>
    <script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('js/component.js')}}"></script>
    <script src="{{ URL::asset('assets/swiper/js/swiper.min.js')}}"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="background-color: #F5F5F5;">
<!--头部html-->
@if(isset($flag))
    @include('common.agent.header',['flag'=>$flag])
@else
    @include('common.agent.header')
@endif

<div id="main" role="main" style="padding: 0 0 20px 0">
      @yield('content')
</div>

@if(!isset($flag))
    @include('common.agent.footer')
@endif
</body>

@yield('js-file')

<script>
    @yield('pages-js')
</script>
</html>