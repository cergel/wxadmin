<?php
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/uploadify/jquery.uploadify.min.js");
Yii::app()->clientScript->registerScript('form', "
    $('.date-timepicker').datetimepicker({
        format:\"YYYY-MM-DD HH:mm:ss\"
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

");
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'movie-list-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
        ));
?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model, '<div class="alert alert-danger">', '</div>'); ?>
        <!-- 标题 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title', array('class' => 'col-sm-3 control-label no-padding-right ')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'title', array('size' => 60, 'class' => 'col-xs-10','required'=>true));
                ?>
            </div>
        </div>
        <!-- 描述 -->
        <div class="form-group">
            <?php echo $form->labelEx($model, 'brief', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model, 'brief', array('dotype' => 'title', 'size' => 60,  'class' => 'col-xs-10', 'do' => "notnull",'required'=>true)); ?>
            </div>
        </div>

        <!--电影-->
        <div class="form-group">
            <?php
            echo CHtml::label('电影', '', array('class' => 'col-sm-3 control-label no-padding-right'));
            ?>
            <div class="col-sm-9" id="movielist">
                <table class="table table-bordered" id="movies">
                    <tr>
                        <th>排序ID</th>
                        <th>影片ID</th>
                        <th>影片名</th>
                        <th>影片描述</th>
                        <th>操作</th>
                    </tr>
                    <?php if (!empty($arrMovies)) { ?>
    <?php foreach ($arrMovies as $k => $v) : ?>
                            <tr>
                                <td> 
                                    <input type="text" class="width-80" id="sortId" name="sortId" value="<?php echo $k+1; ?>"/>
                                </td>
                                <td><input name="movie_id[]" class="width-80" type="text" value="<?php echo $v['movie_id']; ?>"/></td>
                                <td><input name="movie_title[]" type="text" value="<?php echo $v['movie_title']; ?>" readonly="readonly" /></td>
                                <td><input name="movie_desc[]" type="text" value="<?php echo $v['movie_desc']; ?>"/></td>
                                <td>
                                    <?php echo CHtml::button('上移', array('id' => 'moveUpBtn')); ?>
                                    <?php echo CHtml::button('下移', array('id' => 'moveDownBtn')); ?>
        <?php echo CHtml::button('删除', array('id' => 'removeBtn')); ?>
                                   
                                </td>
                            </tr>
                        <?php endforeach; ?>
<?php } ?>
                    <tr>
                        <td colspan="5"> <?php echo CHtml::button('添加', array('class' => 'btn btn-info', 'id' => 'btn_add')) ?></td>
                    </tr>

                </table>
            </div>
        </div>

        <!-- 作者 -->
        <div class="form-group">
                <?php echo $form->labelEx($model, 'author', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
<?php echo $form->textField($model, 'author', array('dotype' => '', 'size' => 60, 'class' => 'col-xs-10','required'=>true)); ?>
            </div>
        </div>

        <!-- 作者头像  -->
        <div class="form-group">
<?php echo $form->labelEx($model, 'author_image', array('class' => 'col-sm-3 control-label no-padding-right required', 'required' => true)); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="author_image" dotype="author_image" src="<?php echo isset($model->author_image) ? $model->author_image : ''; ?>" width="200" height="200" />
                </div>
                <span class="help-inline col-xs-5">
                    <span class="middle">最佳尺寸: 正方形200*200，小于32Kb。</span>
                </span>
                <div class="col-xs-10">
                    <input type="file" dotype="author_image" id="valphoto" name="MovieList[author_image]" value="<?php echo isset($model->author_image) ? $model->author_image : ''; ?>" onchange="imageCheck(this)" <?php if($model->isNewRecord):?> required="true" <?php endif; ?>>
                </div>                
            </div>
        </div>
        <!-- 作者描述 -->
        <div class="form-group">
<?php echo $form->labelEx($model, 'author_desc', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
<?php echo $form->textField($model, 'author_desc', array('dotype' => '', 'class' => 'form-control','required'=>true)); ?>
                </div>
            </div>
        </div>
        <!--收藏注水-->
        <div class="form-group">
<?php echo $form->labelEx($model, 'collect_num', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
<?php echo $form->textField($model, 'collect_num', array('dotype' => '', 'class' => 'form-control')); ?>

                </div>
            </div>
        </div>
        <!--阅读注水-->

        <div class="form-group">
<?php echo $form->labelEx($model, 'read_num', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
<?php echo $form->textField($model, 'read_num', array('class' => 'form-control')); ?>
                </div>
            </div>
        </div>

        <!-- 分享标题 -->
        <div class="form-group">
<?php echo $form->labelEx($model, 'share_title', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
<?php echo $form->textField($model, 'share_title', array('dotype' => '', 'class' => 'form-control','required'=>true)); ?>
                </div>
            </div>
        </div>
        <!-- 分享描述 -->
        <div class="form-group">
<?php echo $form->labelEx($model, 'share_desc', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
<?php echo $form->textField($model, 'share_desc', array('dotype' => '', 'class' => 'form-control','required'=>true)); ?>
                </div>
            </div>
        </div>
        <!-- 分享图标  -->
        <div class="form-group">
<?php echo $form->labelEx($model, 'share_image', array('class' => 'col-sm-3 control-label no-padding-right required', 'required' => true)); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="share_image" dotype="share_image" src="<?php echo isset($model->share_image) ? $model->share_image : ''; ?>" width="200" height="200" />
                </div>
                <div class="col-xs-10">
                    <input type="file"  dotype="share_image" id="valphoto" name="MovieList[share_image]" value="<?php echo isset($model->share_image) ? $model->share_image : ''; ?>" onchange="imageCheck(this)" <?php if($model->isNewRecord):?> required="true" <?php endif; ?>>
                </div>
                <span class="help-inline col-xs-5">
                    <span class="middle">最佳尺寸: 正方形200*200，小于32Kb。</span>
                </span>
            </div>
        </div>
        <!--分享平台-->  
        <div class="form-group">
<?php echo $form->labelEx($model, 'share_platform', array('class' => 'col-sm-3 control-label no-padding-right required', 'required' => true)); ?> 
            <div class="col-xs-9">
                <div class="col-xs-10">
<?php echo $form->checkBoxList($model, 'share_platform', MovieList::model()->getSharePlatForm('list'), array('separator' => ' ', 'do' => "checkbox")); ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="MovieList[movie_num]" id="MovieList_movie_num"/>
        <div class="form-group">
<?php echo $form->labelEx($model, 'online_time', array('class' => 'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
<?php echo $form->textField($model, 'online_time', array('dotype' => 'online_time', 'class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            </div>
        </div>
        <input type="hidden" id="filmList" name="filmList" value="<?php echo $model->getIsNewRecord()? 0:$model->id; ?>"/>
        <input type="hidden" id="isNew" name="isNew" value="<?php echo $model->getIsNewRecord()? -1:1; ?>"/>


        <div class="clearfix form-actions">
            <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="submit" id="submit">
                    <i class="ace-icon fa fa-check bigger-110"></i>
<?php echo $model->isNewRecord ? '创建' : '保存'; ?>
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset" onclick="window.location.reload(true);">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        //添加
        $("#btn_add").click(function () {
            var html = '';
            var movieNum = $("input[name^='movie_id']").length + 1;
            var tr = $('table:first tr:eq(-2)');
            $("#MovieList_movie_num").val(movieNum);
            html += '<tr><td><input type="text" name="sortId" id="sortId" class="width-80" value='+ movieNum +'></td><td><input type="text" name="movie_id[]"/></td><td><input type="text" name="movie_title[]" readonly="readonly"/></td><td><input type="text" name="movie_desc[]"/></td><td><input type="button" id="moveUpBtn" value="上移"/><input type="button" id="moveDownBtn" value="下移"/> <input type="button" id="removeBtn" value="删除"/></td></tr>';
            tr.after(html);
        });
        //上移
        $(document).on("click", "table tr #moveUpBtn", function () {
            var tr = $(this).parent().parent();
            var index = tr.index();
            if (index == 1) {
                alert("已经到顶部了");
                return false;
            } else {
                tr.prev().before(tr);
                refreshSort();
            }
        });
        //下移
        $(document).on("click", "table tr #moveDownBtn", function () {
            var tr = $(this).parent().parent();
            var index = tr.index();
            var trNum = $('input#moveDownBtn').length; 
            if (index == trNum) {
                alert("已经到底部了");
                return false;
            } else {
                tr.next().after(tr);
                refreshSort();
            }
        });
        //删除
        $(document).on("click", "table tr #removeBtn", function () {
            
            var listId = $("#filmList").val(),
                isNew = $("#isNew").val();
            var tr = $(this).parent().parent();
            var movieId = $(this).parent().parent().find("input[name^='movie_id']").val();
            if(isNew == 1) {
                //判断接口删除是否成功 编辑页删除
                $.ajax({
                data: "listId=" + listId + "&movieId=" + movieId,
                url: "/movieList/ajaxRemoveMovie/",
                type: "get",
                dataType: 'json',
                async: false,
                success: function (data) {
//                    if (data.ret == 0) {
                        tr.remove();
                        var movieNum = $("input[name^='movie_id']").length + 1;
                        $("#MovieList_movie_num").val(movieNum);
                        refreshSort();
//                    }
//                    else {
//                        alert('删除失败');
//                    }
                },
                error: function (data) {
                    alert("请求异常");
                }
            });
            }
            else {
                tr.remove();
                var movieNum = $("input[name^='movie_id']").length + 1;
                $("#MovieList_movie_num").val(movieNum);
                refreshSort();
            }
        });
        //数值排序 
        $(document).on("blur", "table tr #sortId", function () {
            var tr = $(this).parent().parent(),
                index = tr.index(),
                trNum = $('input#sortId').length,
                sortValue = $(this).val();
            if (sortValue > trNum || sortValue < 1) {
                alert('无效值');
                $(this).val(''); 
                refreshSort();
                return false;
            } 
            
            if(sortValue == 1) {
                  $('table tr:eq(0)').after(tr) ;
                  $(this).val('');
            } 
            else if (index > sortValue) {
                  $('table tr:eq('+sortValue+')').before(tr);    
                  $(this).val('');
            }
            else {
                 $('table tr:eq('+sortValue+')').after(tr);  
                 $(this).val('');
            }
            refreshSort();
           
        });
        $("#submit").click(function () {
            var movieNum = $("input[name^='movie_id']").length;
            if (movieNum < 1) {
                alert('请填写关联电影');
                return false;
            }else if (movieNum > 800) {
                alert('最多关联800个影片');
                return false;
            }
            $("#MovieList_movie_num").val(movieNum);
            
            var o={};
            var bool = true;
            $("input[name^='movie_id']").each(function(){
                if(!(o[$(this).val()])){
                    o[$(this).val()] = true;
                }else{
                    alert('关联影片重复,请检查');
                    bool = false;
                }
            });
            if (!bool) {
                return false;
            }
        });
    });

    //获取影片信息
    $(document).on('change', "input[name^='movie_id']", function () {
        var movieId = $(this).val();
        var movieName = $(this).parent().parent().find("input[name^=movie_title]");
        if(!movieId){
            movieName.val('请输入影片ID');
            return false;
        }
        $.ajax({
            data: "movie_id=" + movieId,
            url: "/movieList/ajaxGetMovieInfo/",
            type: "get",
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.ret == 0) {
                    if(data.data.name) {
                        movieName.val(data.data.name);
                    }
                    else {
                        movieName.val('不存在的影片ID');
                        $(this).val('');
                    }
                }
                else {
                    movieName.val(data.msg);
                }
            },
            error: function (data) {
                alert("请求异常");
            }
        });
    });
    
    function refreshSort() {
         $("table tr td #sortId").each(function(i){
                $(this).val(i+1);
            });
    }
    
    function imageCheck(img) {
        var filePath = img.value;
        var fileExt = filePath.substring(filePath.lastIndexOf(".")).toLowerCase();
        if (!checkFileExt(fileExt)) {
                alert("您上传的文件不是图片,请重新上传！");
                img.value = "";
                return;
            }
        if (img.files && img.files[0]) {
//                alert('你选择的文件大小' + (img.files[0].size / 1024).toFixed(0) + "kb");
                var fileSize = (img.files[0].size / 1024).toFixed(0);
                if (fileSize > 32) {
                    alert('您选择的文件大小为' + fileSize + 'kb,请重新选择!');
                    img.value = "";
                    return false;
                }
            } else {
                img.select();
                var url = document.selection.createRange().text;
                try {
                    var fso = new ActiveXObject("Scripting.FileSystemObject");
                } catch (e) {
                    alert('如果你用的是ie8以下 请将安全级别调低！');
                }
//                alert("文件大小为：" + (fso.GetFile(url).size / 1024).toFixed(0) + "kb");
                var fileSize = (fso.GetFile(url).size / 1024).toFixed(0);
                if (fileSize > 32) {
                    alert('您选择的文件大小为' + fileSize + 'kb,请重新选择!');
                    img.value = "";
                    return false;
                }
                
            }       
    }
    function checkFileExt(ext) {
        if (!ext.match(/.jpg|.gif|.png|.bmp/i)) {
                return false;
            }
        return true;
    }
</script>
<?php $this->endWidget(); ?>
