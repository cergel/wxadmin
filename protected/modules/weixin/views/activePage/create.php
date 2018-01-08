<?php
$this->breadcrumbs=array(
    '微信'=>array('default/index'),
	'活动模板'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新建活动模板</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'activities' => $activities, 'selectedCinemas' => $selectedCinemas)); ?>