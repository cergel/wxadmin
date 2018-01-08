<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/3
 * Time: 16:03
 */
$this->breadcrumbs=array(
    '明星见面会'=>array('index'),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑活动        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->a_id; ?>        </small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>