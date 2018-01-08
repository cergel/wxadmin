<?php 
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'videomodule-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); 
$Platform_list =  Yii::app()->params['app_module']['Platform'];
?>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Module_Title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-9">
            <?php echo $form->textField($model,'Module_Title',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-10','placeholder'=> '建议不要超过10个字')); ?>
          </div>

        </div>
		
		<div class="form-group" >
			 <?php echo $form->labelEx($model, 'Status', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
			 <div class="col-sm-9">
			  <?php 
			   $model->Status = 1;
			  echo $form->radioButtonList($model,'Status',array('1'=>'上线','0'=>'下线'),array('separator'=>'&nbsp;','labelOptions'=>array('class'=>'radiolabel')));
			 ?>
			 </div>
		 </div> 
		  
		<div class="form-group">
			 <?php echo CHtml::label('投放日期<span class="required">*</span>','platform', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
		 <div class="col-sm-6">
				<label class="col-sm-1">自</label>
				<div class="col-sm-5">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',[
					'model'=>$model, 'attribute'=>'Start',
					'language'=>'zh_cn',
					'name'=>'Start',
						'options'=>[
							'dateFormat'=>'yy-mm-dd',
							'changeMonth'=>true,
							'changeYear'=>true,
							'yearRange'=>'-2:+2',
							'defaultDate'=>'+0'
						],
					])?>
					</div>
				<label class="col-sm-1">至</label>
				<div class="col-sm-5">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',[
					'model'=>$model, 'attribute'=>'End',
					'language'=>'zh_cn',
					'name'=>'End',
						'options'=>[
							'dateFormat'=>'yy-mm-dd',
							'changeMonth'=>true,
							'changeYear'=>true,
							'yearRange'=>'-2:+2',
							'defaultDate'=>'+0'
						],
					])?>
				</div>
          </div>
		  <div class="col-sm-4">
           
          </div>
		</div>
		<div class="form-group">
			 <?php echo CHtml::label('应用平台<span class="required">*</span>','VideoModule[Platform]', array('class'=>'col-sm-3 control-label no-padding-right required')); ?>
		 <div class="col-sm-9">
            <?php echo CHtml::checkBoxList('VideoModule[Platform]',$Platform_list,$Platform_list,array('labelOptions'=>array('class'=>'checkbox-inline col-xs-1'),'template'=>"{beginLabel} {input} {labelTitle}  {endLabel}",'separator'=>'&nbsp;')); ?>
          </div>
		</div>
    	
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Video1_Movie', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-6">
            <?php echo $form->textField($model,'Video1_Movie',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-10 MovieId','placeholder'=> '请输入影片1的ID')); ?>
          </div>
		  <div class="col-sm-2">
		  <button class="btn btn-success search_moive" target="MovieInfo1">
            <i class="glyphicon glyphicon-search"></i>
            查询电影
          </button>
		  </div>
        </div> 
		<!--影片一详细列表-->
		<div class="form-group MdouleDetailBox" id="MovieInfo1" style="display:none">
			<div class="col-sm-9 col-sm-offset-3">				
				<div class="col-xs-10 panel panel-warning">
				  <div class="panel-body">
						<div class="col-xs-6">
							 <div class="form-group">
								 <div class="col-xs-12 panel panel-warning VideoList">
									<div class="panel-heading">
									<h3 class="panel-title">预告片列表</h3>
									</div>
									<div class="panel-body">
										<table  class="table table-bordered table-hover table-condensed">
										</table>
									</div>
								 </div>
							</div>
							<div class="form-group">
							 <?php echo $form->labelEx($model, 'Video1_Title', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
							 <?php echo $form->textField($model,'Video1_Title',array('size'=>200,'maxlength'=>200,'class' => 'field_text col-xs-9')); ?>
							 </div>
							 <div class="form-group">
							 <?php echo $form->labelEx($model, 'Video1_Pic', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
							 <?php echo $form->fileField($model,'Video1_Pic',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-9')); ?>
							 </div>
							 <div class="form-group">
							 <?php echo $form->labelEx($model, 'Video1_Vid', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
							 <?php echo $form->textField($model,'Video1_Vid',array('size'=>200,'maxlength'=>200,'class' => 'field_vid col-xs-9')); ?>
							 </div>
							 
						</div>
						<div class="col-xs-6 row MovieInfoArea">
						<ul class="col-xs-6">
							<li><label type="name">片名</label>:<span></span></li>
							<li><label type="director">导演</label>:<span></span></li>
							<li><label type="actor">演员</label>:<span></span></li>
							<li><label type="date">上映日期</label>:<span></span></li>
							<li><label type="country">国家</label>:<span></span></li>
							<li><label type="tags">标签</label>:<span></span></li>
							<li><label type="version">版本</label>:<span></span></li>
							<li><label type="longs">时长</label>:<span></span></li>
						</ul>
						<div class="col-xs-5 poster text-center">
							<img src="#" class="img-thumbnail">
						</div>
						</div>
						<div class="col-xs-6 text-center VideoArea" style="display:none">
						</div>
				  </div>
				</div>
			</div>
		</div>
		<!--列表完-->
		
		<div class="form-group" >
		  <?php echo $form->labelEx($model, 'Video2_Movie', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
          <div class="col-sm-6">
            <?php echo $form->textField($model,'Video2_Movie',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-10 MovieId','placeholder'=> '请输入影片2的ID')); ?>
          </div>
		  <div class="col-sm-2">
		  <button class="btn btn-success search_moive" target="MovieInfo2">
            <i class="glyphicon glyphicon-search"></i>
            查询电影
          </button>
		  </div>
        </div> 
				<!--影片二详细列表-->
		<div class="form-group MdouleDetailBox" id="MovieInfo2" style="display:none">
			<div class="col-sm-9 col-sm-offset-3">				
				<div class="col-xs-10 panel panel-warning">
				  <div class="panel-body">
						<div class="col-xs-6">
							 <div class="form-group">
								 <div class="col-xs-12 panel panel-warning VideoList">
									<div class="panel-heading">
									<h3 class="panel-title">预告片列表</h3>
									</div>
									<div class="panel-body">
										<table  class="table table-bordered table-hover table-condensed">
										</table>
									</div>
								 </div>
							</div>
							<div class="form-group">
							 <?php echo $form->labelEx($model, 'Video2_Title', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
							 <?php echo $form->textField($model,'Video2_Title',array('size'=>200,'maxlength'=>200,'class' => 'field_text col-xs-9')); ?>
							 </div>
							 <div class="form-group">
							 <?php echo $form->labelEx($model, 'Video2_Pic', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
							 <?php echo $form->fileField($model,'Video2_Pic',array('size'=>200,'maxlength'=>200,'class' => 'col-xs-9')); ?>
							 </div>
							 <div class="form-group">
							 <?php echo $form->labelEx($model, 'Video2_Vid', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
							 <?php echo $form->textField($model,'Video2_Vid',array('size'=>200,'maxlength'=>200,'class' => 'field_vid col-xs-9')); ?>
							 </div>
							 
						</div>
						<div class="col-xs-6 row MovieInfoArea">
						<ul class="col-xs-6">
							<li><label type="name">片名</label>:<span></span></li>
							<li><label type="director">导演</label>:<span></span></li>
							<li><label type="actor">演员</label>:<span></span></li>
							<li><label type="date">上映日期</label>:<span></span></li>
							<li><label type="country">国家</label>:<span></span></li>
							<li><label type="tags">标签</label>:<span></span></li>
							<li><label type="version">版本</label>:<span></span></li>
							<li><label type="longs">时长</label>:<span></span></li>
						</ul>
						<div class="col-xs-5 poster text-center">
							<img src="#" class="img-thumbnail">
						</div>
						</div>
						<div class="col-xs-6 text-center VideoArea" style="display:none">
						</div>
				  </div>
				</div>
			</div>
		</div>
		<!--列表完-->
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
					创建
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
    </div>
</div>
<?php 
Yii::app()->clientScript->registerScript('form', "
	var VideoModule = {
		'Video_preview':function(obj){
			vid = $(obj).parents('tr').find('.Vid').text();
			$(obj).parents('.panel-body').find('.VideoArea').html('<embed src=\"http://static.video.qq.com/TPout.swf?auto=1&vid='+vid+'\" quality=\"high\" width=\"480\" height=\"320\" align=\"middle\" allowScriptAccess=\"sameDomain\" allowFullscreen=\"true\" type=\"application/x-shockwave-flash\"></embed>');
			$(obj).parents('.panel-body').find('.VideoArea').show();
			$(obj).parents('.panel-body').find('.MovieInfoArea').hide();
		},
		'Video_use':function(obj){
			vid = $(obj).parents('tr').find('.Vid').text();
			text = $(obj).parents('tr').find('.Text').text();
			$(obj).parents('.panel-body').find('.field_vid').val(vid);
			$(obj).parents('.panel-body').find('.field_text').val(text);
			$(obj).parents('.panel-body').find('.VideoArea').hide();
			$(obj).parents('.panel-body').find('.MovieInfoArea').show();
			$(obj).parents('.MdouleDetailBox').find('.VideoList').slideToggle();
		},
		'search_moive':function(obj){
			movieId = $(obj).parents('.form-group').find('input[class*=\"MovieId\"]').val();
			if(movieId == ''){
				alert('请输入影片ID');
				return false;
			}
			$.ajax({
				'url':'/app/videomodule/searchmoive',
				'dataType':'json',
				'data':{'MoiveId':movieId},
				'typeString':'get',
				'success':function(msg){
					if(msg.ret != 0){
						alert('检索电影失败');
						return false;
					}
					VideoModule.set_info(obj,msg.data);
				}
			})
		},
		'set_info':function(obj,data){
			//定位信息框
			target = $(obj).attr('target');
			//显示信息框
			$('#'+target).slideDown();
			//清空之前输入框残留信息
			$('#'+target).find('input').val('');
			//填充影片信息
			list = $('#'+target).find('.MovieInfoArea').find('li');
			list.each(function(k,v){
				type = $(v).find('label').attr('type');
				$(v).find('span').text(data[type]);
			});
			//填充海报图片
			$('#'+target).find('.MovieInfoArea > .poster > img').attr('src',data['poster_url']);
			//填充预告片地址
			$('#'+target).find('.VideoList').slideDown();
			$('#'+target).find('table').empty();
			$('#'+target).find('table').append('<tr><th class=\"col-xs-2\">预告片</th><th class=\"col-xs-6\">介绍</th><th class=\"col-xs-4\">操作</th></tr>');
			
			if(data['videos'].length < 1){
				$('#'+target).find('.VideoList').find('table').html('<tr><td class=\"text-center\" colspan=\"3\">暂无预告片，请自行填写相关信息</td></tr>');
			}
			$.each(data['videos'],function(k,v){
				$('#'+target).find('table').append('<tr><td class=\"Vid\">'+v['vid']+'</td><td class=\"Text\">'+v['tt']+'</td><td><button class=\"btn btn-xs btn-primary Video_preview\"><i class=\"glyphicon glyphicon-play\"></i>预览</button><button class=\"btn btn-xs btn-success Video_use\"><i class=\"glyphicon glyphicon-ok\"></i>选择</button></td></tr>');
			});
			return false;
		}
	};
	$('.search_moive').bind('click',function(){
		VideoModule.search_moive(this);
		return false;
	});
  
	$( document ).on( 'click', '.Video_use', function() {
		VideoModule.Video_use(this);
		return false;
	});	
	$( document ).on( 'click', '.Video_preview', function() {
		VideoModule.Video_preview(this);
		return false;
	});

	
");


$this->endWidget(); ?>
