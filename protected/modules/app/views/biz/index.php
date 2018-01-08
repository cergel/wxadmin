<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/jquery-ui.min.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery-ui.min.js");
$this->breadcrumbs = array(
    '影片商业化详情',
);


?>
<div class="page-header">
    <table>
        <tr>
            <td>
                <h1>新建影片商业化详情</h1>
            </td>
            <td>
            </td>
            <td>
                <a class="btn btn-success" href="/app/biz/create">创建项目</a>
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
                    'header' => '影片名称',
                    'value' => '"载入中……"',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'end',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'platform',
                    'htmlOptions' => array(
                        'width' => 200,
                    ),
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->status==1?"已发布":"下线"',
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
                            'url' => '"/app/biz/update/id/".$data->id',
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
            if (!confirm("目前状态是 :" + $(this).text() + " ,是否切换状态?!")) {
                return false;
            }
            var l = $(this);
            l.text("操作中...");
            var id = $(this).parent().children(".class_movieGuide_id").html();
            $.ajax({
                url: '/app/biz/switch/id/' + id,
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

        $(document).on("click", '#flushcache_btn', function () {
            $(document).on('mouseover', '.classIsOnline', function () {
                $(this).css("color", "#2a6496");
                $(this).css("cursor", "pointer");
            });
            $(document).on('mouseout', '.classIsOnline', function () {
                $(this).css("color", "#428bca");
            });

        });
        $.each($("tr"), function (i, k) {
            movieId = $($(k).find("td")[1]).text();
            $(k).attr("id", movieId);

            if (/\d/.test(movieId)) {
                $.ajax({
                    "url": "http://commoncgi.wepiao.com/channel/movie/get-movie-info",
                    "dataType": "script",
                    "data": {"movieId": movieId}
                });
            }
            //渠道号转换为文字
            platform = $($(k).find("td")[4]).text();
            if (platform != "") {
                arr = platform.split(",");
                if ($.inArray("8", arr) != -1) {
                    arr[$.inArray("8", arr)] = "iOS";
                }

                if ($.inArray("9", arr) != -1) {
                    arr[$.inArray("9", arr)] = "Android";
                }
                $($(k).find("td")[4]).text(arr.join(","));
            }

        });


    });
    var MovieData = {};
    MovieData.set = function (i, k) {
        $($($("#" + k.info.id)).find("td")[2]).text(k.info.name);
    };
</script>