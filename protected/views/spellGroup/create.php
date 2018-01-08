<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/8/11
 * Time: 16:08
 */
$this->breadcrumbs=array(
    '拼团活动'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新建活动</h1>
    </div>
<?php $this->renderPartial('_form', array(
    'model'=>$model,
)); ?>