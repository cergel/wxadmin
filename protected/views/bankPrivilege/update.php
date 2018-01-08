<?php
/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/4/26
 * Time: 18:15
 */
$this->breadcrumbs=array(
    '银行优惠'=>array('index'),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑银行优惠        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?>        </small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array(
    'model'=>$model,
    'selectedCities'=>$selectedCities,
)); ?>