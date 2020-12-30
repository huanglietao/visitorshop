<!-- table列表数据显示  -->
@if(!empty($list))
	@foreach  ($list as $k=>$v)
		@inject('CommonPresenter','App\Presenters\CommonPresenter');
		{{--订单开始--}}
		<tr class="o_list_tr">
			<td colspan="7" style="vertical-align: middle; text-align: left;padding-left: 10px;">
				<label for="checkedres{{$k}}"></label>
				<span class="o_next_span">订单号：<span class="data-text">{{$v['order_no']}}</span></span>
				<span class="o_next_span">订单项目号：<span class="data-text">{{$v['ord_prj_no']}}</span></span>
				<span class="o_next_span" >交易时间：<span class="data-text">{{$CommonPresenter->exchangeTime($v['created_at'])}}</span> </span>
				<span class="o_next_span" >总数量：
					<span class="data-text">{{$v['nums']}} 件</span>
			    </span>
				<span class="o_next_span" >来源：<span class="data-text">{{$v['agent_name']}}【{{$v['cha_name']}}】</span></span>
			</td>
		</tr>


		{{--一订单一商品情况--}}
		<tr>
			<td class="o_attr_goods">
				<div class="o_works_info">
					<div class="o_works_img">
						<img src="{{$v['item']['prod_main_thumb']}}">
					</div>
					<div class="o_works_detail">
						<p class="o_works_name">{{$v['item']['prod_name']}}</p>
						<p class="o_works_spec">{{$v['item']['attr_str']}}</p>
					</div>
				</div>
			</td>
			<td class="o_attr o_attr_price">
                {{--￥{{$v['prod_price']}}--}}
            </td>
			<td class="o_attr o_attr_num">{{$v['nums']}}</td>
			<td class="o_attr o_attr_other o_attr_end td_border">
				<p>
                    <span  class="o_real_price">
                        {{--￥{{$v['new_sp_order_amount']}}--}}
                    </span>
                </p>
			</td>
			<td class="o_attr o_attr_other o_attr_end td_border">
				<span class="o_logistics">{{$CommonPresenter->spOrderStatusExchange($v['sp_order_status'])}}<br></span>
			</td>
			<td class="o_attr o_attr_sale td_border" style="padding-top: 8px;">
				<div class="qr_code" style="margin: 0 auto;display: none" data-value="{{$v['ord_prj_no']}}"></div>
				<div id="{{$v['new_sp_ord_id']}}" style="margin: 0 auto"></div>
				<input type="button" onclick="prints({{$v['new_sp_ord_id']}})" value="打印" style="background-color: white;border: 1px solid white;color: blue;font-size: 12px;"/>
			</td>
			<td class="o_attr o_attr_other o_attr_tags o_attr_end td_border">
				<p style="margin-bottom: 0px;">{{$v['item']['is_download']}}</p>
				@foreach($v['item']['download'] as $download)
					@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
						<p style="margin-bottom: 5px;">
							<a href="/order/orders/download?url={{$download['url']}}&id={{$download['new_sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}&item_no={{$v['originally_ord_prj_no_']}}" target="_blank">
								<button class="btn  btn-primary btn-3F51B5">封面下载</button>
							</a>
						</p>
					@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
						<p>
							<a href="/order/orders/download?url={{$download['url']}}&id={{$download['new_sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}&item_no={{$v['originally_ord_prj_no_']}}" target="_blank">
								<button class="btn  btn-primary btn-3F51B5">内页下载</button>
							</a>
						</p>
					@endif
				@endforeach
			</td>
		</tr>
		{{--订单结束--}}

		{{--订单间隔--}}
		<tr class="o_spacing"></tr>
	@endforeach
@else
	<tr><td colspan=7>暂无记录</td></tr>
@endif
<script>
    //生成canvas形式的二维码
    $(".qr_code").each(function () {
        $(this).qrcode({
            width:80,
            height:80,
            text:$(this).attr("data-value")//需要生成的内容
		})
    })

    //从 canvas 提取图片 image
    function convertCanvasToImage(canvas) {
        //新Image对象，可以理解为DOM
        var image = new Image();
        // canvas.toDataURL 返回的是一串Base64编码的URL
        // 指定格式 PNG
        image.src = canvas.toDataURL("image/png");
        return image;
    }

    //获取网页中的canvas对象
    $('canvas').each(function () {
        //调用convertCanvasToImage函数将canvas转化为img形式
        var img=convertCanvasToImage($(this)[0])
        //将img插入容器
        $(this).parent().next()[0].append(img)
    })

    //打印操作
    function prints(id) {
        var userAgent = navigator.userAgent.toLowerCase(); //取得浏览器的userAgent字符串
        if (userAgent.indexOf("trident") > -1) {
            alert("请使用google或者360浏览器打印");
            return false;
        } else if (userAgent.indexOf('msie') > -1) {
            var onlyChoseAlert = simpleAlert({
                "content" : "请使用Google或者360浏览器打印",
                "buttons" : {
                    "确定" : function() {
                        onlyChoseAlert.close();
                    }
                }
            })
            alert("请使用google或者360浏览器打印");
            return false;
        } else {//其它浏览器使用lodop
            var oldstr = document.body.innerHTML;
            var headstr = "<html><head><title></title></head><body>";
            var footstr = "</body></html>";
            //执行隐藏打印区域不需要打印的内容
            // document.getElementById("otherpho").style.display="none";
            var printData = document.getElementById(id).innerHTML; //获得 div 里的所有 html 数据
            var wind = window.open("", "newwin",
                "toolbar=no,scrollbars=yes,menubar=no");
            wind.document.body.innerHTML = headstr + printData + footstr;
            wind.print();
            //打印结束后，放开隐藏内容
            // document.getElementById("otherpho").style.display="block";
            wind.close();
            window.document.body.innerHTML = oldstr;
        }
        changeStatus(id)
    }

    function changeStatus(id) {
        $.ajax({
            url : '/order/orders/status',
            type : 'POST',
            data : {
                'new_sp_ord_id':id,
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function (data) {
                loadTable();
            }

        });
    }
    
    var beforePrint = function () {
		console.log(123)
    }
    var afterPrint = function () {
		console.log(456)
    }

    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function(mql) {
            if (mql.matches) {
                beforePrint();
            } else {
                afterPrint();
            }
        });
    }
</script>

