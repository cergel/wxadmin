<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'luck-activity-form',
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('class' => 'form-horizontal')
));

Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerCssFile("/assets/css/uploadify.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.uploadify.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/bootstrap-wysiwyg.min.js", CClientScript::POS_END); 
Yii::app()->clientScript->registerScript('form', ""); //留着吧、删了js就不行、不查了、
?>
<script>
$(function() {

	//初始化
	 $('.date-timepicker').datetimepicker({
	        format:"YYYY-MM-DD HH:mm:ss"
	    }).next().on(ace.click_event, function(){
	        $(this).prev().focus();
	    });
		update_form();
		function update_form(){
			$('[isshow=2]').hide();
			$('[isshow=3]').hide(); //普奖
			var ticketsid = $('#LuckActivity_iTickets').is(':checked');
			var status    = $('#LuckActivity_iGeneral').val();
			if(ticketsid){
				$('[isshow=2]').show();	
			}
			if(status==1){
				
				$('[isshow=3]').show();
			}
			
			//是否增加普奖
			$('.iGeneralCheck').find('option').remove();
			$('.luck_goods tr').each(function(index,element){
				var selectData = ''; 
				if(index>0){
					optionData =  $(this).children().children().val();
					var len =$(this).children().children().attr('puval');
					var ispu=$(this).children().children().attr('ispu');
					if(ispu==1){
						selectData = '<option value="'+len+'" selected="selected">'+optionData+'</option>';
					}else{
						selectData = '<option value="'+len+'">'+optionData+'</option>';
					}
					
					
					$('.iGeneralCheck').append(selectData);
				}
			});
			
		}
		$('#LuckActivity_iUpdateTime :radio').click(function () {
	        update_form();
	    });
		$('#LuckActivity_iGeneral').change(function(){
			update_form();
		});
		$('#LuckActivity_iTickets').click(function(){
			update_form();
		});	





	
imageeach();
	function imageeach(){
		$(".picUpload").each(function(){
			//更新普选二级下拉框
			$(this).parent().prev().find('input').blur(function(){
				update_form();
			});
			var target  = $("."+$(this).attr("target"));
			$(this).uploadify({
				'formData'     : {
					'timestamp' : '<?php echo 333;?>',
					'token'     : '<?php echo md5('unique_salt' . 333);?>'
				},
				'swf'      : '/assets/js/uploadify.swf',
				'uploader' : '/tool/LuckActivity/uploadimage',
				'onUploadSuccess' : function(file, data, response) {
					data = eval("("+data+")");
					target.attr("value",data.name);
					data = '/uploads/luck/'+data.name;
					target.css("background-image","url("+data+")");
					target.attr('src',data);
		        }
			});
			
		});
	}

//以下为增加奖项相关
  $('[do=add_goods]').click(function(){
	var len = $('.luck_goods tr').length;
	if(len>=12){
		alert('奖项最多只能增加12个');
		return false;
	}
	var goodsData = "<tr class='addLuck_"+len+"'>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][sPrizeName]' puval="+len+"></td>";
  		goodsData+= "<td><input type='hidden' value='' name='goods["+len+"][sImages]'  class='goods-cover_"+len+"'><img src='' class='goods-cover_"+len+"' width='100px;' height='100px;'><input type='button' value='上传图片' class='picUpload' target='goods-cover_"+len+"' id='btn_cover_goods_"+len+"'></td>";
  		goodsData+= "<td><input type='hidden' value='' name='goods["+len+"][sFrontcoverImage]' class='frontcover-cover_"+len+"'><img src='' class='frontcover-cover_"+len+"' width='100px;' height='100px;'><input type='button' value='上传图片' class='picUpload' target='frontcover-cover_"+len+"' id='frontcover-cover_"+len+"'></td>";
  		goodsData+= "<td>";
	  		goodsData+= "<select name='goods["+len+"][iType]' class='goodsChange'>";
	  		goodsData+= "<option value='0'>红包</option>";
	  		goodsData+= "<option value='1'>实物</option>";
	  		goodsData+= "<option value='2'>地推奖品</option>";
	  		goodsData+= "</select>";
	  	goodsData+= "</td>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][iGalleryNum]' style='width:30px;'></td>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][sKudoName]'  style='width:120px;'></td>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][iBonusId]' style='width:70px;'>";
  		goodsData+= "<input type='text' name='goods["+len+"][iMoney]' value='' style='width:40px;'><span>元</span>";
  		goodsData+= "</td>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][iProbability]' style='width:70px;'></td>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][iPeopleStint]' style='width:70px;'></td>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][iDayNum]' style='width:70px;'></td>";
  		goodsData+= "<td><input type='text' name='goods["+len+"][iGoodsCount]' style='width:70px;'></td>";
  		goodsData+= "<td><input type='button' value='删除' do='del_goods'></td>";
  		goodsData+= "</tr>";
  		$('.luck_goods').append(goodsData);
  		imageeach();
  });
  
  $("body").delegate('[do=del_goods]','click',function(){
		$(this).parent().parent().remove();
		update_form();
	});
	
  $("body").delegate('.goodsChange','change',function(){
		val = $(this).val();
		if(val != 0){
			$(this).parent().next().next().next().children().prop('type','hidden');
			$(this).parent().next().next().next().find('span').remove();
		}else{
			$(this).parent().next().next().next().children().prop('type','text');
			var appdata = "<span>元</span>";
			$(this).parent().next().next().next().append(appdata);
		}
	});
});



</script>
<style>
	.luck{
		height:100%;width:100%;
		background-color:#DDD;
	}
	.luck-title table tr{
		width:100%;
	}
	.luck-title table tr td{
		width:200px;
	}
</style>
<div class="row">
    <div class="col-xs-12">
	<?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iActivityType', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
            
                <?php echo $form->dropDownList($model,'iActivityType',LuckActivity::getActivityType(), array('separator' => ' ')); ?>
                
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sRule', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sRule',array('rows' => 5, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sDescription', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sDescription',array('rows' => 5, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iStatusTime', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9 ">
            <div class="input-group col-xs-3">
                <?php echo $form->textField($model,'iStatusTime',array('class' => 'form-control date-timepicker')); ?>
            </div>
            </div>
             <?php echo $form->labelEx($model, 'iEndTime', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9 ">
            <div class="input-group col-xs-3">
                <?php echo $form->textField($model,'iEndTime',array('class' => 'form-control date-timepicker')); ?>
            </div>
            </div>
            
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iDayNum', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-1">
                <?php echo $form->textField($model,'iDayNum',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        
        <!-- 抽奖次数更新时间 点击自定义弹出选择时间框 -->
         <div class="form-group">
            <?php echo $form->labelEx($model, 'iUpdateTime', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iUpdateTime',LuckActivity::getUpdateTime(), array('separator' => ' ')); ?>
            </div>
        </div>
        
        
        <!-- 本次活动中奖次数 
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iLotteryNum', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-1">
                <?php echo $form->textField($model,'iLotteryNum',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
         -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sGaCode', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sGaCode',array('size'=>60,'maxlength'=>500,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        
        
        
        <!-- 此处为增加奖项 -->
         <div class="form-group">
        	<div class="col-sm-9 luck">
        		<div class="luck-title">
        			<table  class="luck_goods">
        				<tr>
        					<td>奖项</td>
        					<td>奖品图片</td>
        					<td>封面图片</td>
        					<td>奖品类型</td>
        					<td>展示个数</td>
        					<td>奖品名称</td>
        					<td>虚拟ID</td>
        					<td>中奖概率</td>
        					<td>每人领取上限</td>
        					<td>每日发放数量</td>
        					<td>奖品总量</td>
        				</tr>
        				
        				<?php if(!empty($goodsList)){ ?>
        					<?php foreach($goodsList as $key=>$info){ ?>
	        					<tr class='addLuck_<?php echo $key; ?>'>
							  	<td><input value="<?php echo $info->sPrizeName;?>" type='text' name='goods[<?php echo $key; ?>][sPrizeName]'  puval="<?php echo $key;?>" ispu="<?php echo $info->iGeneral; ?>"></td>
							  	<td><input type='hidden' value='<?php echo $info->sImages; ?>' name='goods[<?php echo $key; ?>][sImages]'  class='goods-cover_<?php echo $key; ?>'><img src='/uploads/luck/<?php echo $info->sImages;?>' class='goods-cover_<?php echo $key; ?>' width='100px;' height='100px;'><input type='button' value='上传图片' class='picUpload' target='goods-cover_<?php echo $key; ?>' id='btn_cover_goods_<?php echo $key; ?>'></td>
							  	<td><input type='hidden' value='<?php echo $info->sFrontcoverImage; ?>' name='goods[<?php echo $key; ?>][sFrontcoverImage]'  class='frontcover-cover_<?php echo $key; ?>'><img src='/uploads/luck/<?php echo $info->sFrontcoverImage;?>' class='frontcover-cover_<?php echo $key; ?>' width='100px;' height='100px;'><input type='button' value='上传图片' class='picUpload' target='frontcover-cover_<?php echo $key; ?>' id='frontcover-cover_<?php echo $key; ?>'></td>
							  	<td>
							  		<select name='goods[<?php echo $key; ?>][iType]'  class='goodsChange'>
							  			<option value='0' <?php if($info->iType==0){ echo 'selected'; }?>>红包</option>
							  			<option value='1' <?php if($info->iType==1){ echo 'selected'; }?>>实物</option>
							  			<option value='2' <?php if($info->iType==2){ echo 'selected'; }?>>地推奖品</option>
							  		</select>
							  	</td>
							  	<td style="width:6%px;"><input type="text" name="goods[<?php echo $key;?>][iGalleryNum]" value="<?php echo $info->iGalleryNum;?>" style="width:30px;"></td>
							  	<td><input value="<?php echo $info->sKudoName;?>" type='text' name='goods[<?php echo $key; ?>][sKudoName]' style="width:100px;"></td>
							  	<td>
							  		<input value="<?php echo $info->iBonusId;?>" type='<?php if($info->iType != 0 ){echo "hidden"; }else{ echo "text"; }?>' name='goods[<?php echo $key; ?>][iBonusId]'  style='width:70px;'>
							  		<input type="<?php if($info->iType != 0 ){echo "hidden"; }else{ echo "text"; }?>" name="goods[<?php echo $key; ?>][iMoney]" value="<?php echo $info->iMoney; ?>" style="width:40px;"><?php if($info->iType == 0 ){echo "<span>元</span>"; }?>
							  	</td>
							  	<td><input value="<?php echo $info->iProbability;?>" type='text' name='goods[<?php echo $key; ?>][iProbability]' style='width:70px;'></td>
							  	<td><input value="<?php echo $info->iPeopleStint;?>" type='text' name='goods[<?php echo $key; ?>][iPeopleStint]' style='width:70px;'></td>
							  	<td><input value="<?php echo $info->iDayNum;?>" type='text' name='goods[<?php echo $key; ?>][iDayNum]' style='width:70px;'></td>
							  	<td><input value="<?php echo $info->iGoodsCount;?>" type='text' name='goods[<?php echo $key; ?>][iGoodsCount]' style='width:70px;'></td>
							  	<td><input value="<?php echo $info->iId;?>" type="hidden" name="goods[<?php echo $key; ?>][iId]"></td>
							  	<td><input type='button' value='删除' do="del_goods"></td>
							  	</tr>
        					<?php } ?>
        				<?php } ?>
        				
        				
        				<input type="button" value="增加" do="add_goods">
        			</table>
        		</div>
            </div>
        </div>
        <!-- 增加奖项结束 -->
        
        
        <!-- 空奖封面图 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sEmptyAwardImages', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
               <input type='hidden' value='<?php echo $model->sEmptyAwardImages; ?>' name='LuckActivity[sEmptyAwardImages]'  class='goods-sEmptyAwardImages'><img src='/uploads/luck/<?php echo $model->sEmptyAwardImages;?>' class='goods-sEmptyAwardImages' width='100px;' height='100px;'><input type='button' value='上传图片' class='picUpload' target='goods-sEmptyAwardImages' id='btn_goods-sEmptyAwardImages'>
            </div>
        </div>
        
        
        
        <!-- 是否开启普奖 勾选开启后右边多出下拉框 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iGeneral', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->dropDownList($model,'iGeneral',LuckActivity::getIgeneral(), array('separator' => ' ')); ?>
               <!-- 选择开启后js弹出下拉 -->
            	<select class="iGeneralCheck" isshow='3' name="iGeneralCheck">
            		
            	</select>
            
            </div>
        </div>
        
        
        <!-- 购票增加抽奖机会 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iTickets', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->checkbox($model,'iTickets',array()); ?>
            </div>
        </div>
        <!-- 勾选完购票增加抽奖机会本文本框弹出 -->
        <div class="form-group" isshow='2'>
            <?php echo $form->labelEx($model, 'iTicketsNum', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-1">
                <?php echo $form->textField($model,'iTicketsNum',array('class' => 'col-xs-10')); ?>
            </div>
        </div>
        
         <div class="form-group">
            <?php echo $form->labelEx($model, 'iRequirement', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <?php echo $form->radioButtonList($model,'iRequirement',LuckActivity::getiRequirement(), array('separator' => ' ')); ?>
            </div>
        </div>
        
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShareTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShareTitle',array('size'=>60,'maxlength'=>300,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShareDescript', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShareDescript',array('size'=>60,'maxlength'=>2000,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sRingTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sRingTitle',array('size'=>60,'maxlength'=>300,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShareImage', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
               <input type='hidden' value='<?php echo $model->sShareImage; ?>' name='LuckActivity[sShareImage]'  class='goods-share'><img src='/uploads/luck/<?php echo $model->sShareImage;?>' class='goods-share' width='100px;' height='100px;'><input type='button' value='上传图片' class='picUpload' target='goods-share' id='btn_goods-share'>
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
<?php $this->endWidget(); ?>
