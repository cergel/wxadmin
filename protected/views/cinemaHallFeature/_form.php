<?php
/* @var $this CinemaHallFeatureController */
/* @var $model CinemaHallFeature */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'cinema-hall-feature-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
    Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
            <?php if (!$model->getIsNewRecord()) { ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'id', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                    <div class="col-sm-9">
                        <?php echo $form->textField($model, 'id', array('id' => 'id', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'readonly' => true)); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'cinema_no', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'cinema_no', array('id' => 'cinema_no', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'do' => "notnull")); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'hall_no', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'hall_no', array('id' => 'hall_no', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'do' => "notnull")); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="button" class="btn btn-primary " id="getCHInfo">获取影厅信息</button>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'cinema_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'cinema_name', array('id' => 'cinema_name', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'readonly' => true)); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'hall_name', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'hall_name', array('id' => 'hall_name', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'readonly' => true)); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'feature_ext', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'feature_ext', array('id' => 'feature_ext', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'readonly' => true)); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'base_zan_num', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'base_zan_num', array('id' => 'base_zan_num', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'zan_num', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'zan_num', array('id' => 'zan_num', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'readonly' => true)); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'step_num', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model, 'step_num', array('id' => 'step_num', 'size' => 60, 'maxlength' => 64, 'class' => 'col-xs-4', 'readonly' => true)); ?>
                </div>
            </div>
            <?php if (!$model->getIsNewRecord()) { ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="count_zan">合计赞</label>
                    <div class="col-sm-9">
                        <input id="count_zan" class="col-xs-4" type="text" readonly="readonly" maxlength="64"
                               size="60" value="<?php echo $model->zan_num + $model->base_zan_num; ?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label no-padding-right" for="count_num">总人数</label>
                    <div class="col-sm-9">
                        <input id="count_num" class="col-xs-4" type="text" readonly="readonly" maxlength="64"
                               size="60"
                               value="<?php echo $model->zan_num + $model->base_zan_num + $model->step_num; ?>"/>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'specific_description', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textArea($model, 'specific_description', array('rows' => 3, 'id' => 'specific_description', 'class' => 'col-xs-4')); ?>
                </div>
            </div>
            <input type="hidden" name="CinemaHallFeature[feature_type]" id="feature_type" value="0"/>

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
</div><!-- form -->
<script type="text/javascript">
    //异步获取影厅信息
    $(function () {
        $("#getCHInfo").click(function () {
            var cinema_no = $("#cinema_no").val();
            var hall_no = $("#hall_no").val();
            if (cinema_no == '' || hall_no == '') {
                alert('请输入影院ID和影厅ID');
                return false;
            }
            $('#cinema_no,#hall_no').change(function () {
                $('#cinema_name').val('');
                $('#hall_name').val('');
                $('#feature_ext').val('');
            });
            $.ajax({
                type: 'post',
                url: '<?php echo Yii::app()->getController()->createUrl('cinemaHallFeature/ajaxGetInfo');?>',
                dataType: 'json',
                data: {'hall_no': hall_no, 'cinema_no': cinema_no},
                success: function (res) {
                    if (res.code == 0) {
                        $('#cinema_name').val(res.data.BaseCinemaName);
                        $('#hall_name').val(res.data.HallName);
                        $('#feature_ext').val(res.data.FeatureText);
                        $('#feature_type').val(res.data.FeatureType);
                        $('#base_zan_num').val(0);
                        $('#zan_num').val(0);
                        $('#step_num').val(0);
                        alert('获取成功');
                    } else {
                        alert(res.msg);
                    }
                }, error: function () {
                    alert('未找到相关信息');
                }
            });
        })
        var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
        $("#cinema-hall-feature-form").validate({
            rules: {
                'CinemaHallFeature[cinema_no]': {
                    required: true
                },
                'CinemaHallFeature[hall_no]': {
                    required: true
                },
                'CinemaHallFeature[hall_name]': {
                    required: true
                },
                'CinemaHallFeature[cinema_name]': {
                    required: true
                },
                'CinemaHallFeature[feature_ext]': {
                    required: true
                },
                'CinemaHallFeature[base_zan_num]': {
                    required: true,
                    number:true,
                },
                'CinemaHallFeature[zan_num]': {
                    required: true
                },
                'CinemaHallFeature[step_num]': {
                    required: true
                },
                'CinemaHallFeature[specific_description]': {
                    maxlength: 140
                }
            },
            messages: {
                'CinemaHallFeature[cinema_no]': {
                    required: icon + "请输入影院ID",
                },
                'CinemaHallFeature[hall_no]': {
                    required: icon + "请输入影厅ID",
                },
                'CinemaHallFeature[hall_name]': {
                    required: icon + "请点击获取影厅信息",
                },
                'CinemaHallFeature[cinema_name]': {
                    required: icon + "请点击获取影厅信息",
                },
                'CinemaHallFeature[feature_ext]': {
                    required: icon + "请点击获取影厅信息",
                },
                'CinemaHallFeature[base_zan_num]': {
                    required: icon + "请输入注水赞",
                    number:icon + "请输入正确的数字",
                },
                'CinemaHallFeature[zan_num]': {
                    required: icon + "请点击获取影厅信息",
                },
                'CinemaHallFeature[step_num]': {
                    required: icon + "请点击获取影厅信息",
                },
                'CinemaHallFeature[specific_description]': {
                    maxlength: icon + "最长不能超过140字",
                }

            }, submitHandler: function (form) {
                <?php
                if ($model->getIsNewRecord()) {
                    $url = Yii::app()->getController()->createUrl('cinemaHallFeature/ajaxCreate');
                    $alert_url = Yii::app()->getController()->createUrl('cinemaHallFeature/index');
                } else {
                    $url = Yii::app()->getController()->createUrl('cinemaHallFeature/ajaxUpdate');
                    $alert_url = Yii::app()->getController()->createUrl('cinemaHallFeature/Update/' . $model->id);
                }
                ?>
                $(form).ajaxSubmit({
                    type: 'post',
                    url: '<?php echo $url?>',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            location.href = "<?php echo $alert_url?>";
                        } else {
                            alert(res.msg);
                        }
                    }, error: function () {
                        alert('操作失败(确保非重复插入)');
                    }
                });
                return false;
            }
        })
    });
</script>
<?php $this->endWidget(); ?>
