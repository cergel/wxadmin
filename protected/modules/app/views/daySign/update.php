<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2015/11/6
 * Time: 14:59
 */
$this->breadcrumbs=array(
    'Day Sign'=>array('index'),
    //$model->iId=>array('update','id'=>$model->iId),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑DaySign        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->iID; ?>        </small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>