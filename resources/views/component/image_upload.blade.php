<!-- 图片上传组件-->
<div class="trip-uploader" style="border: 1px solid white;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" class="{{$num}}" value="{{$num}}">
    <input name="{{$name}}" type="hidden" class="img_content form-control" data-rule="{{isset($rule)?$rule:''}}" value="{{$value}}">
    <div class="uploade_main" id="{{$browse_btn}}">
        <div class="upload_icon"></div>
        <div class="upload_text">上传照片</div>
    </div>
    @if ($direction == 1)
        {{--图片水平排列--}}
        <div class="{{$content_class}}" style="display: block;float: right;width: 100%;padding-left: 110px;"></div>
    @elseif ($direction == 0)
        {{--图片垂直排列--}}
        <div class="{{$content_class}}" style="width: 100%;margin-top:106px;overflow: hidden;display: block"></div>
    @endif


</div>

{{--<script type="text/javascript" src="/js/jquery-3.3.1.min.js "></script>--}}
{{--<script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>--}}
<script>

    var ${{$content_class}} = $(".{{$content_class}}");
    var ${{$name}} = $('input[name="{{$name}}"]')
    var num{{$num}} = $(".{{$num}}").val();
    var ${{$uploader}} = new plupload.Uploader({ //实例化一个plupload上 传对象
        browse_button: "{{$browse_btn}}",
        runtimes: 'html5,flash,silverlight,html4',
        url: '/ajax/upload',//服务器接口地址
        flash_swf_url: '/js/plupload/Moxie.swf',
        silverlight_xap_url: '/js/plupload/Moxie.xap',
        multipart_params:{'_token': $('input[name="_token"]').val()},
        multipart: true,
        multi_selection: true, //多图上传
        filters: {
            mime_types: [ //只允许上传图片文件
                { title: "图片文件", extensions: "{{$img_format}}" }
            ]
        }
        , prevent_duplicates: !1
        , max_file_size: "{{isset($img_size)?$img_size:'10mb'}}"
        // , chunk_size: '1mb'//分片上传一定要注意压缩的大小
        //, resize: { width: 320, height: 240, quality: 90 }
        , init:
            {
                PostInit: function (a) {
                    // console.log("初始化完毕");
                },
                FilesAdded: function (uder, files) {
                    if(files.length > num{{$num}} ){
                        alert("数量超出限制")
                        for (var i = 0; i < files.length; i++) {
                            ${{$uploader}}.removeFile(files[i])
                        }
                        return;
                    }
                    if(${{$name}}.val() != '' && ${{$name}}.val().split(",").length >= num{{$num}}){
                        alert("数量超出限制")
                        for (var i = 0; i < files.length; i++) {
                            ${{$uploader}}.removeFile(files[i])
                        }
                        return;
                    }
                    // console.log("添加进队列");
                    ${{$content_class}}.css("display","block")
                    if(files.length == 1){
                        previewImage(files[0],function(imgsrc){
                            appendimg(files[0].id,imgsrc,files, uder,${{$content_class}},"{{$name}}");
                        })
                    }else{
                        for (var i = 0; i < files.length; i++) {
                            var file = files[i];
                            previewImage(files[i],function(imgsrc){
                                appendimg(file.id, imgsrc,files, uder,${{$content_class}},"{{$name}}");
                            })
                        }
                        uder.start();
                    }
                },
                BeforeUpload: function (uder, files) {
                    // console.log("开始上传");
                },
                UploadProgress: function (uder, file) {
                    // console.log("进度：[百分比:" + file.percent + "，状态：" + file.status + ",原始大小：" + file.origSize + ",已传：" + file.loaded + "]");
                    progress(file.id, file.percent, file, uder,${{$content_class}});
                },
                UploadFile: function (uder) {
                    // console.log(uder.id + "开始上传");
                },
                FileUploaded: function (uder, file, resObject) {
                    var result = resObject.response;
                    // console.log("上传完成" + result);
                    var str = ${{$name}}.val();

                    if(str ==''){
                        ${{$name}}.val(result);
                    }else{
                        str = str + "," + result;
                        ${{$name}}.val(str);
                    }
                    ${{$content_class}}.find(".img-close").each(function () {
                        if($(this).attr("data-url") == undefined){
                            $(this).attr("data-url",result)
                            return false;
                        }
                    });
                },
                // ChunkUploaded: function (a, b, c) {
                //     console.log("小片上传完成后");
                // },
                UploadComplete: function (uder, files) {
                    // console.log("上传完毕");
                },
                Error: function (up, args) {
                    // console.log(args);
                    if (args.code == -601) {
                        alert("格式错误")
                        // alert('[error] File:' + args.file + "   " + args.message);
                    }else if(args.code == -600){
                        alert("大小超出限制")
                    } else {
                        alert('[error]' + args.message);
                    }
                }
            }

    });
    ${{$uploader}}.init(); //初始化

    function appendimg(id, imgurl, files, uder,obj,obj_input) {
        var html = ' <div  class="' + id + ' file-item"><a class="fancybox"> <img src="'+imgurl+'"/> </a><span class="img-close" data-obj="'+obj_input+'" onclick="del(this)"></span></div>';
        obj.append(html);
        if(files.length == 1) {
            uder.start();
        }
    }
    function progress(id, percent, files, uder,obj) {
        if(files.length == 1) {
            uder.stop();
        }
        var c = obj.find("." + id);
        {{--var c = ${{$content_class}}.find("." + id);--}}
        var d = c.find(".progress span");
        d.length || (d = $('<p class="progress"><span></span></p>').appendTo(c).find("span"));
        d.css("width",   percent + "%")
    }

    //file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
    function previewImage(file,callback){
        if(!file || !/image\//.test(file.type)) return; //确保文件是图片
        if(file.type=='image/gif'){ //gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
            var gif = new moxie.file.FileReader();
            gif.onload = function(){
                callback(gif.result);
                gif.destroy();
                gif = null;
            };
            gif.readAsDataURL(file.getSource());
        }else{
            var image = new moxie.image.Image();
            image.onload = function() {
                image.downsize( 150, 150 );//先压缩一下要预览的图片,宽300，高300
                var imgsrc = image.type=='image/jpeg' ? image.getAsDataURL('image/jpeg',80) : image.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                callback && callback(imgsrc); //callback传入的参数为预览图片的url
                image.destroy();
                image = null;
            };
            image.load( file.getSource() );
        }
    }

    //移除图片
    function del(obj) {
        var path${{$name}} = $(obj).attr("data-url");
        var arr${{$name}} = ${{$name}}.val().split(",")

        $(obj).parent().remove();
        // var len = $(".$-images-list").find(".file-item").length
        var len = ${{$content_class}}.find(".file-item").length
        if(len == 0){
            ${{$content_class}}.css("display","none")
        }
        for (var i=0;i<arr${{$name}}.length;i++){
            if(arr${{$name}}[i] == path${{$name}}){
                arr${{$name}}.splice(i,1)
            }
        }
        $('input[name="'+$(obj).attr("data-obj")+'"]').val(arr${{$name}}.join(","))

        {{--$.ajax({--}}
            {{--url : "/ajax/del",--}}
            {{--type: 'POST',--}}
            {{--data:{--}}
                {{--path:path${{$name}},--}}
            {{--},--}}
            {{--dataType : 'JSON',--}}
            {{--headers: {--}}
                {{--'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')--}}
            {{--},--}}
            {{--success : function(data){--}}
                {{--if(data.status == 'error'){--}}
                    {{--alert(data.msg);--}}
                {{--}else{--}}
                    {{--$(obj).parent().remove();--}}
                    {{--// var len = $(".$-images-list").find(".file-item").length--}}
                    {{--var len = ${{$content_class}}.find(".file-item").length--}}
                    {{--if(len == 0){--}}
                        {{--${{$content_class}}.css("display","none")--}}
                    {{--}--}}
                    {{--for (var i=0;i<arr${{$name}}.length;i++){--}}
                        {{--if(arr${{$name}}[i] == path${{$name}}){--}}
                            {{--arr${{$name}}.splice(i,1)--}}
                        {{--}--}}
                    {{--}--}}
                    {{--$('input[name="'+$(obj).attr("data-obj")+'"]').val(arr${{$name}}.join(","))--}}
                {{--}--}}
            {{--},--}}
            {{--error : function(){--}}
                {{--alert('出错了')--}}
            {{--}--}}
        {{--});--}}
    }

    //生成随机数
    function uuid() {
        var s = [];
        var hexDigits = "0123456789abcdef";
        for (var i = 0; i < 36; i++) {
            s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
        }
        s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
        s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
        s[8] = s[13] = s[18] = s[23] = "-";

        var uuid = s.join("");
        return uuid;
    }
    
    //图片加载
    function loadImg(id, imgurl,obj,obj_input) {
        var url_arr = imgurl.split(",")
        for(var i=0;i<url_arr.length;i++){
            var src = url_arr[i]
            var html = ' <div  class="file-item"><a class="fancybox"> <img src="'+src+'"/> </a><span class="img-close" data-obj="'+obj_input+'" onclick="delImg{{$name}}(this)" data-url="'+url_arr[i]+'"></span></div>';
            obj.append(html)
        }

    }

    if(${{$name}}.val() != ''){
        loadImg(${{$name}},"{{$value}}",${{$content_class}},"{{$name}}")
    }

    //图片删除(加载)
    function delImg{{$name}}(obj) {
        var path${{$name}} = $(obj).attr("data-url");
        var arr${{$name}} = ${{$name}}.val().split(",")
        $(obj).parent().remove();
        var len = ${{$content_class}}.find(".file-item").length
        if(len == 0){
            ${{$content_class}}.css("display","none")
        }
        for (var i=0;i<arr${{$name}}.length;i++){
            if(arr${{$name}}[i] == path${{$name}}){
                arr${{$name}}.splice(i,1)
            }
        }
        $('input[name="'+$(obj).attr("data-obj")+'"]').val(arr${{$name}}.join(","))
    }
</script>


