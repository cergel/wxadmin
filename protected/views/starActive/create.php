<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/3/3
 * Time: 16:03
 */
$this->breadcrumbs=array(
    '明星见面会'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新增活动</h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>