<?php
$this->breadcrumbs = array(
    '用户权限分组' => array('index'),
    'Update',
);
?>
    <div class="page-header">
        <h1>
            编辑用户权限分组
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?></small>
        </h1>
    </div>

<?php $this->renderPartial('_form', array('model' => $model)); ?>