<?php
$this->breadcrumbs=array(
    'Question Sets'=>array('index'),
    '新建',
);
?>
    <div class="page-header">
        <h1>更新QuestionSet</h1>
    </div>
<?php $this->renderPartial('_updateform', array('model'=>$model,'modelQuestions'=>$modelQuestions)); ?>