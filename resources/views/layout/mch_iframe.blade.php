<!-- mch_iframe 里的布局 -->

<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="_token" content="{{ csrf_token() }}"/>
<title>订单详情</title>
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ URL::asset('assets/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/backend.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/iconfont.css')}}">
<link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/adminlte/theme_mch.css') }}">
<link rel="stylesheet" href="{{ URL::asset('js/jstree/dist/themes/default/style.css') }}">
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>

@yield('pages-css')

<style>
    body{margin:0px;padding:0px;font-size: 12px!important;}

</style>

<body>
    @yield('main-content')
</body>
<script src="https://cdn.bootcss.com/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>

<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>

<script src="{{ URL::asset('js/common.js')}}"></script>
<script src="{{ URL::asset('js/component.js')}}"></script>
<script src="{{ URL::asset('assets/nice-validator/dist/jquery.validator.js?local=zh-CN')}}"></script>

<script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
<script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ URL::asset('js/jstree/dist/jstree.js')}}"></script>

@yield('js-file')
<script>
    @yield('pages-js')
</script>



</html>