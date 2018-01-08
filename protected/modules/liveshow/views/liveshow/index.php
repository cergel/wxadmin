<?php

Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs = array(
    '直播管理 ' => array('index'),
//	'直播管理'
);
?>
<div class="page-header">
    <h1>直播列表</h1>
    <a class="btn btn-success" href="/liveshow/liveshow/edit">新建直播</a>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'apply-active-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 5,
                    ),
                ),
                array(
                    'name' => 'name',
                    'htmlOptions' => array(
                        'width' => 10
                    ),
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'template' => '{view} {delete} ',
                    'headerHtmlOptions' => array('width' => '50'),
                    'buttons' => array(
                        'del' => [
                            'label' => '删除',
                            'url' => '',
                            'options' => array(
                                'do' => 'dialogInfo',
                                'value' => 'Liveshow::model()->del($data->id)',
                            )
                        ],
                        'offline' => [
                            'label' => '下线',
                            'url' => 'Liveshow::model()->getOfflineUrl($data->id)',
                        ],
                        'view' => [
                            'label' => '编辑',
                            'url' => 'Liveshow::model()->getInfoUrl($data->id)',
                        ],
                    ),
                ),
            ),
        ));
        ?>
    </div>
    <div id="dialogInfo" style="display: none">stgfdrfyhdfrtdfh</div>
</div>