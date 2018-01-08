<?php
/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/2/23
 * Time: 14:37
 */
$this->breadcrumbs=array(
    'MovieGuide'=>array('index'),
    '新建',
);
?>
<div class="page-header">
    <h1>创建观影秘籍</h1>
</div>
<?php $this->renderPartial('_created', array('model'=>$model)); ?>