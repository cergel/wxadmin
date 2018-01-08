<?php
$this->breadcrumbs = array(
    '影片排序'
);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);

?>
<div class="page-header">
    <table>
        <tr>
            <td><h1>影片排序</h1></td>
            <td>
                <div style="position:absolute;right:100px;top:0;font-size: 14px;">
                    排期&nbsp;&nbsp;
                <input type="text" name="schedNum" id="sched" size=6 value="<?php echo $num; ?>" style="line-height:10px;text-align: center;"/>
                <input type="button" name="schedNum" id="schedbutton" style="background-color:#87b87f;color:#FFF;width: 62px;height: 42px;border:none;"  value="修改" />
            </div>
            </td>
            <td><a class="btn btn-success" href="/movieOrder/create">创建</a></td>
        </tr>
    </table>
</div>
<script>
    //修改
    $(function()
    {
        $('#schedbutton').click(function()
        {
            var num = $("#sched").val();
            if(!isNaN(num) && num >0){
                $.post("<?php echo $this->createUrl('movieOrder/saveCache')?>","num="+num,function(a){
                    alert(a);
                })
            }else{
                alert('请输入合法的数据');
            }
        })
    });
</script>

<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id'=>'active-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'movie_id',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'movie_name',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'start_time',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'$data->formatData($data->start_time)',
                ),
                array(
                    'name' => 'end_time',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'$data->formatData($data->end_time)',
                ),
                array(
                    'name' => 'pos',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'status',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'$data->formatStatus($data->status)',
                ),
                array(
                    'name' => 'created',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'$data->formatData($data->created)',
                ),
                array(
                    'name' => 'updated',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                    'value'=>'$data->formatData($data->updated)',
                ),
//                array(
//                    'name' => 'content',
//                    'htmlOptions' => array(
//                        'width' => 80,
//                    ),
//                ),
                array(
                    'header' => '操作',
                    'class'=>'CButtonColumn',
                    'headerHtmlOptions' => array('width'=>'150'),
                    'template' => ' {update} {delete}',
                ),
            ),
        )); ?>
    </div>
    <div id="dialogInfo" style="display: block"></div>
</div>
