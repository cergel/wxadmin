<div class="page-header">
    <h1>查看中奖详情</h1>
</div>
<div class="row">
    <div class="col-xs-12">
<table class="items table table-striped table-bordered table-hover dataTable">
	<thead>
		<tr class="filters">
			<td>日期</td>
			<?php if(!empty($goodsList)){
				foreach($goodsList as $info){
			?>
			<td><?php echo $info->sPrizeName;?></td>
			<?php } }?>
			<td>合计</td>
		</tr>
	</thead>
		<tbody>
		<?php foreach ($dateArr as $key=>$value){?>
		<tr class="odd">
			<td><?php echo $key;?></td>
			
			<?php if(!empty($goodsList)){
				$num =0;
				foreach($goodsList as $info){
			?>
			<td><?php 
			  if (!empty($value[$info->iId])){
				$daynum=$value[$info->iId];
				}else{
				$daynum =0;	
				}
				$num +=$daynum;
			?>
			<?php echo @$daynum;?>
			<?php if($info->iType==1){
					echo "<a href='/tool/LuckActivity/physical/id/{$info->iActivityId}/time/{$key}/goodsId/{$info->iId}' target='_blank'>查看实物中奖详细</a>";
			}elseif($info->iType==2){
					echo "<a href='/tool/LuckActivity/placepush/id/{$info->iActivityId}/time/{$key}/goodsId/{$info->iId}' target='_blank'>查看地推中奖</a>";
			}
			?>
			</td>
			<?php } }?>
			<td><?php echo $num;?></td>
		<tr class="even">
		<?php }?>
		</tbody>
	</table>
    </div>
</div>
