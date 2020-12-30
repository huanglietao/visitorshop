@extends('layout.iframe')
@section('main-content')
<input type="button" value="新弹窗" class="btn-dialog" data-url="{{URL::asset('/tips')}}" data-title = "添加" />
<input type="button" value="成功提示" class="btn-suc" />
<input type="button" value="删除警告" class="btn-de" />
@endsection



<!-- IonIcons -->
@section('pages-js')

        $(".btn-suc").click(function () {
            tip_success();
        });
        $(".btn-de").click(function () {
            tip_warn("/test");
        });

@endsection
