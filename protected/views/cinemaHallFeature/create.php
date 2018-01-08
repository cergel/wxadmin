<?php
$this->breadcrumbs = array(
    '影城' => array('index'),
    '创建特效厅',
);
?>
    <div class="page-header">
        <h1>新增特效厅</h1>
    </div>
<?php $this->renderPartial('_form', array('model' => $model)); ?>