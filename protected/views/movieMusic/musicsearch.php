<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
?>
<div class="row">
	<div class="modal-body">
			<table width="90%">
				<tbody>
				<tr>
					<td width="80"><label for="key_name">关键词：</label></td>
					<td width="160"><input id="key_name" type="text" size="20" placeholder="填写音乐关键字"></td>
					<td width="50">
						<button id="search_sche_button" class="btn btn-primary" type="button">查询</button>
					</td>
					<td align="right">
						<button id="save_sche" type="button" class="btn">加入到列表</button>
					</td>
				</tr>
				</tbody>
			</table>
	</div>
	<div id="search_sche_list_container">
		<table id="search_sche_container" class="table-bordered" style="width: 100%;height: 100%;">
			<tr>
				<th><label><input type="checkbox" id="chkAll" />全选</label></th>
				<th>歌名</th>
				<th>歌手</th>
				<th>专辑</th>
				<th>歌曲大小</th>
				<th>时长</th>
				<th>播放</th>
			</tr>
		</table>
	</div>
	<div class="page_bar">

	</div>
</div>