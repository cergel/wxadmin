<?php
$this->breadcrumbs=array(
	$this->module->id,
    '导出客服记录'
);

Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });
    $('#btn').click(function(){
        $.ajax({
            'url' : '/weixin/service/export',
            'data' : 'date=' + $('#date').val(),
            'method' : 'post',
            'dataType' : 'json',
            'beforeSend' : function () {
                $('#btn').attr('disabled', true);
                $('#date').attr('disabled', true);
            },
            'success' : function (data) {
                if (data.ret == 0) {
                    $('body').append('<iframe src=\'/weixin/service/download?path='+data.data+'\'></iframe>');
                } else {
                    alert('请重试');
                }
            },
            'error' : function (data) {
                alert('请重试');
            },
            'complete' : function (data) {
                $('#btn').attr('disabled', false);
                $('#date').attr('disabled', false);
            }
        });
    });
");
echo CHtml::beginForm('/weixin/service/export', 'post', array('enctype'=>'multipart/form-data','class' => 'form-horizontal')); ?>
<div class="row">
            <div class="col-sm-offset-3 col-sm-6" style="margin-top:200px">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="input-group">
                            <?php echo Chtml::textField('date','',array('class' => 'form-control date-timepicker', 'id'=> 'date')); ?>
                            <span class="input-group-addon">
						        <i class="fa fa-clock-o bigger-110"></i>
					        </span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-info btn-sm" type="button" id="btn">
                            <i class="ace-icon fa fa-check bigger-110"></i>
                            导出
                        </button>
                    </div>
                </div>
            </div>
</div>
<?php echo CHtml::endForm(); ?>
