<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/19
 * Time: 15:01
 */
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
Yii::app()->clientScript->registerScript('index', "
    $(document).on('click', '[do=dialogInfo]', function(){

 		$('#dialogInfo').html($(this).attr('href'));
		$('#dialogInfo').dialog({
				autoOpen:false,
				height:500,
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
$this->breadcrumbs = array(
    '投票管理 ' => array('index'),
    '投票管理'
);
?>
<div class="page-header">
    <h1>投票管理</h1>
    <a class="btn btn-success" href="/vote/create">新建投票</a>
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
                    'name' => 'name',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'name' => 'voteNum',
                    'value'=>'Vote::model()->getVoteNum($data->id)',
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
<<<<<<< Updated upstream
                    'name' => 'end_flag',
                    'filter'=> CHtml::activeDropDownList($model, 'end_flag', [''=>'全部','1'=>'截止','0'=>'进行中']),
                    'value'=>'$data->end_flag?"截止":"进行中"',
=======
                    'name' => 'voteStatus',
                    'value'=>'(Vote::model()->getVoteStatus($data->end_flag)==1 ? "已截止" : "正在进行")',
                    'filter'=> CHtml::activeDropDownList(Vote::model(), 'end_flag', array('0'=>'正在进行', '1' => '已截止')),
>>>>>>> Stashed changes
                    'htmlOptions' => array(
                        'width' => 40
                    )
                ),
                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'template' => '{view}  {update}  {delete} ',
                    'headerHtmlOptions' => array('width' => '150'),
                    'buttons' => array(
                        'view' => array(
                            'label'=>'查看',
                            'url'=>'Vote::model()->getDialogInfo($data->id)',
                            'options' => array(
                                'do' => 'dialogInfo',
                                'value' => 'Vote::model()->getDialogInfo($data->id)',
                            )
                        ),
                        'update' => array(
                            'label' => '更新',
                            'url' => 'Vote::model()->getInfoUrl($data->id)',
                        ),
                        'del' => array(
                            'label'=>'删除',
                            'url'=>'',
                            'options' => array(
                                'do' => 'dialogInfo',
                                'value' => 'Vote::model()->del($data->id)',
                            )
                        ),
                    ),
                ),
            ),
        ));
        ?>
    </div>
    <div id="dialogInfo" style="display: none">xx</div>
</div>