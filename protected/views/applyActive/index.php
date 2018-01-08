<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/19
 * Time: 15:01
 */
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs = array(
    '发现活动管理 ' => array('index'),
    '活动管理'
);
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){
 		$('#dialogInfo').html($(this).attr('href'));
		$('#dialogInfo').dialog({
				autoOpen:false,
				height:350,
				width:600,
				modal:true, //蒙层（弹出会影响页面大小）
				title:'链接查看',
				overlay: {opacity: 0.5, background: 'black' ,overflow:'auto'},
				buttons:{
					'确定':function(){
						$(this).dialog('close');
					}
		}});
		$('#dialogInfo').dialog('open');
		return false;
    });
");
?>
<div class="page-header">
    <h1>活动管理</h1>
    <a class="btn btn-success" href="/applyActive/create">新建活动</a>
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
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'type',
                    'value' => 'ApplyActive::model()->getType($data->type)',
                    'filter' => CHtml::activeDropDownList($model, 'type', (array('' => '全部') + ApplyActive::model()->getType())),
                    'htmlOptions' => array(
                        'width' => 100,
                    ),
                ),
                array(
                    'name' => 'title',
                    'htmlOptions' => array(
                        'width' => 300
                    )
                ),
                array(
                    'name' => 'start_apply',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'end_apply',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 80
                    )
                ),
                array(
                    'name' => 'apply_status',
                    'value' => 'ApplyActive::model()->getApplyStatus($data->start_apply,$data->end_apply)',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'question',
                    'value' => 'ApplyActive::model()->getQuestion($data->question)',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 60
                    )
                ),
                array(
                    'name' => 'support_count',
                    'value' => 'ApplyActive::model()->getApplyNum($data->id)',
                    'htmlOptions' => array(
                        'width' => 50,
                    ),
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'template' => '{view} {manager} {update} {delete} ',
                    'headerHtmlOptions' => array('width' => '150'),
                    'buttons' => array(
                        'manager' => array(
                            'label' => '报名管理',
                            'url' => 'ApplyActive::model()->getApplyRecord($data->id)',
                        ),
                        'view' => array(
                            'label'=>'查看',
                            //'url'=>'Yii::app()->params["active_page"]["final_url"] . "/" . $data->iActivePageID . "/index.html"',
                            'url'=>'ApplyActive::model()->getTemplateUrl($data->platform,$data->id)',
                            'options' => array(
                                //'target' => '_blank',
                                'do' => 'dialogInfo',
                                'value' => 'ApplyActive::model()->getTemplateUrl($data->platform,$data->id)',
                            )
                        ),
                    ),
                ),
            ),
        ));
        ?>
    </div>
    <div id="dialogInfo" style="display: none">stgfdrfyhdfrtdfh</div>
</div>