<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/4/26
 * Time: 12:02
 */
$this->breadcrumbs=array(
    '银行信息'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新建信息</h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>