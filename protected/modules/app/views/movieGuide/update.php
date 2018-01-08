<?php
/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/2/23
 * Time: 14:37
 */
$this->breadcrumbs = array(
    'MovieGuides' => array('index'),
    '编辑',
);
?>

    <div class="page-header">
        <h1>
            编辑MovieGuide
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?>        </small>
        </h1>
    </div>
    <script> var guideConfig = <?php echo str_replace("#CDNPATH#", "", $model->config) ?></script>
<?php $this->renderPartial('_created', array('model' => $model)); ?>