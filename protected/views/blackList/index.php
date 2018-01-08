<?php
$this->breadcrumbs=array(
	'黑名单'=>array('index'),
	'管理',
);
?>
<div class="page-header">
<h1>黑名单管理</h1>
<a class="btn btn-success" href="/blackList/create">新增黑名单</a>
</div>

<script type="text/javascript">
function GetCheckbox(){
        var data=new Array();
        $("input:checkbox[name='uid[]']").each(function (){
                if($(this).is(':checked')){
                     data.push($(this).val());
                }
        });
        if(data.length > 0){
             url = "/blackList/deleteAll?ajax=comment-grid";
                $.post(url,{'uid[]':data}, function (data) {
                        if (data=='ok') {
                           alert('删除成功！');
                           $("input:checkbox[name='uid[]']").each(function (){
                               if($(this).is(':checked')){
                                       $(this).parent().parent().remove();
                               }
                           }); 
                      }else{
						alert("删除失败")
                      }
                });
        }else{
        	alert("请选择要删除的黑名单用户!");
        }
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
				'checkBoxHtmlOptions' => array('name' => 'uid[]','class'=>'myclass',), //复选框的 html 选项
		),
		array(
			'name' => 'uid',
            'htmlOptions' => array(
                'width' => 60
            )
		),
// 		array(
// 			'name' => 'create_name',
//             'htmlOptions' => array(
//                 'width' => 60
//             )
// 		),
		array(
				'name' => 'created',
				'value' => 'date("Y-m-d H:i:s", $data->created)',
				'htmlOptions' => array(
						'width' => 70
				)
		),
		array(
				'name' => 'stype',
				'value' => 'BlackList::model()->getStype($data->stype)',
				'filter'=> CHtml::activeDropDownList($model, 'stype', $model->getStype('all')),
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
				'name' => 'create_name',
				'htmlOptions' => array(
						'width' => 60
				)
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{delete}'
		),
	),
)); ?>
    </div>
     <button type="button"  onclick="GetCheckbox();" style="width:76px">批量删除</button>
</div>
