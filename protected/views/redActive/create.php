<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/14
 * Time: 11:28
 */
$this->breadcrumbs=array(
    '红点活动'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新增活动</h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>