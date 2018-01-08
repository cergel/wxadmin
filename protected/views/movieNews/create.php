<?php
$this->breadcrumbs=array(
	'首页推荐'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增首页推荐</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model, 'selectedCities' => $selectedCities)); ?>