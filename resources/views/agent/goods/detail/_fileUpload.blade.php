<tr>
    <input id="up{{$type}}" type="hidden" data-value="{{$type}}" value="{{$cross}}"/>
    <input type="hidden" data-value="check" id="checks{{$type}}" value="error">
    <td class="tit"><span style="color: red;margin-right: 5px">*</span>{{$size_name}}上传：</td>
    <td style="padding: 5px 0">
        <span style="position:relative;">
            <button type="button" id="browse_btn{{$type}}" class="btn btn-default" style="position: relative;"><i class="fa fa-upload"></i> 上传</button>
        </span>
        <span style="color:gray;font-size:12px">注：请按规则上传PDF文件</span>
        <input type="hidden" name="{{$file_path}}" id="{{$file_path}}"/>
    </td>
</tr>
<tr>
    <td class="tit"></td>
    <td>
        <div id="file_list{{$type}}"></div>
    </td>
</tr>

<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
<script>
    //实例化一个plupload上传对象
    var ${{$uploader}} = new plupload.Uploader({
        runtimes : 'html5,flash,html4',//支持上传的方式
        browse_button : "browse_btn{{$type}}", //触发文件选择对话框的按钮，为那个元素id
        url : '/goods/fileUpload', //服务器端的上传页面地址
        flash_swf_url: '/js/plupload/Moxie.swf',
        silverlight_xap_url: '/js/plupload/Moxie.xap',
        multipart_params:{'_token': $('meta[name="_token"]').attr('content')},
        multi_selection:false,
        unique_names : true,    //唯一文件名，false为本身文件名
        max_retries:3,       //发生网络错误时重试次数
        max_file_size: '2000mb',//限制为2MB
        chunk_size:'1000kb',//大文件切分的单元
        offset:0,
        filters: {
            mime_types:[
                {title: "PDF",extensions: "pdf"}
            ],
            prevent_duplicates:false
        },

        init: {
            //文件选择
            FilesAdded: function(up, files) {
                var fileLen = up.files.length;
                if(fileLen>1){
                    ${{$uploader}}.splice(0,fileLen-1);
                }
                ${{$uploader}}.start();
                plupload.each(files, function(file) {
                    document.getElementById('file_list{{$type}}').innerHTML = '' +
                        '<div style="width:350px" id="' + file.id + '">' + file.name +
                        ' (' + plupload.formatSize(file.size) + ') ' +
                        '</br><span id="check'+{{$type}}+'"  style="display: none;color: red;margin-right: 5px">图片数量不符合要求,请先删除上传文件再重新整理上传</span>' +
                        '<a id="stop_file'+{{$type}}+'" href="javascript:;" onclick="stop'+{{$type}}+'()">暂停上传</a>' +
                        '<a id="restart'+{{$type}}+'" href="javascript:;" onclick="start'+{{$type}}+'()" style="display: none">继续上传</a>' +
                        '<span id="success'+{{$type}}+'"  style="display: none;color: #2a9055;margin-right: 5px">上传成功</span>' +
                        '<span id="fail'+{{$type}}+'"  style="display: none;color: red;margin-right: 5px">上传失败</span>' +
                        '<a id="delPDF'+{{$type}}+'" href="javascript:;" onclick="delpdf'+{{$type}}+'()" style="display: none;color: red;">删除文件</a></br>' +
                        '<div style="width: 85%">' +
                        '<div class="bar" style="background-color: #0785d1;display: block;width: 0%;height: 15px;text-align: center"><b></b></div>' +
                        '</div></div>';
                });
            },

            //文件上传
            UploadProgress: function(up, file) {
                var percent = file.percent;
                document.getElementById(file.id).getElementsByClassName('bar')[0].style.width=percent + "%";
                document.getElementById(file.id).getElementsByClassName('bar')[0].getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                if(percent==100){
                    $("#stop_file{{$type}}").hide();
                    $("#restart{{$type}}").hide();
                    $("#success{{$type}}").show();
                    $("#delPDF{{$type}}").show();
                }
            },

            //文件上传结束
            FileUploaded: function (up, file, res) {
                //文件上传完成后，up:plupload对象，file:上传的文件相关信息，res:服务器返回的信息
                var data = JSON.parse(res.response);
                if(data.code==2 && data.msg=='success'){
                    $("#stop_file{{$type}}").hide();
                    $("#restart{{$type}}").hide();
                    $("#success{{$type}}").show();
                    $("#fail{{$type}}").hide();
                    $("#delPDF{{$type}}").show();
                    $("#{{$file_path}}").val(data.file_path);


                    var cross = $("#up{{$type}}").val();
                    var type = $("#up{{$type}}").attr("data-value");
                    var page = $("#page").val();
                    if(page==0){
                        page = 1;
                    }
                    $.ajax({
                        type: 'POST',
                        url: '/goods/checkPage',
                        dataType: "json",
                        data: {
                            file_path:data.file_path,
                            type:type,
                            cross:cross,
                            page:page
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        success: function(res) {
                            if(res.status==200 && res.success == 'true'){
                                layer.msg("上传的{{$size_name}}文件检验图片数量成功");
                                $("#checks{{$type}}").val(1);
                                $("#check{{$type}}").hide();
                            }else{
                                layer.msg("上传的{{$size_name}}文件图片数量不符合要求,请重新整理上传");
                                $("#checks{{$type}}").val("error");
                                $("#check{{$type}}").show();
                            }
                        },
                        error:function(res){
                            layer.msg("程序出错了，请联系工作人员");
                        }
                    });




                }
                else if(data.code==1 && data.msg=='error'){
                    $("#stop_file{{$type}}").hide();
                    $("#restart{{$type}}").hide();
                    $("#success{{$type}}").hide();
                    $("#fail{{$type}}").show();
                    $("#check{{$type}}").hide();
                    $("#delPDF{{$type}}").hide();
                    tip_note('上传文件出错，请重新上传！','error');
                }

            },

            Error: function(up, err) {
                if(err.code=='-600'){
                    layer.msg("选择的文件过大");
                }
                if(err.code=='-200'){
                    $("#stop_file{{$type}}").hide();
                    $("#restart{{$type}}").hide();
                    $("#success{{$type}}").hide();
                    $("#fail{{$type}}").show();
                    $("#check{{$type}}").hide();
                    $("#delPDF{{$type}}").hide();
                    layer.msg("网络错误！请重新上传");
                }
            }
        }
    });
    //在实例对象上调用init()方法进行初始化
    ${{$uploader}}.init();

    //暂停上传
    function stop{{$type}}(){
        ${{$uploader}}.stop();
        $("#stop_file{{$type}}").hide();
        $("#restart{{$type}}").show();
    }
    //继续上传
    function start{{$type}}() {
        ${{$uploader}}.start();
        $("#stop_file{{$type}}").show();
        $("#restart{{$type}}").hide();
    }

    function delpdf{{$type}}() {
        layer.confirm("您确定要删除已上传的文件吗",{btn:['确定','取消']},
            function(index) {
                var file_path = $("#{{$file_path}}").val();
                $.ajax({
                    type: 'POST',
                    url: '/goods/delete_pdf',
                    dataType: "json",
                    data: {
                        file_path:file_path
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    error:function(res){
                        layer.msg(res.message);
                    },
                    success: function(data) {
                        if(data.status==200 && data.success == 'true'){
                            layer.close(index);
                            layer.msg("文件已成功删除！");
                            document.getElementById('file_list{{$type}}').innerHTML = '';
                        }
                    }
                });
            })



    }



</script>