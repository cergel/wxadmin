<?php
$this->breadcrumbs=array(
	'Show Comment Replies'=>array('index'),
	'新建',
);
?>
<div class="page-header">
    <h1>新增ShowCommentReply</h1>
</div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>