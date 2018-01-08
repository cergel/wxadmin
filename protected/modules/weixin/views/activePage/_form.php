<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    function update_form() {
 		var thisid = $('#ActivePage_iType :radio:checked').val();
		if(thisid){
			$('[do=demo]').hide();
			$('[itype'+thisid+'='+thisid+']').show();
			if($('#itype'+thisid).val())
				$('#ActivePage_sStyle').val($('#itype'+thisid).val());
		}else{
			$('#ActivePage_iType_0').attr('checked','checked');
			$('#ActivePage_iType_0').click();
		}

    }

    $('#ActivePage_iType :radio').click(function () {
        update_form();
    });
     $('.date-timepicker').datetimepicker({
            format:\"YYYY-MM-DD HH:mm:ss\"
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });

    update_form();
	var isnew = '". $model->isNewRecord."';
	if(!isnew){
		$('#ActivePage_iType :radio').attr('disabled','disabled');
	}

");

 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'active-page-form',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'focus'=>array($model,'sName'),
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
 <div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="row">
<script>
    $(function()
    {
        $('[do=add_filem_img]').click(function()
        {
            var obj =$("[do=typeContent]");
            if(obj.length >=8){
					alert('最多只能8部影片');
					return;
                }
            var htmlStr ="<tr>";
	            htmlStr += '<td><input type="text" name="sort[]" value=""  do="notnull"/></td>';
	            htmlStr +='<td><input type="text" do="typeContent" name="film[]" value=""  do="notnull"/></td>';
	    		htmlStr +='<td><input type="file" name="img[]" do="notnull"/></td>';
	    		htmlStr += '<td><a href="javascript:void(0)" do="del_filem_img">删除</a></td>';
	    		htmlStr += '</tr>';
    	
            $('#film_img_table').append(htmlStr);
        });
        
        $("body").delegate('[do=del_filem_img]','click',function(){
        	var the_a=$(this);
        	the_a.parent().parent().remove();
            
        });
        $("body").delegate('#submit','click',function(){
            //验证手机号
            var phone=$('#sDirectorPhone').val();
            if(phone.length==0){
                alert("手机号不能为空");
                return false;
            }


            if(isNaN(phone)){
                alert("请输入正确手机号");
                return false;
            }

            if(phone.length!=11){
                alert('手机号长度错误');
                return false;
            }
            if(phone.substr(0,1)!='1'){
                alert('请输入正确手机号');
                return false;
            }


            var thisid = $('#ActivePage_iType :radio:checked').val();
            if(thisid == 2) {
                $("[do=notnull]").each(function () {
                    if ($(this).val() == '') {
                        alert('影片相关所有项均为必填');
                        return false;
                    }
                })
            }
            var iResourceID = 0;
            $("[name='iResourceID[]']").each(function() {
                if($(this).is(':checked')){
                    iResourceID = 1;
                }
            })
            if(iResourceID == 0){
                alert('活动id为必传字段');
                return false;
            }else  return true;

        });
        
        $('[do=submit123]').click(function()
        {
        	var thisid = $('#ActivePage_iType :radio:checked').val();
        	if(thisid == 2)
        	$("[do=notnull]").each(function() {
            	if($(this).val() ==''){
                	alert('影片相关所有项均为必填');
                	return false;
            	}
         	})
            var iResourceID=0;
            $("[name=iResourceID[]]").each(function() {
                if($(this).is(':checked')){
                    iResourceID = 1;
                }
            })
            if(iResourceID == 0){
                alert('活动id为必传字段');
                return false;
            }else  return true;

         });
         
         $('[do=del_filem_img_file]').click(function()
        {
        	var the_a=$(this);
            var tid=$(this).attr("tid");
            var simg=$(this).attr("simg");
            $.post("<?php echo $this->createUrl('activePage/deleteimg')?>","id="+tid+"&simg="+simg+"",function(a){
                if(a==1){
                    the_a.parent().parent().remove();
                }
            })
            
        })
        
     });
</script>
        	<?php $type =  $model->iType=='1' ? '' : '_More';?>
            <div class="col-xs-<?php echo $model->isNewRecord ? '12' : '7';?>">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sDirector', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'sDirector',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sDirectorPhone', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'sDirectorPhone',array('id'=>'sDirectorPhone','size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        如有问题可以接受24h电话
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sName', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'sName',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'iTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-7">
                            <?php echo $form->textField($model, 'iTime', array('class' => 'form-control date-timepicker')); ?>
                            <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'iShowStartTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-7">
                            <?php echo $form->textField($model, 'iShowStartTime', array('class' => 'form-control date-timepicker')); ?>
                            <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'iShowEndTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-7">
                            <?php echo $form->textField($model, 'iShowEndTime', array('class' => 'form-control date-timepicker')); ?>
                            <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'iPreheatEndTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-7">
                            <?php echo $form->textField($model, 'iPreheatEndTime', array('class' => 'form-control date-timepicker')); ?>
                            <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'iEndTime', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <div class="input-group col-xs-7">
                            <?php echo $form->textField($model, 'iEndTime', array('class' => 'form-control date-timepicker')); ?>
                            <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo Chtml::label('大图', 'ActivePage_sPic', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-sm-9">
                        <?php if (!$model->isNewRecord) { ?>
                            <div style="height:200px;width:400px;">
                                <img src="<?php echo Yii::app()->params['active_page_new']['target_url'] .$type. '/' . $model->iActivePageID . '/images/' . $model->sPic;?>" height="200" />
                            </div>
                        <?php } ?>
                        <div class="col-xs-10">
                            <?php echo $form->fileField($model,'sPic',array('class' => 'col-xs-5'));?>
                            <span class="help-inline col-xs-5">
								<span class="middle">请上传小于512kb的图片文件</span>
							</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sRule', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textArea($model,'sRule',array('rows' => 5, 'class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo Chtml::label('活动ID', 'ActivePage_iActiveID', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="col-xs-12">
                                    <div class="form-group" style="margin-left:0;">
                                        <input type="text" id="activity-search-text" placeholder="请输入关键字或者活动ID" class="col-xs-5" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-sm btn-default" id="activity-search-button" type="button">
                                                搜索活动
                                            </button>
                                        </span>
                                    </div>
                            </div>
                        </div>
                        <div class="row" id="activity-box">
                            <div class="row" id="activities">
                                <?php if ($activities) { ?>
                                    <?php foreach($activities as $k => $activity) { ?>
                                    <div class="col-xs-3">
                                        <div>
                                            <p>
                                                <input type="checkbox" name="iResourceID[]" value="<?php echo $activity->iResourceID;?>" checked="checked" />
                                                ID:<?php echo $activity->iResourceID;?>
                                            </p>
                                            <p><?php echo $activity->sBonusName;?></p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="row" id="activity-search-result"></div>
                        </div>
                    </div>
                </div>
                <!-- 模板类型  -->
		        <div class="form-group" >
		            <?php echo $form->labelEx($model, 'iType', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
		            <div class="col-xs-9">
		                <?php echo $form->radioButtonList($model,'iType',['1'=>'单影片'], array('separator' => ' ')); ?>
		            </div>
		        </div>
		        <?php if (!$model->isNewRecord)echo $form->hiddenField($model,'iType');?>
                <div class="form-group" itype2="2" do='demo'>
                    <?php echo CHtml::label('影片相关', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <div class="col-xs-10">
                        <table  border=1 id="film_img_table">
	                        <tr>
	                        	<td width="40px">序号</td>
	                        	<td width="40px">影片ID</td>
	                        	<td width="160px">上传影片背景</td>
	                        	<td width="80px"><a href="javascript:void(0)" do="add_filem_img">+添加影片</a></td>
                        	</tr>
                        	<?php if (!empty($model->sContent) && $model->iType == '2'){
                        		$sContent =json_decode($model->sContent,true);
                        		foreach ($sContent as $value){
                        	?>
                        	<tr>
                        		<td><?php echo $value['order']?></td>
                        		<td><?php echo $value['movie_id']?></td>
                        		<td><img src="<?php echo Yii::app()->params['active_page_new']['target_url'] .$type. '/' . $model->iActivePageID . '/images/' . $value['img'];?>" height="100px" /></td>
                        		<td><a href="javascript:void(0)" tid="<?php echo $model->iActivePageID?>" simg="<?php echo $value['img'];?>"  do="del_filem_img_file" >删除</a></td>
                        	</tr>
                        	<?php }?>
                        	<?php }else {?>
                        	<tr>
                        		<td><input type="text" name="sort[]" value="1"  do="notnull"/></td>
                        		<td><input type="text" do="typeContent" name="film[]" value=""  do="notnull"/></td>
                        		<td><input type='file' name='img[]' do="notnull"/></td>
                        		<td><a href="javascript:void(0)" do="del_filem_img">删除</a></td>
                        	</tr>
                        	<?php }?>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="form-group" itype1="1" do='demo'>
                    <?php echo $form->labelEx($model,'iMovieId', array('required' => true,'class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model,'iMovieId',array('size'=>60,'maxlength'=>12,'class' => 'col-xs-10')); ?>
                    </div>
                </div>

                <div class="form-group" itype1="1" do='demo'>
                    <?php echo CHtml::label('置顶影城', '', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <div class="col-xs-10">
                            <?php $this->widget('application.components.CinemaSelectorWidget',array(
                                'name' => 'cinemas',
                                'selectedCinemas' => $selectedCinemas
                            )); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo Chtml::label('分享图片', 'sSharePic', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
                    <div class="col-xs-9">
                        <?php if (!$model->isNewRecord) { ?>
                            <div style="height:200px;width:400px;">
                                <img src="<?php echo Yii::app()->params['active_page_new']['target_url'] .$type. '/' . $model->iActivePageID . '/images/' . $model->sSharePic;?>" height="200" />
                            </div>
                        <?php } ?>
                        <div class="col-xs-10">
                            <?php echo $form->fileField($model,'sSharePic', array('class'=>'col-xs-5'));?>
                            <span class="help-inline col-xs-5">
								<span class="middle">请上传小于512kb的图片文件</span>
							</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sShareTitle', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textField($model,'sShareTitle',array('class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sShareContent', array('class'=>'col-xs-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->textField($model,'sShareContent',array('class' => 'col-xs-10')); ?>
                    </div>
                </div>
                <!-- 定向渠道 -->
		        <div class="form-group">
		            <?php echo CHtml::label('生效渠道', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
		            <div class="col-xs-9">
		                <?php echo $form->checkbox($model,'iwx',[]); ?>
		                <?php echo $form->labelEx($model, 'iwx', array('class'=>'')); ?>
		                <br />
		                <?php echo $form->checkbox($model,'iqq',[]); ?>
		                <?php echo $form->labelEx($model, 'iqq', array('class'=>'')); ?>
		                <br />
		                <?php echo $form->checkbox($model,'imobile',[]); ?>
		                <?php echo $form->labelEx($model, 'imobile', array('class'=>'')); ?>
		            </div>
		        </div>
                <!-- 广告位 -->
                <div class="form-group">
                    <?php echo CHtml::label('显示广告', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-xs-9">
                        <?php echo $form->checkbox($model,'iAppWill',[]); ?>
                        <?php echo $form->labelEx($model, 'iAppWill', array('class'=>'')); ?>
                    </div>
                </div>
                <!-- 为了替换css -->
                <input type="hidden" id="itype1" name="itype1" value="<?php echo $model->isNewRecord ? '':null;?>">
                <input type="hidden" id="itype2" name="itype2" value="<?php echo $model->isNewRecord ? '':null;?>">
            </div>
            <?php if (!$model->isNewRecord) {?>
            <!-- 这里是预览窗口 -->
            <div class="col-xs-5">
                <div class="preview-out-box">
                    <div class="preview-in-box">
                        <div class="preview-in-box-title">
                            &nbsp;<i class="ace-icon glyphicon glyphicon-remove"></i>&nbsp;|&nbsp;<span id="preview-title"><?php echo $model->sTitle?$model->sTitle:'页面标题';?></span>
                        </div>
                        <iframe id="preview-iframe" src="<?php echo Yii::app()->params['active_page_new']['target_url'] .$type. '/' . $model->iActivePageID . '/index.html';?>"></iframe>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="clearfix form-actions">
            <div class="col-xs-offset-3 col-xs-9">
                <button class="btn btn-info" type="submit" id="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    <?php echo $model->isNewRecord ? '创建' : '保存' ; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
