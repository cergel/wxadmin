<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('form', "
    function update_form() {
//         if ($('#DiscoveryBanner_iType :radio:checked').val() == '1') {
//             $('.form-group').hide();
		
//         } else if ($('#DiscoveryBanner_iType :radio:checked').val() == '2') {
//             $('.activity-only').hide();
//         } else {
//             $('.activity-only').hide();
//         }
 		var thisid = $('#DiscoveryBanner_iType :radio:checked').val();
		if(thisid){
			$('.form-group').hide();
			$('[isall=1]').show();
			$('[itype'+thisid+'='+thisid+']').show();
		}else{
			$('#DiscoveryBanner_iType_0').attr('checked','checked');
			$('#DiscoveryBanner_iType_0').click();
		}
		
    }

    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    $('#DiscoveryBanner_iType :radio').click(function () {
        update_form();
    });

    update_form();
	var isnew = '". $model->isNewRecord."';
	if(!isnew){
		$('#DiscoveryBanner_iType :radio').attr('disabled','disabled');
	}
 	

    // 编辑器数据绑定
    $('#discovery-banner-form').on('submit', function() {
    var html_content = $('#editor1').html();
    $('#DiscoveryBanner_sContent').val( html_content );
    });

    // 调用编辑器
    $('#editor1').ace_wysiwyg({
    toolbar:
    [
    'font',
    null,
    'fontSize',
    null,
    {name:'bold', className:'btn-info'},
    {name:'italic', className:'btn-info'},
    {name:'strikethrough', className:'btn-info'},
    {name:'underline', className:'btn-info'},
    null,
    {name:'insertunorderedlist', className:'btn-success'},
    {name:'insertorderedlist', className:'btn-success'},
    {name:'outdent', className:'btn-purple'},
    {name:'indent', className:'btn-purple'},
    null,
    {name:'justifyleft', className:'btn-primary'},
    {name:'justifycenter', className:'btn-primary'},
    {name:'justifyright', className:'btn-primary'},
    {name:'justifyfull', className:'btn-inverse'},
    null,
    {name:'createLink', className:'btn-pink'},
    {name:'unlink', className:'btn-pink'},
    //null,
    //{name:'insertImage', className:'btn-success'},
    null,
    'foreColor',
    null,
    {name:'undo', className:'btn-grey'},
    {name:'redo', className:'btn-grey'}
    ],
    'wysiwyg': {
    //fileUploadError: showErrorAlert
    }
    }).prev().addClass('wysiwyg-style2');

");
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'discovery-banner-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<style>
    #discoveryBanner_sContent_editor{
        height:200px;
        overflow:scroll;
        max-height:300px
    }
</style>
<script>
    //多图片上传
    $(function()
    {
        $('#add_file').click(function()
        {
            var obj =$("[sContent=textarea]");
            if(obj.length >=9){
					alert('最多只能上传9张图片');
					return;
                }
            
            var htmlStr ="<div>";
            	htmlStr += "<div class='col-xs-10'>";
            	htmlStr += "<input type='file' name='sContent[][img]' class='col-xs-5'/>";
            	htmlStr += "<span class='help-inline col-xs-5'>";
            	htmlStr += "<span class='middle'>最佳尺寸: 600px*316px，小于512Kb<a href='javascript:void(0);' do='del_file'>删除</a>。</span>";
            	htmlStr += "</span>";
            	htmlStr += "</div>";
            	htmlStr += "<div class='col-xs-9'>";
            	htmlStr += "<textarea id=sContent[] sContent='textarea'  class='col-xs-9' name=sContent[] cols='50' rows='3'></textarea>";
            	htmlStr += "</div>";
            	htmlStr += "</div>";
            $('[do=sContent]').append(htmlStr);
        })
        
        $("body").delegate('[do=del_file]','click',function(){
        	var the_a=$(this);
        	the_a.parent().parent().parent().parent().remove();
            
        });
        $('[do=del_file_content]').click(function()
        {
        	var the_a=$(this);
            var tid=$(this).attr("tid");
            var simg=$(this).attr("simg");
            var content=$(this).attr("scontent");
            $.post("<?php echo $this->createUrl('discoveryBanner/deleteimg')?>","id="+tid+"&simg="+simg+"",function(a){
                if(a==1){
                    the_a.parent().parent().remove();
                }
            })
            
        })
     });
</script>

<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
		<!-- 活动类型  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iType', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iType',DiscoveryBanner::getTypeList(), array('separator' => ' ')); ?>
            </div>
        </div>
        <?php if (!empty($model->iBannerID)){?>
        <input type="hidden" id="DiscoveryBanner[iType]" name="DiscoveryBanner[iType]" value=<?php echo $model->iType;?>>
        <?php }?>
        <!-- 标题 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>100,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 封面  -->
        <div class="form-group" itype1="1" itype2="2" itype4="4">
            <?php echo Chtml::label('封面', 'DiscoveryBanner_sPicture', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_discovery_banner/<?php echo date('Y-m-d', $model->iCreated) . '/' . $model->sPicture;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'sPicture',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 600px*316px，小于512Kb。</span>
                    </span>
                </div>
            </div>
        </div>
        <!-- 图片  -->
        <div class="form-group"  itype3="3">
            <?php echo Chtml::label('图片', 'sContent', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9" do="sContent">
            	<div>
                	<a href="javascript:void(0);" id="add_file">增加</a>
                </div>
            	<?php if (!empty($model->sContent) && $model->iType == 3){ 
            		$sContent =json_decode($model->sContent,true);
            		foreach ($sContent as $value){
            		//foreach (json_decode($model->sContent,true) as $key=>$vlaue){ ?>
            		<div sContent="textarea">
            		<div style="height:50px;" class="col-xs-10">
                        <img src="/uploads/app_discovery_banner/<?php echo date('Y-m-d', $model->iCreated) . '/' . $model->iBannerID.'/'.$value['img'];?>" height="50" />
                    </div>
                    <div class="col-xs-9">
						<?php echo $value['sContent']?>
						<a href="javascript:viod()" do="del_file_content" tid="<?php echo $model->iBannerID?>" simg="<?php echo $value['img'];?>" scontent="<?php echo $value['sContent'];?>">删除</a>
					</div>
					</div>
					<?php }?>
            	<?php } // } }?>
                <?php if ($model->isNewRecord) { ?>
                <div>
                    <div class="col-xs-10">
                    <input type="file" name="sContent[][img]" class="col-xs-5"/>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 600px*316px，小于512Kb<a href="javascript:viod()" do="del_file">删除</a>。</span>
                    </span>
                    </div>
                    <div class="col-xs-9">
						<textarea id="sContent[]" sContent="textarea"  class="col-xs-9" name="sContent[]" cols="50" rows="3"></textarea>
					</div>
				</div>
                <?php } ?>
            </div>
        </div>
        <!--
        * 跳转到影片详情页 wxmovie://filmdetail?movieid=xxxx
        * 跳转到影院列表页 wxmovie://cinemalist?movieid=xxxx
        * 跳转到影院详情页并指定影片 wxmovie://cinemafilm?cinemaid=xxxx&movieid=xxxx
        * 回到主界面 wxmovie://home
        -->
        <!-- 跳转链接 -->
        <div class="form-group" itype1="1" itype2="2">
            <?php echo Chtml::label('跳转链接', 'sLink', array('required' => true,'class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sLink',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        
        <!--视频 -->
        <div class="form-group" itype4="4">
         	<?php echo Chtml::label('视频', 'sVideo', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sVideo',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-10')); ?>
            </div>
        </div>
         <!-- 活动类型才有下面这俩 活动开始时间 -->
        <div class="form-group" itype1="1">
            <?php echo Chtml::label('活动开始时间', 'DiscoveryBanner_iStartAt', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iStartAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!-- 活动结束时间 -->
        <div class="form-group" itype1="1">
            <?php echo Chtml::label('活动结束时间', 'DiscoveryBanner_iEndAt', array('required' => true, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iEndAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        
        <!-- 前台显示时间 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iShowAt', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iShowAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        <!-- 前台结束时间 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iHideAt', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iHideAt',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
        
        <!-- 摘要  -->
        <div class="form-group" itype2="2">
            <?php echo $form->labelEx($model, 'sDescription', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sDescription',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <!-- 内容 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sContent', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php // echo $form->hiddenField($model,'sContent',array('rows'=>6, 'cols'=>50, 'class' => 'col-xs-10')); ?>
                <div class="row">
                    <div class="col-xs-10">
                        <div class="wysiwyg-editor" id="editor1"><?php echo $model->sContent;?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 来源 -->
        <div class="form-group" itype4="4">
            <?php echo $form->labelEx($model, 'sFrom', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sFrom',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-2')); ?>
            </div>
        </div>
        
        <!-- 自定义标签  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sTag', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTag',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-2')); ?>
            </div>
        </div>
        <!-- 分类 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iCategory', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->dropDownList($model,'iCategory',DiscoveryBanner::getCategoryList(), array('separator' => ' ', 'empty' => '请选择分类')); ?>
            </div>
        </div>
         <!-- 定向城市 -->
        <div class="form-group" isall="1">
            <?php echo CHtml::label('定向城市', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="col-xs-10">
                    <?php $this->widget('application.components.CitySelectorWidget',array(
                        'name' => 'cities',
                        'selectedCities' => $selectedCities
                    )); ?>
                </div>
            </div>
        </div>
        <!-- 定向渠道 -->
        <div class="form-group" isall="1">
            <?php echo CHtml::label('定向渠道', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->checkbox($model,'iAndroid',array()); ?>
                <?php echo $form->labelEx($model, 'iAndroid', array('class'=>'')); ?>
                <br />
                <?php echo $form->checkbox($model,'iIOS',array()); ?>
                <?php echo $form->labelEx($model, 'iIOS', array('class'=>'')); ?>
            </div>
        </div>
        
        <!-- 置顶  -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iTop', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iTop',array('0' => '否', '1' => '是'), array('separator' => ' ')); ?>
            </div>
        </div>
        <!-- 基础关注数 -->
        <div class="form-group" itype1="1">
            <?php echo $form->labelEx($model, 'iBaseCount', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'iBaseCount',array('class' => 'col-xs-1')); ?>
            </div>
        </div>
        
        <!-- 状态  ： 上线、预上线 -->
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'iStatus', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iStatus',array('1' => '上线', '0' => '预上线'), array('separator' => ' ')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo $form->labelEx($model, 'sShareContent', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShareContent',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group" isall="1">
            <?php echo Chtml::label('分享图片', 'sSharePic', array('required' => false, 'class'=>'col-sm-3 control-label no-padding-right'));?>
            <div class="col-sm-9">
                <?php if (!$model->isNewRecord) { ?>
                    <div style="height:200px;width:400px;">
                        <img src="/uploads/app_discovery_banner/<?php echo date('Y-m-d', $model->iCreated) . '/'  . $model->sSharePic;?>" height="200" />
                    </div>
                <?php } ?>
                <div class="col-xs-10">
                    <?php echo $form->fileField($model,'sSharePic',array('class' => 'col-xs-5'));?>
                    <span class="help-inline col-xs-5">
								<span class="middle">最佳尺寸: 600px*316px，小于512Kb。</span>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
					<?php echo $model->isNewRecord ? '创建' : '保存'; ?>
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
<script language="javascript">
  var isnew = '<?php echo $model->isNewRecord;?>';
//   if(isnew != '1'){
// 	  var thisredioid = $('#DiscoveryBanner_iType :radio:checked').val();
// 	  var input = $("#DiscoveryBanner_iType").find("input[type='radio']");
// 	  	input.attr("disabled","disabled");
// 	  	input.each(function(){
// 	  	   if($(this).val()==thisredioid){
// 	  	    	$(this).attr("checked",true);
// 	  	   }    
// 	  	  }
// 	  }
 
  //input.eq(1).attr("checked",true);
</script>
<?php $this->endWidget(); ?>
