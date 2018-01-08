<?php
$this->breadcrumbs=array(
	'评论'=>array('index'),
	'管理',
);
?>
<div class="page-header">
	<table><tr><td><h1>管理评论</h1></td>
			<td><button type="button"  onclick="addMovieName();" style="width:100px">拉取电影名称</button>
			</td></tr></table>

</div>

<script type="text/javascript">
	function GetCheckbox(type){
		var data=new Array();
		$("input:checkbox[name='commentId[]']").each(function (){
			if($(this).is(':checked')){
				data.push($(this).val());
			}
		});
		if(data.length > 0){
			if(type == 0){
				var url = "/comment/deleteAll";
			}else{
				var url = "/comment/statusAll";
			}
			$.post(url,{'commentId[]':data}, function (data) {
				if (data=='ok') {
					if(type == 0){
						alert('删除成功！');
						$("input:checkbox[name='commentId[]']").each(function (){
							if($(this).is(':checked')){
								$(this).parent().parent().remove();
							}
						});
					}else{
						alert('审核成功！');
						$("input:checkbox[name='commentId[]']").each(function (){
							if($(this).is(':checked')){
								$(this).parent().parent().find("td").last().prev().html("已审核");
							}
						});
					}
				}
			});
		}else{
			if(type == 0){
				alert("请选择要删除的评论!");
			}else{
				alert("请选择要通过的评论!");
			}
		}
	}
	function addMovieName(){
		$.post("<?php echo $this->createUrl('comment/addMovieName')?>",{'type':'addMovieName'}, function (data) {
			if (data=='ok') {
				alert('电影名称拉取成功！部分影片不在当前所选城市内');
			}else{
				alert('程序遇到异常，请联系技术人员');
			}
		});
	}
</script>

<div class="row">
	<div class="col-xs-12">
		<?php $this->widget('application.components.WxGridView', array(
			'id'=>'comment-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
				array(
					'selectableRows' => 2,
					'class'=>'CCheckBoxColumn',
					'headerHtmlOptions' => array('width'=>'18px'),
					'checkBoxHtmlOptions' => array('name' => 'commentId[]','class'=>'myclass',), //复选框的 html 选项
				),
				array(
					'name' => 'id',
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'movieId',
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'score',
					'value' => 'Comment::model()->getScoreInfo($data->movieId,$data->ucid)',
					'filter' => '',
					'htmlOptions' => array(
						'width' => 60
				)
				),
				array(
					'name' => 'ucid',
					'type' => 'raw',
					'value' => 'BlackList::model()->getUcidStr($data->ucid)',
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'channelId',
					//'value' => 'Comment::model()->getchannelId($data->channelId)',
					'value'=>'$data->channelId == 3 ?"微信电影票":($data->channelId == 8?"IOS":($data->channelId==9?"安卓":"手Q"))',
					'filter'=> CHtml::activeDropDownList($model, 'channelId', (['' => '全部','3'=>'微信电影票','8'=>'IOS','9'=>'安卓','28'=>'手Q'])),
					'htmlOptions' => array(
						'width' => 80
					)
				),
				array(
					'name' => 'status',
					'filter'=> CHtml::activeDropDownList($model, 'status', (['' => '全部','1'=>'上线','0'=>'屏蔽'])),
					'value' => '$data->status == 1?"上线":($data->status==0?"屏蔽":"已删除")',
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'content',
					'value' => 'SensitiveWords::model()->isSensitiveWordInfo($data->content)',
					'type'=>'raw',
					'htmlOptions' => array(
							'width' => 380,
						),
				),
				array(
					'name' => 'favorCount',
					'htmlOptions' => array(
						'width' => 60
					)
				),
				array(
					'name' => 'replyCount',
					'htmlOptions' => array(
						'width' => 40
					)
				),
				array(
					'name' => 'updated',
					'type'=>'raw',
					'filter' => '',
					'value' => 'date("Ymd H:i:s", $data->updated)',
					'htmlOptions' => array(
						'width' => 80
					),
				),
				array(
					'name' => 'checkstatus',
//					'value' => 'Comment::model()->getCheckstatus($data->checkstatus)',
					'value' => '$data->checkstatus == 0?"未审核":($data->checkstatus==3?"含敏感词":"已审核")',
					'filter'=> CHtml::activeDropDownList($model, 'checkstatus', (['' => '全部', '0' => '未审核', '1' => '已审核', '3' => '含敏感词'])),
					'htmlOptions' => array(
						'width' => 80
					)
				),
				array(
					'header' => '操作',
					'class'=>'CButtonColumn',
					'headerHtmlOptions' => array('width'=>'80'),
					'template' => '{update} {delete} {addcomment} ',
					'buttons'=>array(
						'addcomment' => array(
							'label'=>'推荐',
							'url'=>'"/commentRecommend/update/".$data->id',
							'options' => array(
								'target' => '_blank',
							)
						),
					),
				),
			),
		)); ?>
	</div>
	<button type="button"  onclick="GetCheckbox(0);" style="width:76px">批量删除</button><button type="button"  onclick="GetCheckbox(1);" style="width:76px">批量通过</button>
</div>
