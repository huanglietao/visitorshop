<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>长荣云印刷SCM系统 - 管理平台</title>
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ URL::asset('assets/font-awesome/css/font-awesome.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="{{ URL::asset('assets/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/extends.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/theme_mes.css') }}">

    <!-- Google Font: Source Sans Pro -->
</head>
<style>
    body{
        margin:0px;padding:0px;font-size: 14px!important;
        font-weight: 400;
        font-family:"Microsoft Yahei", "Lucida Grande", Verdana, Lucida, Helvetica, Arial, sans-serif; !important;
    }
</style>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    @include('common.header',['systemName'=>'scm'])

    @include('common.slidebar',['system'=>'SCM', 'systemName'=>'scm'])

    <div class="content-wrapper tab-content" style="background:#fff">
        @if(true)
            <iframe style="padding-bottom:30px" id="menuFrame" name="menuFrame"  src="" width="100%" height="100%"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes">

            </iframe>
        @else
            @yield('content')
        @endif
        <div class="zdj-footer" style="">
            帮助中心 &nbsp;&nbsp;&nbsp;
            视频教程 &nbsp;&nbsp;&nbsp;
            功能更新 &nbsp;&nbsp;&nbsp;
        </div>
    </div>

    @include('common.footer')

</div>

<!-- jQuery -->


<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{ URL::asset('assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ URL::asset('assets/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('js/adminlte/adminlte.js')}}"></script>
<script src="{{ URL::asset('js/adminlte/admin.js')}}"></script>

<!-- OPTIONAL SCRIPTS -->

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->

<script src="{{ URL::asset('assets/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
<script src="{{ URL::asset('assets/raphael/raphael.min.js')}}"></script>
<script src="{{ URL::asset('assets/jquery-mapael/jquery.mapael.min.js')}}"></script>
<script src="{{ URL::asset('assets/jquery-mapael/maps/world_countries.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ URL::asset('assets/chart.js/Chart.min.js')}}"></script>
<!-- PAGE SCRIPTS -->
<script src="{{ URL::asset('js/adminlte/pages/dashboard2.js')}}"></script>

<!--<script src="{{ URL::asset('js/app.js')}}"></script>-->

<script>

</script>
</body>
</html>