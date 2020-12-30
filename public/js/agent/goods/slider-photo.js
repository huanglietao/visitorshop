// 轮播图js
(function($) {
    $.fn.slider = function(opts) {
        opts = $.extend({
            imgW: 0,
            imgH: 0,
            timeout: "3000",
            moveTime: 500,
            autoSlider: true,
            moveStyle: "slide",
            addTag: true,
            tagSize: 10,
            tagDefaultBg: "rgba(0, 0, 0, 0.33)",
            tagActiveBg: "#fff",
            btnShow: true,
            btnW: 40,
            btnH: 40,
            btnBg: "rgba(255,255,255,0.1)"
        },
        opts || {});
        opts.timeout = opts.timeout < 1500 ? 1500 : opts.timeout;
        var ths = this,
        $imgs = ths.find("img"),
        $thsLink = ths.find("a"),
        imgSize = $imgs.length,
        $btn,
        $tag,
        count = 0;
        var init = function() {
            stopSlider();
            initCss();
            ths.find(".s_btn").remove();
            ths.find(".s_tag").remove();
            paramChange("autoSlider");
            paramChange("addTag");
            paramChange("btnShow");
            if (opts.addTag) {
                createTag(ths);
                $tag.find("span").eq(0).css({
                    "background-color": opts.tagActiveBg
                });
            }
            if (opts.btnShow) {
                createBtn();
                $btn.on("click",
                function() {
                    stopSlider();
                    var thsIndex = $btn.index($(this));
                    if (thsIndex == 1) {
                        imgsMove();
                    } else if (thsIndex == 0) {
                        imgsMove("left");
                    }
                });
            }
            $thsLink.eq(0).css({
                "left": 0
            });
            $thsLink.eq(imgSize - 1).css({
                "left": "-100%"
            });
            startSlider();
        },
        paramChange = function(p) {
            if (eval("opts." + p) == "true") {
                opts[p] = true;
            } else if (eval("opts." + p) == "false") {
                opts[p] = false;
            }
        },
        initCss = function() {
            ths.css({
                "position": "relative",
                // "height": opts.imgH + "px",
                "height": "502px",
                "overflow": "hidden",
                "background-color": "#fff",
                "z-index": "9"
            });
            $thsLink.css({
                "display": "block",
                "position": "absolute",
                "top": "0",
                "left": "100%",
                "width": "100%",
                "height": "502px",
                "z-index": "9"
            });
            $imgs.css({
                "max-width": "1700px",
                "max-height": "502px"
            });
        },
        createBtn = function() {
            var btnTem = '<span class="s_btn"><img src="/images/preImg.png" style="position: relative;top: 10px;left:13px;"></span><span class="s_btn"><img src="/images/nexImg.png" style="position: relative;top: 10px;left:15px;"></span>';
            ths.append(btnTem);
            $btn = ths.find(".s_btn");
            $btn.css({
                "position": "absolute",
                "top": "50%",
                "margin-top": ( - 1) * opts.btnH / 2 + "px",
                "width": opts.btnW + "px",
                "height": opts.btnH + "px",
                "cursor": "pointer",
                "background-color": opts.btnBg,
                "z-index": "10",
                "border-radius":"50px"
            });
            $btn.eq(0).css({
                "left": "10px"
            });
            $btn.eq(1).css({
                "right": "10px"
            });
        }
        createTag = function(warp) {
            var tem = '<div class="s_tag"></div>';
            warp.append(tem);
            $tag = warp.find(".s_tag");
            $tag.css({
                "position": "absolute",
                "left": "0",
                "bottom": "0",
                "width": "100%",
                "height": "40px",
                "line-height": "40px",
                "font-size": "0",
                "text-align": "center",
                "z-index": "10"
            });
            for (var i = 0; i < imgSize; i++) {
                $tag.append("<span></span>");
            }
            $tag.find("span").css({
                "display": "inline-block",
                "margin-top": (40 - opts.tagSize) / 2 + "px",
                "margin-left": "5px",
                "margin-right": "5px",
                "width": opts.tagSize + "px",
                "height": opts.tagSize + "px",
                "background-color": opts.tagDefaultBg,
                "border-radius": "9999px",
                "cursor": "pointer"
            });
        },
        imgsMove = function(direction) {
            if (opts.moveStyle == "slide") {
                if (direction == "left") {
                    count--;
                    count = count < 0 ? imgSize - 1 : count;
                    $thsLink.eq(count).stop(true, false).animate({
                        "left": 0
                    },
                    opts.moveTime,
                    function() {
                        var countR = count - 1;
                        $thsLink.eq(countR).css({
                            "left": "-100%"
                        });
                    });
                    var countL = count + 1 == imgSize ? 0 : count + 1;
                    $thsLink.eq(countL).stop(true, true).animate({
                        "left": "100%"
                    },
                    opts.moveTime);
                } else {
                    count++;
                    count = count == imgSize ? 0 : count;
                    $thsLink.eq(count).stop(true, false).animate({
                        "left": 0
                    },
                    opts.moveTime,
                    function() {
                        var countR = (count == imgSize - 1 ? -1 : count) + 1;
                        $thsLink.eq(countR).css({
                            "left": "100%"
                        });
                    });
                    $thsLink.eq(count - 1).stop(true, true).animate({
                        "left": "-100%"
                    },
                    opts.moveTime);
                }
            } else if (opts.moveStyle == "fade") {
                if (direction == "left") {
                    count--;
                    count = count < 0 ? imgSize - 1 : count;
                } else {
                    count++;
                    count = count == imgSize ? 0 : count;
                }
                $thsLink.css({
                    "left": 0
                }).stop().hide().eq(count).fadeIn(opts.moveTime);
            }
            tagsMove();
            startSlider();
        },
        tagsMove = function() {
            if (opts.addTag) {
                var $tagsObj = $tag.find("span");
                $tagsObj.css({
                    "background-color": opts.tagDefaultBg
                }).eq(count).css({
                    "background-color": opts.tagActiveBg
                });
            }
        },
        startSlider = function() {
            if (opts.autoSlider == true) {
                ths.data('autoSli', window.setTimeout(imgsMove, opts.timeout));
            }
        },
        stopSlider = function() {
            window.clearTimeout(ths.data('autoSli'));
        };
        var bannerImg = new Image;
        bannerImg.onload = function() {
            var loadImgW = ths.width(),
            loadImgH = bannerImg.height;
            opts.imgH = loadImgW * 9 / 18;
            init();
        }
        bannerImg.src = $imgs.eq(0).attr("src");
    }
    $.each($('*[e-fun = slider]'),
    function(i) {
        $('*[e-fun = slider]').eq(i).slider();
    });
})(jQuery);
