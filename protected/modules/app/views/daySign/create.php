<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2015/11/6
 * Time: 14:56
 */
$this->breadcrumbs=array(
    'Day Sign'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>新增DaySign</h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>