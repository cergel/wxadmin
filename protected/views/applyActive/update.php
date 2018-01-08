<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/19
 * Time: 16:42
 */
$this->breadcrumbs=array(
    '活动管理'=>array('index'),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑活动管理     <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?>        </small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array(
    'model'=>$model,
)); ?>