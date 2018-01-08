<?php
/* @var $this PromotionSharingController */
/* @var $model PromotionSharing */

$this->breadcrumbs = array(
    '拉新分享管理' => array('index'),
    '更新',
);
?>

    <h1>#<?php echo $model->id; ?></h1>


<?php $this->renderPartial('_form', compact('model', 'channels')); ?>