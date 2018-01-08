<?php
$this->breadcrumbs = array(
    '评论' => array('index'),
    '创建主创说',
);
?>
<div class="page-header">
    <h1>新增主创说</h1>
</div>
<?php $this->renderPartial('_form', array('model' => $model)); ?>