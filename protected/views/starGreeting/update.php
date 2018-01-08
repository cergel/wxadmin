<?php
$this->breadcrumbs = array(
    '管理' => array('index'),
    '更新',
);
?>
    <div class="page-header">
        <h1>更新明星问候 #<?php echo $model->id; ?></h1>
    </div>
<?php $this->renderPartial('_form', array('model' => $model, 'count1' => $count1, 'count2' => $count2)); ?>