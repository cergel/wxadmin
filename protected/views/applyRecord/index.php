<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 19:03
 */
$this->breadcrumbs = array(
    '发现活动管理' => array('index'),
    '报名管理'
);
?>
<div class="page-header">
    <h1>报名管理</h1>
    <a class="btn btn-success" href="javascript:void(0)" onclick="GetSearch()">导出</a>
</div>


<script type="text/javascript">
    function GetSearch(){
//        var data=new Array();
//        $("input[name='ApplyRecord[*]']").each(function (){
//            alert($(this.attr('name')));
//        });
//        return false;
        var urlparams = '';
        $("input:text").each(function(){
            urlparams += $(this).attr('name')+'='+$(this).val()+'&';
        });
        urlparams += 'd=1';
        window.open('/applyRecord/out?'+urlparams);

    }
</script>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'apply-record-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 60,
                        'do'=>'search',
                    )
                ),
                array(
                    'name' => 'create_time',
                    'value'=>'ApplyRecord::model()->int2date($data->create_time)',
                    'filter'=>'',
                    'htmlOptions' => array(
                        'width' => 200,
                        'do'=>'search',
                    ),
                ),
                array(
                    'name' => 'a_id',
                    'htmlOptions' => array(
                        'width' => 80,
                        'do'=>'search',
                    )
                ),
                array(
                    'name' => 'open_id',
                    'htmlOptions' => array(
                        'width' => 150,
                        'do'=>'search',
                    )
                ),
                array(
                    'name' => 'user_name',
                    'htmlOptions' => array(
                        'width' => 150,
                        'do'=>'search',
                    )
                ),
                array(
                    'name' => 'phone',
                    'htmlOptions' => array(
                        'width' => 150,
                        'do'=>'search',
                    )
                ),
                array(
                    'name' => 'remark_content',
                    'htmlOptions' => array(
                        'width' => 150,
                        'do'=>'search',
                    )
                ),
                array(
                    'name' => 'channel_id',
                    'htmlOptions' => array(
                        'width' => 150,
                        'do'=>'search',
                    )
                ),
            )
        )); ?>
    </div>
</div>