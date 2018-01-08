<?php
$this->breadcrumbs=array(
	'图片'=>array('index'),
	'管理',
);
?>
<div class="page-header">
    <h1>管理图片</h1>
    <a class="btn btn-success" href="/moviePoster/create">创建影片</a>
</div>

<script type="text/javascript">
function GetCheckbox(type){
        var data=new Array();
        $("input:checkbox[name='moviePostTemp[]']").each(function (){
                if($(this).is(':checked')){
                     data.push($(this).val());
                }
        });
        if(data.length > 0){
            if(type == 0){
                //批量删除
            	var url = "/moviePosterTemp/deleteAll";
            }else{
                //批量审核通过
            	var url = "/moviePosterTemp/updateAll";
            }
            
                $.post(url,{'moviePostTemp[]':data}, function (data) {
                        if (data=='ok') {
                        	 if(type == 0){
                                alert('删除成功！');
                                $("input:checkbox[name='moviePostTemp[]']").each(function (){
                                    if($(this).is(':checked')){
                                            $(this).parent().parent().remove();
                                    }
                            	}); 
                        	 }else{
                        		 alert('审核成功！');
                        		 $("input:checkbox[name='moviePostTemp[]']").each(function (){
                                     if($(this).is(':checked')){
                                    	 $(this).parent().parent().remove();
                                          //  $(this).parent().parent().find("td").last().prev().html("已审核");
                                     }
                             	});
                              }
                      }
                });
        }else{
        	 if(type == 0){
 				alert("请选择要删除的图片!");
             }else{
             	alert("请选择要通过的图片!");
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
	'id'=>'movie-from',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
				'selectableRows' => 2,
				'class'=>'CCheckBoxColumn',
				'headerHtmlOptions' => array('width'=>'18px'),
				'checkBoxHtmlOptions' => array('name' => 'moviePostTemp[]','class'=>'myclass',), //复选框的 html 选项
		),
		array(
			'name' => 'id',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
				'name' => 'movie_id',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
				'name' => 'poster_type',
				'type' => 'raw',
				'value' => 'MoviePoster::model()->getPoster($data->poster_type)',
				'htmlOptions' => array(
						'width' => 100
				)
		),
		array(
			'name' => 'source_type',
			'value'=>'$data->source_type=="1"?"微影新增":($data->source_type=="2"?"灵思新增":"微影删除")',
			'filter'=> CHtml::activeDropDownList($model, 'source_type', (['' => '全部','1'=>'微影新增','2'=>'灵思新增','3'=>'微影删除',])),
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'url',
			'type' => 'raw',
			'value' => 'MoviePoster::model()->getImgUrl($data->id,2)',
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
			'name' => 'status',
			'value'=>'$data->status=="1"?"未审核":($data->source_type=="2"?"审核未通过":"审核通过")',
				'filter'=> CHtml::activeDropDownList($model, 'status', (['' => '全部','1'=>'未审核','2'=>'审核未通过','3'=>'审核通过',])),
            'htmlOptions' => array(
                'width' => 80
            )
		),
		array(
            'header' => '操作',
			'class'=>'CButtonColumn',
            'headerHtmlOptions' => array('width'=>'80'),
            'template' => '{auto} {delete}',
				'buttons'=>array(
						'auto' => array(
								'label'=>'审核',
								'url'=>'"/moviePosterTemp/update/".$data->id',
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
