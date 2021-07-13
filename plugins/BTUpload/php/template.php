<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BTUpload</title>
    <?php
    $this->link('static/zTree_v3/css/demo.css');
    $this->link('static/zTree_v3/css/zTreeStyle/zTreeStyle.css');
    $this->link('static/zTree_v3/js/jquery-1.4.4.min.js');
    $this->link('static/zTree_v3/js/jquery.ztree.all.js');
    ?>
</head>
<body>
<div class="">
    <div>
        <input id="path" type="text" placeholder="地址">
        <button type="button" id="bt_add">添加</button>
    </div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
<script>
    function zTreeOnClick(event, treeId, treeNode) {
        if (!treeNode.isParent) {
            $('#path').val(treeNode.path);
        }
    }

    var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        callback: {
            onClick: zTreeOnClick
        }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = <?= json_encode($z_nodes, JSON_UNESCAPED_UNICODE) ?>;
    $(document).ready(function(){
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    });

    $('#bt_add').click(function () {
        $.ajax({
            url: '<?= $this->pluginApi ?>add',
            type: "post",
            data: {path: $('#path').val(), source: '<?= $path['id'] ?>'},
            success: function(res) {
                alert(res.msg);
            },
            error: function (res) {
                alert(res.msg);
            },
            complete: function (res) {
            }
        });
    });
</script>
</body>
</html>