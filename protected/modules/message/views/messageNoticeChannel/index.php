<?php
/* @var $this MessageNoticeChannelController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Message Notice Channels',
);

$this->menu=array(
	array('label'=>'Create MessageNoticeChannel', 'url'=>array('create')),
	array('label'=>'Manage MessageNoticeChannel', 'url'=>array('admin')),
);
?>

<h1>Message Notice Channels</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
