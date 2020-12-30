<!DOCTYPE html>
<html lang="en">

<body>
<header class="masthead">

</header>

<div class="container" >
    <div class="row" style="margin-top: 100px">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h3 style="text-align: center">
                {{ $message }},将在<span class="loginTime" style="color: red">{{ $jumpTime }}</span>秒后跳转至<a href="{{ $url }}" style="color: red">
                    @if(isset($jumpText))
                        {{$jumpText}}
                    @else
                        首页
                    @endif
                </a>页面

            </h3>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script>
    $(function(){
        var url = "{{$url}}";
        var loginTime = parseInt($('.loginTime').text());
        var time = setInterval(function(){
            loginTime = loginTime-1;
            $('.loginTime').text(loginTime);
            if(loginTime==0){
                clearInterval(time);
                window.location.href=url;
            }
        },1000);
    })
</script>
</body>
</html>
