<?php
$this->breadcrumbs=array(
	'CMS评论'=>array('index'),
	'管理',
);
?>
<div class="page-header">
	管理CMS评论
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
				var url = "/cmsComment/deleteAll";
			}else{
				var url = "/cmsComment/statusAll";
			}
			$.post(url,{'commentId[]':data}, function (data) {
				if (data=='ok') {
					if(type == 0){
						alert('删除成功！');
						$("input:checkbox[name='commentId[]']").each(function (){
							if($(this).is(':checked')){
								//$(this).parent().parent().remove();
								$(this).parent().parent().find("td[dotype=status]").html("屏蔽");
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
	$(function () {
		$("body").delegate('[typedel=del]','click',function(){
			var commentId = $(this).attr("href");
			var obj = $(this);
			var url = "/cmsComment/delete/"+commentId;
			$.post(url,{'type':'addMovieName'}, function (data) {
				if (data=='1') {
					obj.parent().parent().find("td[dotype=status]").html("屏蔽");
				}else{
					alert(data);
				}
			});
			return false;

		});
	});
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
						'width' => 60
					)
				),
				array(
					'name' => 'a_id',
					'htmlOptions' => array(
						'width' => 60
					)
				),
				array(
					'name' => 'a_info',
					'filter'=>'',
					'value' => 'ActiveCms::model()->getCmsTitle($data->a_id)',
					'htmlOptions' => array(
						'width' => 150
					)
				),
				array(
					'name' => 'open_id',
					'type' => 'raw',
					'htmlOptions' => array(
						'width' => 80
					)
				),
				array(
					'name' => 'channel_id',
					'value' => 'CmsComment::model()->getchannelId($data->channel_id)',
					'filter'=> CHtml::activeDropDownList($model, 'channel_id', (['' => '全部','3'=>'微信电影票','8'=>'IOS','9'=>'安卓','28'=>'手Q'])),
					'htmlOptions' => array(
						'width' => 80,
					)
				),
				array(
					'name' => 'status',
					'filter'=> CHtml::activeDropDownList($model, 'status', (['' => '全部','1'=>'上线','0'=>'屏蔽'])),
					'value' => '$data->status?"上线":"屏蔽"',
					'htmlOptions' => array(
						'width' => 40,
						'dotype'=>'status',
					)
				),
				array(
				'name' => 'content',
				'value' => 'SensitiveWords::model()->isSensitiveWordInfo($data->content)',
				'type'=>'raw',
				'htmlOptions' => array(
						'width' => 150,
					),
				),
				array(
					'name' => 'favor_count',
					'htmlOptions' => array(
						'width' => 60
					)
				),
				array(
					'name' => 'created',
					'type'=>'raw',
					'value' => 'date("Ymd H:i:s", $data->created)',
					'filter'=>'',
					'htmlOptions' => array(
						'width' => 100
					),
				),
				array(
					'name' => 'checkstatus',
					'value' => 'CmsComment::model()->getCheckstatus($data->checkstatus)',
					'filter'=> CHtml::activeDropDownList($model, 'checkstatus', ($model->getCheckstatus('all'))),
					'htmlOptions' => array(
						'width' => 80
					)
				),
				//先临时去掉
//				array(
//					'header' => '操作',
//					'class'=>'CButtonColumn',
//					'headerHtmlOptions' => array('width'=>'80'),
//					'template' => '{update} {delcomment} ',
//					'buttons'=>array(
//						'delcomment' => array(
//							'label'=>'屏蔽',
//							'url'=>'$data->id',
//							'options' => array(
//								'typeDel'=>'del',
//							)
//						),
//					),
//				),
			),
		)); ?>
	</div>
	<button type="button"  onclick="GetCheckbox(0);" style="width:76px">批量删除</button><button type="button"  onclick="GetCheckbox(1);" style="width:76px">批量通过</button>
</div>
