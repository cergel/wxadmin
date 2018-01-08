<?php
/**
 * Created by PhpStorm.
 * User: liulong
 * Date: 2017年01月17日
 * Time: 2017年01月17日16:17:00
 */
$this->breadcrumbs=array(
    '作者管理'=>array('index'),
    '编辑',
);
?>
    <div class="page-header">
        <h1>
            编辑作者信息        <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                #<?php echo $model->id; ?>        </small>
        </h1>
    </div>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>