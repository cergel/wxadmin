<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/11
 * Time: 16:54
 */
$this->breadcrumbs=array(
    '可配置资源'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新增可配置资源</h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>