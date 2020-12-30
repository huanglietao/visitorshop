<!-- erpkf_iframe 里的布局 -->

<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="_token" content="{{ csrf_token() }}"/>
<title>6565</title>
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ URL::asset('assets/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/backend.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/iconfont.css')}}">
<link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/adminlte/theme_erpkf.css') }}">
<link rel="stylesheet" href="{{ URL::asset('js/jstree/dist/themes/default/style.css') }}">
<style>
    body{margin:0px;padding:0px;font-size: 12px!important;}

</style>

<body>
    @yield('main-content')
</body>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>

<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>

<script src="{{ URL::asset('js/common.js')}}"></script>
<script src="{{ URL::asset('assets/nice-validator/dist/jquery.validator.js?local=zh-CN')}}"></script>

<script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
<script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>

<script src="{{ URL::asset('js/jstree/dist/jstree.js')}}"></script>
@yield('js-file')
<script>
    @yield('pages-js')
</script>



</html>