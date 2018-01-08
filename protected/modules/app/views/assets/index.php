<?php
$this->breadcrumbs=array(
	'静态资源管理'=>array('index'),
	'管理',
);
$AssetsCfg = Yii::app()->params['Assets'];

?>
<div class="page-header">
    <h1>静态资源管理[CDN仅对现网环境生效]</h1>
</div>
<div class="row">
    <div class="col-xs-12">
		<div class="row">
			<form id='_fileForm' enctype='multipart/form-data'>
				<div class="col-xs-12">
				<div class="col-xs-12 center">
					<a href="javascript:fileupload.click();"><span  class="glyphicon glyphicon-upload" style="font-size:100px;color:#3ca0d9" aria-hidden="true"></span></a>
					<input name="file" id="fileupload" type="file"  style="display:none"/>
				</div>
				</div>
			</form>
		</div>
		<div class="row">
			<table id="filetable" class="table table-bordered table-striped">
			<tr>
				<th width="15%">文件名</th>
				<th>字节数</th>
				<th>创建日期</th>
				<th width="15%">MIME</th>
				<th width="10%">MD5</th>
				<th width="15%">本地连接</th>
				<th width="15%">CDN连接</th>
				<th>重命名</th>
				<th>删除</th>
			</tr>
			<?php foreach($list as $value):?>
			<?php
				//获取文件名以及文件信息
				$filename = array_pop(explode('/',$value));
				$filesize = filesize($value);
				$filectime = filectime($value);
				$mime = mime_content_type($value);
				$md5 = md5_file($value);
			?>
			<tr>
				<td class='filename'><?php echo $filename?></td>
				<td><?php echo $filesize?></td>
				<td style="word-break:break-all; word-wrap:break-word;"><?php echo date("Y-m-d H:i:s",$filectime)?></td>
				<td style="word-break:break-all; word-wrap:break-word;"><?php echo $mime?></td>
				<td style="word-break:break-all; word-wrap:break-word;"><?php echo $md5?></td>
				<td style="word-break:break-all; word-wrap:break-word;"><a target="_blank" href="<?php echo $AssetsCfg['local'].$filename?>"><?php echo $AssetsCfg['local'].$filename?></a></td>
				<td style="word-break:break-all; word-wrap:break-word;"><a target="_blank" href="<?php echo $AssetsCfg['cdn'].$filename?>"><?php echo $AssetsCfg['cdn'].$filename?></a></td>
				<td><a class="rename" href="#">重命名</a></td>
				<td><a class="delete" href="#">删除</a></td>
			</tr>
			<?php endforeach?>
			</table>
		</div>
	<div>
</div>
<?php 
//注册相关JS文件
$baseUrl = Yii::app()->baseUrl;
$cs=Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl .'/assets/js/assets.js',CClientScript::POS_END);
$cs->registerScriptFile($baseUrl .'/assets/js/jquery.form.js',CClientScript::POS_END);
?>
