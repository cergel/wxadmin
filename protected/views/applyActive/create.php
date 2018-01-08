<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 19:37
 */
$this->breadcrumbs=array(
    '活动管理'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新建活动</h1>
    </div>
<?php $this->renderPartial('_form', array(
    'model'=>$model,
)); ?>