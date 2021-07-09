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
    <button type="button">添加</button>
    <ul id="treeDemo" class="ztree"></ul>
</div>
<script>
    var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = <?= json_encode($z_nodes, JSON_UNESCAPED_UNICODE) ?>;
    $(document).ready(function(){
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    });
</script>
</body>
</html>