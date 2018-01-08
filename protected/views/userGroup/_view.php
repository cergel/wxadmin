<?php
/* @var $this UserGroupController */
/* @var $data UserGroup */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupName')); ?>:</b>
	<?php echo CHtml::encode($data->groupName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isAdmin')); ?>:</b>
	<?php echo CHtml::encode($data->isAdmin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('authList')); ?>:</b>
	<?php echo CHtml::encode($data->authList); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('blackOrWhite')); ?>:</b>
	<?php echo CHtml::encode($data->blackOrWhite); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('createUser')); ?>:</b>
	<?php echo CHtml::encode($data->createUser); ?>
	<br />

	*/ ?>

</div>