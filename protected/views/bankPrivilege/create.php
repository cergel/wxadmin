<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/25
 * Time: 15:31
 */
$this->breadcrumbs=array(
    '银行优惠'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新建优惠</h1>
    </div>
<?php $this->renderPartial('_form', array(
    'model'=>$model,
    'selectedCities'=>$selectedCities
)); ?>