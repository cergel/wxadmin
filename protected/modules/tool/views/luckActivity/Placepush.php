<div class="page-header">
    <h1>查看中奖详情</h1>
</div>
<div class="row">
    <div class="col-xs-12">
<table class="items table table-striped table-bordered table-hover dataTable">
	<thead>
		<tr class="filters">
			<td>ID</td>
			<td>姓名</td>
			<td>电话</td>
			<td>身份证号</td>
			<td>地址</td>
			<td>奖品</td>
		</tr>
	</thead>
		<tbody>
		<?php foreach($list as $info){ ?>
		<tr class="odd">
			<td><?php echo $info['iId'];?></td>
			<td><?php echo $info['sUserName'];?></td>
			<td><?php echo $info['sMobile'];?></td>
			<td><?php echo $info['sCode'];?></td>
			<td><?php echo $info['sAddress'];?></td>
			<td><?php echo $info['sKudoName'];?></td>
		<tr class="even">
		<?php } ?>
		</tbody>
	</table>
    </div>
</div>
