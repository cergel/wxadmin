<?php
/**
 * Created by PhpStorm.
 * User: kirsten_ll
 * Date: 2016/2/23
 * Time: 14:38
 */
/* @var $this ActiveController */
/* @var $dataProvider CActiveDataProvider */
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs = array(
    'MovieGuide',
);


?>
<div class="page-header">
    <table>
        <tr>
            <td>
                <h1>管理观影秘籍</h1>
            </td>
            <td>
                <ul>
                    <li>领取人数为缓存数据更新缓存后即可显示实时数据</li>
                    <li>本条目不能直接删除，需要删除请先将发布状态更改为下线一小时后再物理删除</li>
                </ul>
            </td>
            <td>
                <a class="btn btn-success" href="/app/movieGuide/create">创建观影秘笈</a>
            </td>
        </tr>
    </table>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php $this->widget('application.components.WxGridView', array(
            'id' => 'movieGuide-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => 80,
                        'class' => 'class_movieGuide_id',
                    ),
                ),
                array(
                    'name' => 'movieId',
                    'htmlOptions' => array(
                        'width' => 80,
                    ),
                ),
                array(
                    'name' => 'title',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'is_index',
                    'htmlOptions' => array(
                            'width' => 200,
                    ),
                    'filter' => CHtml::activeDropDownList($model, 'is_index', ['' => '全部', '1' => '有', '0' => '无']),
                    'value'=>'$data->is_index ? "有" : "无"',
                    ),
                array(
                    'name' => 'pvCount',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'basePvCount',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'baseGetCount',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'getCount',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'status',
                    'value' => function ($model) {
                        $status = [
                            '0' => "未上线",
                            '1' => "已上线",
                            '2' => "等待删除",
                        ];

                        if (isset($status[$model->status])) {
                            return $status[$model->status];
                        } else {
                            return "状态未知";
                        }
                    },
                    'filter' => CHtml::activeDropDownList($model, 'status', ['' => '全部', '1' => '上线', '0' => '下线 ']),
                    'htmlOptions' => array(
                        'width' => 80,
                        'class' => 'classIsOnline',
                        'style' => 'color: #428bca;text-decoration: none;',
                    ),
                ),

                array(
                    'header' => '操作',
                    'class' => 'CButtonColumn',
                    'headerHtmlOptions' => array('width' => '150'),
                    'template' => '{edit} {delete}',
                    'buttons' => array(
                        'edit' => array(
                            'label' => '编辑',
                            'url' => '"/app/MovieGuide/update/id/".$data->id',
                        ),
                    )
                ),
            ),
        )); ?>
    </div>

</div>
<script>
    $(function () {

        $(document).on("click", '.classIsOnline', function () {

            var l = $(this);
            l.text("操作中...");
            var id = $(this).parent().children(".class_movieGuide_id").html();
            $.ajax({
                url: '/app/movieGuide/switch/id/' + id,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    alert('更新成功');
                    window.location.reload();
                },
                error: function (msg) {
                    alert('网络异常请重试')
                }
            });
        });


        $(document).on('mouseover', '.classIsOnline', function () {
            $(this).css("color", "#2a6496");
            $(this).css("cursor", "pointer");
        });


        $(document).on('mouseout', '.classIsOnline', function () {
            $(this).css("color", "#428bca");
        });

    })
</script>