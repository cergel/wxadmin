<?php
$this->breadcrumbs = array(
    'jsPatch' => array('index'),
    'APP版本管理',
);
?>
<div class="page-header">
    <h1>版本管理</h1>
</div>
<div class="row">
    <div class="col-xs-12 text-right">
        <input type="text" name="version" class="input-large" placeholder="请输入APP版本号">
        <input type="text" name="remark" class="input-large" placeholder="注释">
        <button id="new_version" class="btn btn-info">创建APP版本</button>
    </div>
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'movieGuide-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 80,
                        'class' => 'class_movieGuide_id',
                    ),
                ),
                array(
                    'name' => 'app_version',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'created_by',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),

                array(
                    'name' => 'channelId',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'remark',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),

                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '80'),
                    'template' => '{createPatch}  {viewPatch} {deleteVersion}',
                    'buttons' => [
                        'createPatch' => array(
                            'options' => array('class' => 'create_patch', 'rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => Yii::t('app', '创建补丁')),
                            'label' => '<i class="glyphicon glyphicon-cloud-upload"></i>创建补丁<br/>',
                            'imageUrl' => false,
                        ),
                        'deleteVersion' => array(
                            'options' => array('class' => 'del_version', 'rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => Yii::t('app', '删除模块')),
                            'label' => '<i class="glyphicon glyphicon-remove"></i>删除版本<br/>',
                            'imageUrl' => false,
                        ),
                        'viewPatch' => array(
                            'options' => array('class' => 'view_patch', 'rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => Yii::t('app', '删除模块')),
                            'label' => '<i class="glyphicon glyphicon-remove"></i>查看补丁<br/>',
                            'imageUrl' => false,
                        ),
                    ],
                ),
            ),
        )); ?>
    </div>
</div>
<script type="text/javascript">
    $("#new_version").click(function () {
        var createVersion = {};
        createVersion.version = $(this).parent().find("input[name='version']").val();
        createVersion.remark = $(this).parent().find("input[name='remark']").val();
        if (createVersion.version == "") {
            alert("请输入APP版本号");
            return false;
        }
        if (!/\d+\.\d+\.\d+/.test(createVersion.version)) {
            alert("版本号格式错误 如:1.2.3");
            return false;
        }
        $.ajax({
            "url": "/app/jspatch/newver",
            "dataType": "json",
            "type": "POST",
            "data": createVersion,
            "cache": false,
            "success": function (msg) {
                if (msg.ret == '0') {
                    window.location.reload();
                } else {
                    alert(msg.msg);
                }
            }
        });
    });

    //创建补丁链接
    $(".create_patch").click(function () {
        var id = $($(this).parents("tr").find("td")[1]).text();
        window.location.href = "/app/jspatch/patch/version/" + id;
    });
    $(".view_patch").click(function () {
        var id = $($(this).parents("tr").find("td")[1]).text();
        window.location.href = "/app/jspatch/view/version/" + id;
    });
    $(".del_version").click(function () {
        var id = $($(this).parents("tr").find("td")[1]).text();
        if (confirm("是否删除版本 " + id + " 以及本版本下所有补丁")) {
            window.location.href = "/app/jspatch/delete/version/" + id;
        }

    });
</script>