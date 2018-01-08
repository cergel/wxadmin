<?php
/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/2/23
 * Time: 14:37
 */
$this->breadcrumbs=array(
    '商业化详情页'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>创建商业化详情</h1>
    </div>
<?php $this->renderPartial('_create', array('model'=>$model)); ?>