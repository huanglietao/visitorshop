<!-- 通用权限树操作 -->
<div id="treeview"></div>

<script>

    var data = {!! $data !!};

    rendertree(data);

    function rendertree(content) {
        //console.log(content);
        $("#treeview")
            .on('redraw.jstree', function (e) {
                $(".layer-footer").attr("domrefresh", Math.random());
            }).on("loaded.jstree", function(event, data) {
            data.instance.clear_state(); // <<< 这句清除jstree保存的选中状态
            $("input[name='rules']").val('');
        })
            .jstree({
                "plugins": ["checkbox", "types","state"],

                "themes": {"stripes": true},
                "checkbox": {
                    "keep_selected_style": false,
                },
                "types": {
                    "root": {
                        "icon": "fa fa-folder-open",
                    },
                    "menu": {
                        "icon": "fa fa-folder-open",
                    },
                    "file": {
                        "icon": "fa fa-file-o",
                    }
                },

                "core": {
                    'themes':{
                        "icons":false,	//默认图标
                        "theme": "classic",
                        "dots": true,
                        "stripes" : false,	//增加条纹
                    },
                    'check_callback': true,
                    'data' :content,

                }
            });

        $('#treeview').on("changed.jstree", function (e, data) {
            //获取选中的列的ID，返回的是数组
            var checkedNodes = $('#treeview').jstree("get_selected");
            var i,j;
            for (i = 0, j = checkedNodes.length; i < j; i++) {
                // console.log(data.instance.get_node(checkedNodes[i]));
                checkedNodes = checkedNodes.concat(data.instance.get_node(checkedNodes[i]).parents);
                // checkedNodes = checkedNodes.concat(data.instance.get_parent(checkedNodes[i]));
            }
            //使用指定的函数过滤数组中的元素，并返回过滤后的数组
            checkedNodes = $.grep(checkedNodes, function (v, i, a) {
                return v != '#';
            });
            checkedNodes = checkedNodes.filter(function (itm, i, a) {
                return i == a.indexOf(itm);
            });
            $("input[name='rules']").val(checkedNodes);
           // console.log(checkedNodes);console.log($("input[name='rules']").val(checkedNodes));
        });
    }



</script>
