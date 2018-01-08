<?php
$this->breadcrumbs = array(
    '影人' => array('index'),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑影人
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?></small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array('model' => $model, 'actor_head' => $actor_head,)); ?>