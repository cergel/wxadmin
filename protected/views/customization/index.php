<?php
$this->breadcrumbs=array(
    '定制化选座'=>array('index'),
    '管理',
);
?>
<div class="page-header">
    <h1>定制化选座</h1>
    <a class="btn btn-success" href="/Customization/create">创建选座方案</a>
</div>
<div class="row">

  <?php foreach($items as $value):?>
  <div class="col-xs-4 container">
    <div class="thumbnail CustomizationSeatItem" id="<?php echo $value->MovieId?>" data-id="<?php echo $value->SeatId?>">
      <img class="img" src="http://placehold.it/190x276">
      <div class="caption">
        <h3>影片名称</h3>
        <p>开始时间:<?php echo $value->Start?></p>
        <p>结束时间:<?php echo $value->End?></p>
        <p><a href="/customization/update/<?php echo $value->SeatId?>" class="btn btn-primary" role="button">配置选坐方案</a> <a href="#"  class="btn btn-default btn-delete" role="button" >删除选坐方案</a></p>
      </div>
    </div>
	</div>
	<?php endforeach?>


</div>
<?php 
$baseUrl = Yii::app()->baseUrl;
$cs=Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl .'/assets/js/customization.js',CClientScript::POS_END);
 ?>