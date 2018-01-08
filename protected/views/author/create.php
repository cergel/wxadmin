<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 2017年01月17日
 * Time: 2017年01月17日16:17:00
 */
$this->breadcrumbs=array(
    '作者管理'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新建作者</h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>