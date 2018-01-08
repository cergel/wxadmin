<?php
/* @var $this CinemaHallFeatureController */
/* @var $model CinemaHallFeature */
$this->breadcrumbs = array(
    '影城' => array('index'),
    '更新特效厅',
);
?>
    <div class="page-header">
        <h1>
            更新特效厅
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?></small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array('model' => $model)); ?>