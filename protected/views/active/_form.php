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
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'ad-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data','class' => 'form-horizontal')
)); ?>
<link rel="stylesheet" type="text/css" href="/assets/js/uploadify/uploadify.css">
<div class="row">
    <input name="updateType" type="hidden" value="<?php echo $updateType;?>">
    <div>
        <div class="col-md-offset-3 col-md-9">
            <button class="btn btn-info" type="submit" id="submit"  style="margin-bottom: 20px;">
                <i class="ace-icon fa fa-check bigger-110"></i>
                <?php echo $model->isNewRecord ? '创建' : '保存'; ?>
            </button>
        </div>
    </div>
    <div class="col-xs-12">
        <?php echo $form->errorSummary($model,'<div class="alert alert-danger">','</div>'); ?>
        <?php if(!empty($updateType) && $updateType == 'edit'){ ?>
        <div class="form-group" >
            <?php echo $form->labelEx($model, 'iType', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="row">
                <div style="display:<?php if(isset($model->iType)&& $model->iType!=1){echo "none";}else{echo "block";} ?>" class="col-xs-1"><input id="input_type_1"  value="1" type="radio" class="type" name="Active[iType]" <?php if((isset($model->iType) && $model->iType==1)|| !isset($model->iType)){echo 'checked';} ?>>图文</div>
                <div style="display:<?php if(isset($model->iType)&& $model->iType!=2){echo "none";}else{echo "block";} ?>" class="col-xs-1"><input id="input_type_2" value="2" type="radio" class="type" name="Active[iType]" <?php if(isset($model->iType) && $model->iType==2){echo 'checked';} ?>>视频</div>
                <div style="display:<?php if(isset($model->iType)&& $model->iType!=3){echo "none";}else{echo "block";} ?>" class="col-xs-1"><input id="input_type_3" value="3" type="radio" class="type" name="Active[iType]" <?php if(isset($model->iType) && $model->iType==3){echo 'checked';} ?>>相册</div>
                <div style="display:<?php if(isset($model->iType)&& $model->iType!=4){echo "none";}else{echo "block";} ?>" class="col-xs-1"><input id="input_type_4" value="4" type="radio" class="type" name="Active[iType]" <?php if(isset($model->iType) && $model->iType==4){echo 'checked';} ?>>原声</div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sSummary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sSummary',array('rows' => 5, 'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div id="type_1" class="type_div" style="display:<?php if(isset($model->iType)){echo $model->iType==1?'block':'none';}else{echo 'block';}?>;">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sContent', array('id'=>'','class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <!-- 加载编辑器的容器 -->
                    <script id="container" name="Active[sContent]" type="text/plain" style="width:640px; height:350px;"><?php echo isset($model->sContent)?$model->sContent:''; ?>
            </script>
                    <!-- 配置文件 -->
                    <script type="text/javascript" src="/assets/js/ueditor/ueditor.config.js"></script>
                    <!-- 编辑器源码文件 -->
                    <script type="text/javascript" src="/assets/js/ueditor/ueditor.all.js"></script>
                    <!-- 实例化编辑器 -->
                    <script type="text/javascript">
                        var ue = UE.getEditor('container');
                    </script>
                    <div style="color:#D42124;margin-top:8px;">文中增加影片 card 在编辑器中粘贴{movieId:5853}，增加演出card格式：{showId:01ed2fc7dec64b478f9f72e88f137f7c}</div>
                </div>
            </div>
        </div>
        <div  id="type_2"   class="type_div"  style="display:<?php if(isset($model->iType)){echo $model->iType==2?'block':'none';}else{echo 'none';}?>">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sVideo_link', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'sVideo_link',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                    <div style="color:#D42124;">视频来源仅使用腾讯、优酷平台,暂不使用其他平台。视频提取链接为:&lt;iframe&nbsp;frameborder=&quot;0&quot;&nbsp;width=&quot;640&quot;&nbsp;height=&quot;498&quot;&nbsp;src=&quot;http://v.qq.com/iframe/player.html?vid=y00192bf0qq&tiny=0&auto=0&quot;&nbsp;allowfullscreen&gt;&lt;/iframe&gt;</div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sVideo_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'sVideo_time',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                    <div style="color:#D42124;margin-top:8px;">格式为 5'11"</div>
                </div>
            </div>
        </div>
        <div  id="type_3" class="type_div" style="display: <?php if(isset($model->iType)){echo $model->iType==3?'block':'none';}else{echo 'none';}?>">
            <div class="form-group">
                <?php echo $form->labelEx($model, '相册', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <div id="file_upload">
                    </div>
                    <div id="uploadfileQueue" style="padding: 3px;">
                    </div>
                    <div style="color:#D42124;">上传图册需统一主题,>6 张，图册每张图要求添加描述,描述字数控制在 80 字以内</div>
                </div>
            </div>
            <div id="album">
                <?php
                if(isset($model->sPicture)){
                    foreach($model->sPicture as $k=>$v){
                        ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">图片</label>
                            <div class="col-sm-9">
                                <img src="<?php echo $v['path'];?>">
                                <input type="hidden" name="Active[album_pic][]" value="<?php echo $v['path'];?>">
                                <input type="button" class="delPic" value="删除">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right"></label>
                            <div class="col-sm-9">
                                <textarea style="width:350px; height:120px;" name="Active[album_content][]"><?php echo $v['content'];?></textarea>
                            </div>
                        </div>
                    <?php
                    }}
                ?>
            </div>
        </div>
        <div id="type_4" class="type_div"style="display: <?php if(isset($model->iType)){echo $model->iType==4?'block':'none';}else{echo 'none';}?>">
            <div class="form-group ">
                <?php echo $form->labelEx($model, '音频链接', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'sAudio_link',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                </div>
            </div>
            <div class="form-group ">
                <?php echo $form->labelEx($model, '音频时长', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
                <div class="col-sm-9">
                    <?php echo $form->textField($model,'sAudio_time',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                </div>
            </div>
        </div>
        <?php } ?>
        <?php if(!empty($updateType) && $updateType == 'release'){ ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTitle', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTitle',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, '发布平台', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-xs-9">
                <div class="row">
                    <?php if(isset(Yii::app()->params['share_platform'])){ foreach(Yii::app()->params['release_platform'] as $k =>$v){?>
                        <div class="col-xs-2"><input type="checkbox" class="release_checkbox" name="release[]" value="<?php echo $k;?>"  <?php echo isset($release[$k])?'checked':'' ?>  ><?php echo $v;?></div>
                    <?php }}?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sTag', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sTag',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sCover', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="sCover_img" src="<?php echo isset($model->sCover)?$model->sCover:'';?>" width="180" height="130" />
                    <input id="coverUploadClean" type="button" value="删除">
                </div>
                <div class="col-xs-10">
                    <div id="type_1" class="type_div" style="display:<?php if(isset($model->iType)){echo $model->iType==1?'block':'none';}else{echo 'block';}?>;">
                        <div style="color:#D42124;margin-bottom:10px;padding:0px">正方形,大小小于 32K</div>
                    </div>
                    <div  id="type_2"   class="type_div"  style="display:<?php if(isset($model->iType)){echo $model->iType==2?'block':'none';}else{echo 'none';}?>">
                        <div style="color:#D42124;margin-bottom:10px;padding:0px">清晰无 logo,电影剧照优先,人像使用中近景， 16:9(建议使用 640x360)</div>
                    </div>
                    <div  id="type_3" class="type_div" style="display: <?php if(isset($model->iType)){echo $model->iType==3?'block':'none';}else{echo 'none';}?>">
                        <div style="color:#D42124;margin-bottom:10px;padding:0px">清晰无 logo,电影剧照优先,人像使用中近景，要求上传封面图,图片尺寸为 3:2(建议使用 600X400)</div>
                    </div>
                    <div id="type_4" class="type_div"style="display: <?php if(isset($model->iType)){echo $model->iType==4?'block':'none';}else{echo 'none';}?>">
                        <div style="color:#D42124;margin-bottom:10px;padding:0px">清晰无 logo,电影剧照优先,人像使用中近景</div>
                    </div>
                    <div id="coverUpload">
                    </div>
                    <div id="coverUploadQueue" style="padding: 3px;">
                    </div>
                    <input id="coverInput" name="Active[sCover]" type="hidden" value="<?php echo isset($model->sCover)?$model->sCover:'';?>">
                </div>
            </div>
        </div>
        <?php }?>
        <?php if(!empty($updateType) && $updateType == 'edit'){ ?>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="Active_source_name"><B>来源平台:</B></label>
            <div  class="col-sm-9" style="color:#D42124;margin-top:6px;">须加编辑名、头像和介绍(要求每个人固定以上要素)</div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sSource_name', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sSource_name',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sSource_head', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="sourceHead_img" src="<?php echo isset($model->sSource_head)?$model->sSource_head:'';?>" width="180" height="130" />
                    <input id="sourceHeadUploadClean" type="button" value="删除">
                </div>
                <div class="col-xs-10">
                    <div id="sourceHeadUpload">
                    </div>
                    <div id="sourceHeadUploadQueue" style="padding: 3px;">
                    </div>
                    <input id="sourceHeadInput" name="Active[sSource_head]" type="hidden" value="<?php echo isset($model->sSource_head)?$model->sSource_head:'';?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sSource_summary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textArea($model,'sSource_summary',array('rows'=>5,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sSource_link', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sSource_link',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label no-padding-right" for="Active_source_name"><B>分享配置:</B></label>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShare_logo', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div style="height:200px;width:400px;">
                    <img id="shareLogo_img" src="<?php echo isset($model->sShare_logo)?$model->sShare_logo:'';?>" width="180" height="130" />
                    <input id="shareLogoUploadClean" type="button" value="删除">
                </div>
                <div class="col-xs-10">
                    <div style="color:#D42124;margin-bottom:10px;padding:0px">正方形,大小小于 32K</div>
                    <div id="shareLogoUpload">
                    </div>
                    <div id="shareLogoUploadQueue" style="padding: 3px;">
                    </div>
                    <input id="shareLogoInput" name="Active[sShare_logo]" type="hidden" value="<?php echo isset($model->sShare_logo)?$model->sShare_logo:'';?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShare_title', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShare_title',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
                <div style="color:#D42124;margin-top:8px;">15字以内</div>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShare_summary', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShare_summary',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShare_link', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShare_link',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'sShare_otherLink', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'sShare_otherLink',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, '分享平台(仅APP用)', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="row">
                <?php if(isset(Yii::app()->params['share_platform'])){ foreach(Yii::app()->params['share_platform'] as $k =>$v){?>
                    <?php
                    if(isset($isCreate)){
                        ?>
                        <div class="col-xs-2"><input type="checkbox" name="share[]" value="<?php echo $k;?>" checked><?php echo $v;?></div>
                        <?php
                    }else{
                        ?>
                        <div class="col-xs-2"><input type="checkbox" name="share[]" value="<?php echo $k;?>" <?php echo isset($share[$k])?'checked':'' ?>><?php echo $v;?></div>
                        <?php
                    }
                    ?>
                <?php  }}?>
            </div>
        </div>
        <?php }?>
        <?php if(!empty($updateType) && $updateType == 'release'){ ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iFill', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'iFill',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iFillRead', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <?php echo $form->textField($model,'iFillRead',array('size'=>60,'maxlength'=>200,'class' => 'col-xs-10')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'iOnline_time', array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php echo $form->textField($model,'iOnline_time',array('class' => 'form-control date-timepicker')); ?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>
      <!--  <div class="form-group">
            <?php /*echo $form->labelEx($model, 'iOffline_time', array('class'=>'col-sm-3 control-label no-padding-right')); */?>
            <div class="col-sm-9">
                <div class="input-group col-xs-3">
                    <?php /*echo $form->textField($model,'iOffline_time',array('class' => 'form-control date-timepicker')); */?>
                    <span class="input-group-addon">
						<i class="fa fa-clock-o bigger-110"></i>
					</span>
                </div>
            </div>
        </div>-->
<!--        <div class="form-group">
            <?php /*echo $form->labelEx($model, 'iIsonline', array('class'=>'col-sm-3 control-label no-padding-right')); */?>
            <div class="row">
                <div class="col-xs-1"><input type="radio" name="Active[iIsonline]" value="1" <?php /*echo (isset($model->iIsonline)&&($model->iIsonline==1))?'checked':''*/?>>是</div>
                <div class="col-xs-1"><input type="radio" name="Active[iIsonline]" value="0" <?php /*echo (isset($model->iIsonline)&&($model->iIsonline==0))?'checked':''*/?>>否</div>
            </div>
        </div>-->
        <div class="form-group" isall="1">
            <?php echo CHtml::label('定向城市', false, array('class'=>'col-sm-3 control-label no-padding-right')); ?>
            <div class="col-sm-9">
                <div class="col-xs-10">
                    <?php $this->widget('application.components.CitySelectorWidget',array(
                        'name' => 'cities',
                        'selectedCities' => $selectedCities
                    )); ?>
                </div>
            </div>
        </div>
        <?php }?>
        <input name="updateType" type="hidden" value="<?php echo $updateType;?>">
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
<script>
    $(function(){
        var picNum=<?php echo isset($model->picNums)?$model->picNums:0;?>
        //切换类型
        $(".type").click(function(){
            var v=$(this).val();
            $(".type_div").hide();
            $("#type_"+v).show();
        })
        //增加图片
        $("#addPic").click(function(){

            if(picNum==9){
                alert("图片最多传9张");
                return;
            }
            picNum++;
            var html='<div class="form-group">' +
                '<label class="col-sm-3 control-label no-padding-right">图片</label>'+
            '<div class="col-sm-9">'+
            '<input type="file" name="Active[album_pic][]">'+
            '<input type="button" class="delPic" value="删除">'+
            '</div> </div>'+
            '<div class="form-group">'+
            '<label class="col-sm-3 control-label no-padding-right"></label>'+
            '<div class="col-sm-9">'+
            '<textarea style="width:350px; height:120px;" name="Active[album_content][]"></textarea>'+
            '</div>'+
            '</div>'
            $("#type_3").append(html);
        })



        //相册删除按钮
        $("body").on("click",".delPic",function(){
            var htmlObj=$(this).parents(".form-group");
            htmlObj.next().remove();
            htmlObj.remove();
        });

        //上传按钮逻辑
        $("#file_upload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'uploadfileQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/active/ajaxUpload',
            'multi':  false,//禁止批量上传，要一次一次的传
            'fileTypeDesc':'支持的格式：',
            'fileTypeExts':'*',
            'fileSizeLimit':'10MB',
            'removeTimeout':1,
            //检测FLASH失败调用
            'onFallback':function(){
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess':function(file, data, response){
                jsonObj=jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if(succ==1){
                    picNum++;
                    var html='<div class="form-group">' +
                    '<label class="col-sm-3 control-label no-padding-right">图片</label>'+
                    '<div class="col-sm-9">'+
                    '<img src="'+path+'">'+
                    '<input type="hidden" name="Active[album_pic][]" value="'+path+'">'+
                    '<input type="button" class="delPic" value="删除">'+
                    '</div> </div>'+
                    '<div class="form-group">'+
                    '<label class="col-sm-3 control-label no-padding-right"></label>'+
                    '<div class="col-sm-9">'+
                    '<textarea style="width:350px; height:120px;" name="Active[album_content][]"></textarea>'+
                    '</div>'+
                    '</div>'
                    $("#album").prepend(html);
                }else{
                    alert('图片上传失败')
                }
            }
        });

        //封面图上传
        $("#coverUpload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            'multi':true,//允许批量上传
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'coverUploadQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/active/ajaxUpload',
            'multi':  false,//禁止批量上传，要一次一次的传
            'fileTypeDesc':'支持的格式：',
            'fileTypeExts':'*',
            'fileSizeLimit':'10MB',
            'removeTimeout':1,
            //检测FLASH失败调用
            'onFallback':function(){
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess':function(file, data, response){
                jsonObj=jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if(succ==1){
                    $("#coverInput").val(path);
                    $("#sCover_img").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });

        //删除封面图
        $("#coverUploadClean").click(function(){
            $("#coverInput").val();
            $("#sCover_img").attr('src','');
        })

        //来源平台-头像上传
        $("#sourceHeadUpload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            'multi':true,//允许批量上传
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'sourceHeadUploadQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/active/ajaxUpload',
            'multi':  false,//禁止批量上传，要一次一次的传
            'fileTypeDesc':'支持的格式：',
            'fileTypeExts':'*',
            'fileSizeLimit':'10MB',
            'removeTimeout':1,
            //检测FLASH失败调用
            'onFallback':function(){
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess':function(file, data, response){
                jsonObj=jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if(succ==1){
                    $("#sourceHeadInput").val(path);
                    $("#sourceHead_img").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });

        //删除来源平台
        $("#sourceHeadUploadClean").click(function(){
            $("#sourceHeadInput").val('');
            $("#sourceHead_img").attr('src','');
        })

        //分享-图片上传
        $("#shareLogoUpload").uploadify({
            //开启调试
            'debug' : false,
            //是否自动上传
            'auto':true,
            'buttonText':'上传',
            'multi':true,//允许批量上传
            //flash
            'swf': "/assets/js/uploadify/uploadify.swf",
            //文件选择后的容器ID
            'queueID':'shareLogoUploadQueue',
            'fileObjName':'UpLoadFile',
            'uploader':'/active/ajaxUpload',
            'multi':  false,//禁止批量上传，要一次一次的传
            'fileTypeDesc':'支持的格式：',
            'fileTypeExts':'*',
            'fileSizeLimit':'10MB',
            'removeTimeout':1,
            //检测FLASH失败调用
            'onFallback':function(){
                alert("您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            //上传到服务器，服务器返回相应信息到data里
            'onUploadSuccess':function(file, data, response){
                jsonObj=jQuery.parseJSON(data)
                path = jsonObj.path;
                succ = jsonObj.success
                if(succ==1){
                    $('#shareLogoInput').val(path);
                    $("#shareLogo_img").attr('src',path);
                }else{
                    alert('图片上传失败')
                }
            }
        });

        //分享图片 删除
        $("#shareLogoUploadClean").click(function(){
            $('#shareLogoInput').val('');
            $("#shareLogo_img").attr('src','');
        })

    })
</script>
    <?php $this->endWidget(); ?>
