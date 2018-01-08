<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/26
 * Time: 14:03
 */
$this->breadcrumbs=array(
    '银行信息'=>array('index'),
    //$model->id=>array('update','id'=>$model->id),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑银行信息        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?>        </small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>