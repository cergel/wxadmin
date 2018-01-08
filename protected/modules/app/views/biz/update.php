<?php
$this->breadcrumbs = array(
    'MovieGuides' => array('index'),
    '编辑',
);
?>

    <div class="page-header">
        <h1>
            编辑商业化详情
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?>        </small>
        </h1>
    </div>
    <script> var Config = <?php echo str_replace("#CDNPATH#", "", $model->config) ?></script>
<?php $this->renderPartial('_create', array('model' => $model)); ?>