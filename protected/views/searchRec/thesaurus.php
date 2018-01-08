<?php
/* @var $this ActorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '热门搜索' => array('index'),
    '推荐词设置' => array('info', 'id' => $id),
    '词库设置',
);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
?>
<div class="page-header">
    <h1><?php echo str_replace('综合搜索-', '', $title); ?>
        <small>词库设置</small>
    </h1>
</div>
<div class="row">
    <form id="SearchRec-Thesaurus-From">
        <div class="col-xs-8 col-xs-offset-1 form-horizontal">
            <div id="ThesaurusBox">
                <?php foreach ($SearchRecList as $value) {
                    $value['data'] = json_decode($value['data'], true);
                    ?>
                    <div class="form-group">
                        <div class="col-sm-5">
                            <input type="text" class="form-control ThesaurusTag"
                                   name="ThesaurusTag[]"
                                   value="<?php echo implode(' | ', $value['data']['data']) ?>">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" class="form-control ThesaurusName"
                                   name="ThesaurusName[]"
                                   value="<?php echo $value['data']['name'] ?>">
                            <input type="hidden" name="ThesaurusId[]" class="form-control ThesaurusId"
                                   value="<?php echo $value['id'] ?>">
                        </div>
                        <div class="col-sm-2">
                            <a class="btn btn-default" onclick="ThesaurusDel(this)">删除</a>
                        </div>
                    </div>
                <?php } ?>
                <input type="hidden" name="ThesaurusDelete" id="ThesaurusDelete" class="form-control ThesaurusId"
                       value="">
                <input type="hidden" class="form-control ThesaurusTag"
                       name="ThesaurusPage"
                       value="<?php echo isset($_GET['page']) ? $_GET['page'] : 1;?>">
            </div>
            <div class="form-group">
                <div class="col-sm-4">
                    <a href="javascript:void(0)" id="AddThesaurus">添加关键词</a>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0px;">
                <div class="pull-right">
                    <?php $this->widget('application.components.SimplePaginateWidget', ['currentPage' => $paginate['page'], 'total' => $paginate['total'], 'perPage' => $paginate['perPage']]); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4" style="padding-top:10px">
                    <button class="btn btn-primary" type="submit">保 存</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="#" data-toggle="modal" data-target="#myModal4">上传文件</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="/index.php/SearchRec/export/<?php echo $id; ?>">下载文件</a>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal inmodal" id="myModal4" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title"><i class="fa fa-upload modal-icon"></i>&nbsp;&nbsp;上传文件</h4>
            </div>
            <div class="modal-body">
                <form id="csv_upload" class="form-horizontal" enctype="multipart/form-data">
                    <div class="row">
                        <br>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择文件:</label>
                            <div class="col-sm-6">
                                <input type="file" name="UploadCsv" accept=".csv">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-sm btn-primary pull-left m-t-n-xs">确认导入</button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="alert alert-warning">
                                <label class="alert-link">上传文件:</label>
                                <span>将编辑好的csv文件上传,覆盖当前的全部内容</span>
                                <br/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div style="display: none" id="NewThesaurusTemplate">
    <div class="form-group">
        <div class="col-sm-5">
            <input type="type" class="form-control ThesaurusTag" name="ThesaurusTag[]">
        </div>
        <div class="col-sm-5">
            <input type="type" class="form-control ThesaurusName" name="ThesaurusName[]">
            <input type="hidden" class="form-control ThesaurusId" name="ThesaurusId[]">
        </div>
        <div class="col-sm-2">
            <a class="btn btn-default" onclick="ThesaurusDel(this)">删除</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
    //文件上传
    $("#csv_upload").validate({
        errorPlacement: function (error, element) {
            error.appendTo(element.parent());
        },
        rules: {
            'UploadCsv': {
                required: true
            }
        },
        messages: {
            'UploadCsv': {
                required: icon + "请选择Csv文件"
            }
        },
        submitHandler: function (form) {
            layer.msg('请等候', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
            <?php
            $url = Yii::app()->getController()->createUrl('SearchRec/csvUpload/');
            ?>
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $url?>',
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        layer.msg('上传成功');
                        location.reload();
                    } else {
                        layer.msg(res.msg);
                    }
                }, error: function () {
                    layer.msg('系统繁忙');
                }
            });
            return false;
        }
    })
    function ThesaurusChange(em) {
        var _ThesaurusTag = $(em).parent().parent().find('.ThesaurusTag').val();
        var _ThesaurusName = $(em).parent().parent().find('.ThesaurusName').val();
        var _ThesaurusId = $(em).parent().parent().find('.ThesaurusId').val();
        if (_ThesaurusTag != '' && _ThesaurusName != '') {
            $.ajax({
                type: "post",
                url: "<?php echo Yii::app()->getController()->createUrl('SearchRec/create/');?>",
                data: {
                    ThesaurusPage:'<?php echo isset($_GET['page']) ? $_GET['page'] : 1;?>',
                    ThesaurusTag: _ThesaurusTag,
                    ThesaurusName: _ThesaurusName,
                    ThesaurusId: _ThesaurusId,
                    Id:<?php echo $id?>},
                dataType: "json",
                success: function (data) {
                    if (data.code == 0) {
                        $(em).parent().parent().find('.ThesaurusId').val(data.data.counterId);
                    }
                    layer.msg(data.msg);
                },
                error: function () {
                    layer.msg('系统繁忙');
                }
            });
        }
    }
    function ThesaurusDel(em) {
        var _ThesaurusId = $(em).parent().parent().find('.ThesaurusId').val();
        var _ThesaurusDelete = $('#ThesaurusDelete').val();
        if (_ThesaurusDelete.length == 0) {
            _ThesaurusDelete = new Array();
        } else {
            _ThesaurusDelete = _ThesaurusDelete.split(',');
        }
        if (_ThesaurusId.length != 0) {
            _ThesaurusDelete.push(_ThesaurusId);
        }
        $(em).parent().parent().remove();
        $('#ThesaurusDelete').val(_ThesaurusDelete.toString());
    }
    $(function () {
        $("#AddThesaurus").click(function () {
            var Dom = $("#NewThesaurusTemplate").html();
            $("#ThesaurusBox").append(Dom);
        });

        $("#SearchRec-Thesaurus-From").validate({
            errorPlacement: function (error, element) {
                error.appendTo(element.parent());
            },
            rules: {},
            messages: {},
            submitHandler: function (form) {
                layer.msg('请等候', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
                <?php
                $url = Yii::app()->getController()->createUrl('searchRec/saveThesaurus') . '/' . $id;
                ?>
                $(form).ajaxSubmit({
                    type: 'post',
                    url: '<?php echo $url?>',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 0) {
                            location.reload();
                            layer.msg(res.msg);
                        } else {
                            layer.msg(res.msg);
                        }
                    }, error: function () {
                        layer.msg('系统繁忙');
                    }
                });
                return false;
            }
        })
    })
</script>