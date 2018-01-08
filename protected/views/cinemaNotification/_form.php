<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    $('.data_date1-timepicker').datetimepicker({
        format:'YYYY-MM-DD HH:mm:ss'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
		$('#CinemaNotification_channel :checkbox').click(function () {
		if($(this).val() == '0'){
			if($(this).is(':checked')){
				updatechannel(1)
			}else{
				updatechannel(2)
			}
		}else{
			if(! $(this).is(':checked')){
				$('#CinemaNotification_channel_0').prop('checked',false);
			}
		}
    });
//     $('#CinemaNotification_channel_0').click(function () {
// 		if($(this).is(':checked')){
// 			updatechannel(1)
// 		}else{
// 			updatechannel(2)
// 		}
//     });
		function updatechannel (t)
		{
			$('#CinemaNotification_channel :checkbox').each(function(){
				if(t == 1)
					this.checked = true;
				else
					this.checked = false;
			  });
		}

	var isnew = '" . $model->isNewRecord . "';
	if(!isnew){
		$('#ActivePage_iType :radio').attr('disabled','disabled');
	}
");
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'cinema-notification-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal', 'autocomplete' => 'off')
)); ?>
    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sName', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'sName', array('size' => 60, 'maxlength' => 100, 'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'iShow', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->radioButtonList($model, 'iShow', $model->getShow(), array('separator' => ' ')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'iStartAt', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div class="input-group col-xs-3">
                        <?php echo $form->textField($model, 'iStartAt', array('class' => 'form-control data_date1-timepicker')); ?>
                        <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'iEndAt', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div class="input-group col-xs-3">
                        <?php echo $form->textField($model, 'iEndAt', array('class' => 'form-control data_date1-timepicker')); ?>
                        <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'iStatus', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <?php echo $form->radioButtonList($model, 'iStatus', array('1' => '开启', '0' => '关闭'), array('separator' => ' ')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo CHtml::label('渠道', '', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <div class="col-xs-10">
                        <?php echo $form->checkBoxList($model, 'channel', $model->getAppkey(), array('separator' => '  ')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'appver', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'appver', array('size' => 60, 'maxlength' => 100, 'class' => 'col-xs-10', 'placeholder' => '请填写指定的版本号，不写则为全部')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sContent', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <?php echo $form->textArea($model, 'sContent', array('rows' => 5, 'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sInfo', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <div>
                        <?php echo $form->textArea($model, 'sInfo', array('rows' => 5, 'class' => 'col-xs-10')); ?>
                    </div>
                    <div class="col-xs-10">
                        <?php echo htmlspecialchars('若添加超链接，请使用<a href=“链接">文本</a>方式添加如：<a href="http://http://www.wepiao.com/">娱票儿</a>'); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'allChannel', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->radioButtonList($model, 'allChannel',['1'=>'是&nbsp;&nbsp;','0'=>'否'], array('separator' => ' ')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo CHtml::label('定向影城', '', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-xs-9">
                    <div class="col-xs-10">
                        <?php $this->widget('application.components.CinemaSelectorWidget', array(
                            'name' => 'cinemas',
                            'selectedCinemas' => $selectedCinemas
                        )); ?>
                    </div>
                </div>
            </div>
            <div class="form-group" id="movie_id" style="display:none">
                <?php echo $form->labelEx($model, 'movieId', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'movieId', array('size' => 60, 'maxlength' => 100, 'class' => 'col-xs-5','disabled'=>true)); ?>
                    (可选填，每次填写一个)
                </div>
            </div>
            <div class="clearfix form-actions">
                <div class="col-xs-offset-3 col-xs-9">
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

    <script type="text/javascript">
        var show_ids = [22, 2];
        $(function () {
            var iShow_id = $("input[name='CinemaNotification[iShow]']:checked").val();
            if ($.inArray(iShow_id, show_ids)) {
                $('#movie_id').show();
                $('#CinemaNotification_movieId').attr('disabled', false);
            }
            var allChannelDom = $("input[name='CinemaNotification[allChannel]']:checked");
            if (allChannelDom.val() == 1) {
                allChannelDom.parent().parent().parent().next().hide();
            } else {
                allChannelDom.parent().parent().parent().next().show();
            }
        })
        $("#CinemaNotification_appver").blur(function () {
            alert("请注意填写此项仅针对APP相关渠道生效,请核查是否勾APP对应的渠道！");
        });

        $("input[name='CinemaNotification[channel][]']").change(function () {
            var app_channel = ["8", "9"];
            var select_channel = [];
            $("input[name='CinemaNotification[channel][]']").each(function (i, k) {
                if (k.checked) {
                    select_channel.push(k.value);
                }

            });
        });
        $("input[name='CinemaNotification[allChannel]']").change(function () {
            if ($(this).val() == 1) {
                $(this).parent().parent().parent().next().hide();
            } else {
                $(this).parent().parent().parent().next().show();
            }
        })
        $("input[name='CinemaNotification[iShow]']").change(function () {
            if ($.inArray($(this).val(), show_ids) > -1) {
                $('#movie_id').show();
                $('#CinemaNotification_movieId').attr('disabled', false);
            } else {
                $('#movie_id').hide();
                $('#CinemaNotification_movieId').attr('disabled', true);
            }
        });
        $("#cinema-notification-form").submit(function () {
            var iShow_id = $("input[name='CinemaNotification[iShow]']:checked").val();
            var isAllChannel = $("input[name='CinemaNotification[allChannel]']:checked").val();
            var show_ids = ["1", "2", "7"];
            if ($.inArray(iShow_id, show_ids) > -1 && isAllChannel == 0) {
                var cinema_num = $("input[name='cinemas[]']:checked").length;
                if (cinema_num == 0) {
                    alert('请选择指定影院');
                    return false;
                }
            }
            return true;
        })
    </script>
<?php $this->endWidget(); ?>