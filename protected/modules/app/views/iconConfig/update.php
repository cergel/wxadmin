<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/11
 * Time: 16:54
 */
$this->breadcrumbs=array(
    '可配置资源'=>array('index'),
    //$model->id=>array('update','id'=>$model->id),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑可配置资源        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?>        </small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>