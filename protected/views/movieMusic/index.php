<?php
$this->breadcrumbs=array(
	'音乐方案'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>音乐管理</h1>
    <a class="btn btn-success" href="/movieMusic/create">创建音乐方案</a>
</div>
<div class="row">
	<?php foreach($items as $value):?>
		<div class="col-xs-3 container">
			<div class="thumbnail CustomizationSeatItem" id="<?php echo $value->id?>">
				<img class="img" src="http://placehold.it/190x276">
				<div class="caption">
					<h3><?php echo $value->movie_name?></h3>
					<p>
						<a href="/movieMusic/update/<?php echo $value->id?>" class="btn btn-primary" role="button">配置原声方案</a>
						<a href="/movieMusic/delete/<?php echo $value->id?>"  class="btn btn-default btn-delete" role="button" >删除原声方案</a>
					</p>
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
