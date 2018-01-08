<?php
/* @var $this FilmFestivalController */
/* @var $model FilmFestival */
/* @var $form CActiveForm */
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.validate.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.form.js");
//时间控件
Yii::app()->getClientScript()->registerScriptFile("/assets/js/laydate/laydate.js");
//弹窗控件
Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
//
Yii::app()->getClientScript()->registerScriptFile("/assets/js/UploadImg.js");
//时间多选
Yii::app()->getClientScript()->registerScriptFile("/assets/js/Kalendae/build/kalendae.standalone.js");
?>
<link rel="stylesheet" type="text/css" href="/assets/js/Kalendae/build/kalendae.css">
<style type="text/css">
    .error {
        color: #ce4844;
    }

    #voiceDel {
        margin-left: 5px;
        margin-top: 3px;
    }

    .none-padding {
        padding-left: 0px !important;
        padding-right: 0px !important;
        width: 20% !important;
    }

    .file {
        position: relative;
        display: inline-block;
        background: #6fb3e0;
        border: 1px solid #6fb3e0;
        padding: 4px 12px;
        overflow: hidden;
        color: #FFF;
        text-decoration: none;
        text-indent: 0;
        line-height: 20px;
    }

    .file input {
        position: absolute;
        font-size: 100px;
        right: 0;
        top: 0;
        opacity: 0;
    }

    .file:hover {
        background: #1c84c6;
        border-color: #78C3F3;
        color: #FFF;
        text-decoration: none;
    }
</style>
<link rel="stylesheet" type="text/css" href="">

<div class="form">
    <div class="row">
        <div class="col-xs-12 form-horizontal">
            <div class="form-group">
                <label class="col-sm-3 control-label no-padding-right required" for="filmfest_name_show">电影节名称</label>
                <div class="col-sm-9">
                    <input class="col-xs-8" readonly="readonly"
                           value="<?php echo isset($model->filmfest_name) ? $model->filmfest_name : ''; ?>"
                           id="filmfest_name_show" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-10">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active none-padding">
                                <a class="text-center" data-toggle="tab" href="#tab-1" aria-expanded="true">
                                    <label>运营编辑</label>
                                </a>
                            </li>
                            <li class="none-padding">
                                <a class="text-center" data-toggle="tab" href="#tab-2" aria-expanded="false">
                                    <label>片单创建</label>
                                </a>
                            </li>
                            <li class="none-padding">
                                <a class="text-center" <?php echo isset($model->id)?'id="movieListContainer"':''?> data-toggle="tab" href="#tab-3"
                                   aria-expanded="false">
                                    <label>影片列表</label>
                                </a>
                            </li>
                            <li class="none-padding">
                                <a class="text-center" data-toggle="tab" href="#tab-4" aria-expanded="false">
                                    <label>展映单元</label>
                                </a>
                            </li>
                            <li class="none-padding">
                                <a class="text-center" data-toggle="tab" href="#tab-5" aria-expanded="false">
                                    <label>影院列表</label>
                                </a>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <form id="film-festival-form" method="post" enctype="multipart/form-data">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="filmfest_name">电影节名称
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[filmfest_name]"
                                                       value="<?php echo isset($model->filmfest_name) ? $model->filmfest_name : ''; ?>"
                                                       id="filmfest_name" type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="url_param">参数设置
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[url_param]"
                                                       value="<?php echo isset($model->url_param) ? $model->url_param : ''; ?>"
                                                       id="url_param"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="bisServerid">bisServerid<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[bisServerid]"
                                                       value="<?php echo isset($model->bisServerid) ? $model->bisServerid : ''; ?>"
                                                       id="bisServerid"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required">城市ID<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[city_ids]"
                                                       value="<?php echo isset($model->city_ids) ? $model->city_ids : ''; ?>"
                                                       id="city_ids"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required">城市名称<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[city_name]"
                                                       value="<?php echo isset($model->city_name) ? $model->city_name : ''; ?>"
                                                       id="city_name"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required">顶部推荐</label>
                                            <div class="col-sm-10">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label" for="open_top">是否展示<span
                                                            class="required">*</span></label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="open_top1" value="1"
                                                            <?php echo isset($model->open_top) && $model->open_top == 1 ? 'checked' : ''; ?>
                                                               name="FilmFestival[open_top]">
                                                        <label for="open_top1">是&nbsp;</label>
                                                    </div>
                                                    &nbsp;
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="open_top2" value="0"
                                                            <?php echo isset($model->open_top) && $model->open_top == 0 ? 'checked' : ''; ?>
                                                               name="FilmFestival[open_top]">
                                                        <label for="open_top2">否&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"
                                                           for="categoryName">分类名称</label>
                                                    <div class="col-sm-8">
                                                        <input class="col-xs-12" name="FilmFestival[categoryName]"
                                                               value="<?php echo isset($model->top_info->categoryName) ? $model->top_info->categoryName : ''; ?>"
                                                               id="categoryName"
                                                               type="text">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10">
                                                        <table class="table table-bordered" id="top_info">
                                                            <tbody>
                                                            <tr>
                                                                <th>排序</th>
                                                                <th>影片ID</th>
                                                                <th>推荐语</th>
                                                                <th>操作</th>
                                                            </tr>
                                                            <?php
                                                            if (isset($model->top_info->topInfoData)) {
                                                                foreach ($model->top_info->topInfoData as $topInfo) {
                                                                    ?>
                                                                    <tr>
                                                                        <td width="10%">
                                                                            <input class="col-xs-12 sort_num"
                                                                                   value="<?php echo $topInfo->sort; ?>"
                                                                                   name="FilmFestival[top_info][sort][]"
                                                                                   type="text">
                                                                        </td>
                                                                        <td width="30%">
                                                                            <input class="col-xs-12"
                                                                                   value="<?php echo $topInfo->movieId; ?>"
                                                                                   name="FilmFestival[top_info][movieId][]"
                                                                                   type="text">
                                                                        </td>
                                                                        <td width="50%">
                                                                            <input class="col-xs-12"
                                                                                   value="<?php echo $topInfo->describe; ?>"
                                                                                   name="FilmFestival[top_info][describe][]"
                                                                                   type="text">

                                                                        </td>
                                                                        <td width="10%">
                                                                            <a href="javascript:void(0)"
                                                                               onclick="tableDel(this)">删除</a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <tr class="last_tr">
                                                                <td colspan="5">
                                                                    <input class="btn btn-info btn-sm" id="btn_add"
                                                                           onclick="tableAdd('top_info')"
                                                                           type="button"
                                                                           value="添加">
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="top_bar_chart">顶部条图<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10 ">
                                                <div>
                                                    <img id="top_bar_chart_img" style="max-width: 600px;height:36px"
                                                         src="<?php echo isset($model->top_bar_chart) ? $model->top_bar_chart : ''; ?>"/>
                                                    <input type="hidden" name="FilmFestival[top_bar_chart]"
                                                           value="<?php echo isset($model->top_bar_chart) ? $model->top_bar_chart : ''; ?>"
                                                    />
                                                </div>
                                                <br>
                                                <div class="file ">选择文件
                                                    <input type="file" id="top_bar_chart_file"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                class="col-sm-2 control-label no-padding-right required">电影节介绍</label>
                                            <div class="col-sm-10">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">是否展示
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="open_introduce1" value="1"
                                                            <?php echo isset($model->open_introduce) && $model->open_introduce == 1 ? 'checked' : ''; ?>
                                                               name="FilmFestival[open_introduce]">
                                                        <label for="open_introduce1">是&nbsp;</label>
                                                    </div>
                                                    &nbsp;
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="open_introduce2" value="0"
                                                            <?php echo isset($model->open_introduce) && $model->open_introduce == 0 ? 'checked' : ''; ?>
                                                               name="FilmFestival[open_introduce]">
                                                        <label for="open_introduce2">否&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-10" id="">
                                                        <table class="table table-bordered" width="100%">
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="col-sm-2 control-label no-padding-right required">背景色</label>
                                                                        <div class="col-sm-10">
                                                                            <input class="col-xs-10"
                                                                                   name="FilmFestival[introduce][background]"
                                                                                   value="<?php echo isset($model->introduce->background) ? $model->introduce->background : ''; ?>"
                                                                                   type="text">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="col-sm-2 control-label no-padding-right required">顶部图片</label>
                                                                        <div class="col-sm-10 ">
                                                                            <div>
                                                                                <img id="introduce_top_img"
                                                                                     style="max-width:600px"
                                                                                     src="<?php echo isset($model->introduce->top) ? $model->introduce->top : ''; ?>"
                                                                                />
                                                                                <input type="hidden"
                                                                                       name="FilmFestival[introduce][top]"
                                                                                       value="<?php echo isset($model->introduce->top) ? $model->introduce->top : ''; ?>"
                                                                                />
                                                                            </div>
                                                                            <br>
                                                                            <div class="file ">选择文件
                                                                                <input type="file"
                                                                                       id="introduce_top_file">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="col-sm-2 control-label no-padding-right required">文案编辑</label>
                                                                        <div class="col-sm-10">
                                                                    <textarea class="col-xs-10"
                                                                              name="FilmFestival[introduce][copy]"
                                                                              rows="3"
                                                                              type="text"><?php echo isset($model->introduce->copy) ? $model->introduce->copy : ''; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required">
                                                电影节日程</label>
                                            <div class="col-sm-10">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">是否展示
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="open_schedule1" value="1"
                                                               name="FilmFestival[open_schedule]"
                                                            <?php echo isset($model->open_schedule) && $model->open_schedule == 1 ? 'checked' : ''; ?>
                                                        />
                                                        <label for="open_schedule1">是&nbsp;</label>
                                                    </div>
                                                    &nbsp;
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="open_schedule2" value="0"
                                                            <?php echo isset($model->open_schedule) && $model->open_schedule == 0 ? 'checked' : ''; ?>
                                                               name="FilmFestival[open_schedule]">
                                                        <label for="open_schedule2">否&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-10" id="">
                                                        <table class="table table-bordered" width="100%">
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="col-sm-2 control-label no-padding-right required">背景色</label>
                                                                        <div class="col-sm-10">
                                                                            <input class="col-xs-10"
                                                                                   name="FilmFestival[schedule][background]"
                                                                                   value="<?php echo isset($model->schedule->background) ? $model->schedule->background : ''; ?>"
                                                                                   type="text">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="col-sm-2 control-label no-padding-right required">顶部图片</label>
                                                                        <div class="col-sm-10">
                                                                            <div>
                                                                                <img id="schedule_top_img"
                                                                                     style="max-width:600px"
                                                                                     src="<?php echo isset($model->schedule->top) ? $model->schedule->top : ''; ?>"
                                                                                />
                                                                                <input type="hidden"
                                                                                       name="FilmFestival[schedule][top]"
                                                                                       value="<?php echo isset($model->schedule->top) ? $model->schedule->top : ''; ?>"
                                                                                />
                                                                            </div>
                                                                            <br>
                                                                            <div class="file ">选择文件
                                                                                <input type="file"
                                                                                       id="schedule_top_file"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="col-sm-2 control-label no-padding-right required"
                                                                        >文案编辑</label>
                                                                        <div class="col-sm-10">
                                                                        <textarea class="col-xs-10"
                                                                                  name="FilmFestival[schedule][copy]"
                                                                                  rows="3"
                                                                                  type="text"><?php echo isset($model->schedule->copy) ? $model->schedule->copy : ''; ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="share_icon">分享图标<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10 ">
                                                <div>
                                                    <img id="share_icon_img" style="width: 200px;height: 200px"
                                                         src="<?php echo isset($model->share_icon) ? $model->share_icon : ''; ?>"
                                                    />
                                                    <input type="hidden" name="FilmFestival[share_icon]"
                                                           value="<?php echo isset($model->share_icon) ? $model->share_icon : ''; ?>"
                                                    />
                                                </div>
                                                <br>
                                                <div class="file ">选择文件
                                                    <input type="file" id="share_icon_file"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="share_title">分享标题<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[share_title]"
                                                       id="share_title"
                                                       value="<?php echo isset($model->share_title) ? $model->share_title : ''; ?>"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="share_describe">分享描述<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[share_describe]"
                                                       value="<?php echo isset($model->share_describe) ? $model->share_describe : ''; ?>"
                                                       id="share_describe"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="visual_color">主视觉色<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-10">
                                                <input class="col-xs-10" name="FilmFestival[visual_color]"
                                                       id="visual_color"
                                                       value="<?php echo isset($model->visual_color) ? $model->visual_color : ''; ?>"
                                                       type="text">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required"
                                                   for="ticket_time">购票时间<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-4">
                                                <input placeholder="购票时间"
                                                       class="form-control layer-date laydate-icon"
                                                       value="<?php echo isset($model->ticket_time) ? $model->ticket_time : ''; ?>"
                                                       id="ticket_time" name="FilmFestival[ticket_time]">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required">上线时间<span
                                                    class="required">*</span></label>
                                            <div class="col-sm-4">
                                                <input placeholder="开始时间"
                                                       class="form-control layer-date laydate-icon"
                                                       value="<?php echo isset($model->start_time) ? $model->start_time : ''; ?>"
                                                       id="start_time" name="FilmFestival[start_time]">
                                            </div>
                                            <div class="col-sm-4">
                                                <input placeholder="结束时间"
                                                       class="form-control laydate-icon layer-date"
                                                       value="<?php echo isset($model->end_time) ? $model->end_time : ''; ?>"
                                                       id="end_time" name="FilmFestival[end_time]">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label no-padding-right required">
                                                异常短信</label>
                                            <div class="col-sm-10">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">是否生效<span
                                                            class="required">*</span></label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="open_abnormal1" value="1"
                                                            <?php echo isset($model->open_abnormal) && $model->open_abnormal == 1 ? 'checked' : ''; ?>
                                                               name="FilmFestival[open_abnormal]">
                                                        <label for="open_abnormal1">是&nbsp;</label>
                                                    </div>
                                                    &nbsp;
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="inlineRadio2" value="0"
                                                            <?php echo isset($model->open_abnormal) && $model->open_abnormal == 0 ? 'checked' : ''; ?>
                                                               name="FilmFestival[open_abnormal]">
                                                        <label for="inlineRadio2">否&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-xs-10">
                                                        <table class="table table-bordered" width="100%"
                                                               id="abnormal_SMS">
                                                            <tbody>
                                                            <tr>
                                                                <th>影院ID</th>
                                                                <th>短信文案</th>
                                                                <th>操作</th>
                                                            </tr>
                                                            <?php
                                                            if (isset($model->abnormal_SMS) && $model->open_abnormal) {
                                                                foreach ($model->abnormal_SMS as $SMS) {
                                                                    ?>
                                                                    <tr>
                                                                        <td width="30%">
                                                                            <input class="col-xs-12"
                                                                                   value="<?php echo isset($SMS->cinemaId) ? $SMS->cinemaId : ''; ?>"
                                                                                   name="FilmFestival[SMS][cinemaId][]"
                                                                                   type="text">
                                                                        </td>
                                                                        <td width="60%">
                                                                            <input class="col-xs-12"
                                                                                   value="<?php echo isset($SMS->copy) ? $SMS->copy : ''; ?>"
                                                                                   name="FilmFestival[SMS][copy][]"
                                                                                   type="text">
                                                                        </td>
                                                                        <td width="10%">
                                                                            <a href="javascript:void(0)"
                                                                               onclick="tableDel(this)">删除</a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <tr class="last_tr">
                                                                <td colspan="5">
                                                                    <input class="btn btn-info btn-sm" id="btn_add"
                                                                           onclick="tableAdd('abnormal_SMS')"
                                                                           type="button"
                                                                           value="添加">
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <input name='FilmFestival[id]'
                                                               id="id" ;
                                                               type="hidden"
                                                               value="<?php echo isset($model->id) ? $model->id : '' ?>"/>
                                                        <input class="btn btn-info pull-right" id="btn_add"
                                                               type="submit"
                                                               value="保存"></td>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <div class="col-sm-12" id="single_chip">
                                        <form method="post" enctype="multipart/form-data"
                                              action="/filmFestivalSingleChip/ajaxCreate/" id="single_chip_form">
                                            <table class="table table-bordered" id="single_chip" width="100%">
                                                <tbody>
                                                <tr id="singleChip_tr">
                                                    <th>排序</th>
                                                    <th>设置片单</th>
                                                    <th>操作</th>
                                                </tr>
                                                <!-- 循环读取数据库数据 -->
                                                <?php
                                                if (isset($singleChip)) {
                                                    foreach ($singleChip as $k => $v) {
                                                        ?>
                                                        <tr class="items">
                                                            <td width="10%">
                                                                <input type="hidden" name="single_chip_id[]"
                                                                       value="<?php echo $v['id'] ?>"/>
                                                                <input name="sort_num[]" type="text" class="sort_num"
                                                                       value="<?php echo $v['sort_num'] ?>"
                                                                       style="width:100%">
                                                            </td>
                                                            <td width="80%">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="col-sm-2 control-label no-padding-right required"
                                                                        for="share_describe">标题
                                                                        <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="col-xs-10" name="title[]"
                                                                               type="text"
                                                                               value="<?php echo $v['title']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="col-sm-2 control-label no-padding-right required"
                                                                        for="share_describe">描述
                                                                        <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="col-xs-10" name="brief[]"
                                                                               type="text"
                                                                               value="<?php echo $v['brief']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="col-sm-2 control-label no-padding-right required"
                                                                        for="share_describe">作者名
                                                                        <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="col-xs-10" name="author_name[]"
                                                                               type="text"
                                                                               value="<?php echo $v['author_name']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="col-sm-2 control-label no-padding-right required"
                                                                        for="share_describe">影片ID
                                                                        <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <input class="col-xs-10" name="movie_id[]"
                                                                               type="text"
                                                                               value="<?php echo $v['movie_id']; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="col-sm-2 control-label no-padding-right required"
                                                                        for="share_describe">封面图
                                                                        <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <div class="cover_map">
                                                                            <img id="cover_map" class="img_items"
                                                                                 style="height:110px;width:166px"
                                                                                 src="<?php echo $v['cover_map']; ?>"/>
                                                                            <input type="hidden" name="cover_map[]"
                                                                                   value="<?php echo $v['cover_map']; ?>">
                                                                        </div>
                                                                        <br>
                                                                        <div class="file">选择文件
                                                                            <input type="file"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        class="col-sm-2 control-label no-padding-right required"
                                                                        for="share_describe">作者头像
                                                                        <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-sm-10">
                                                                        <div class="author_portrait">
                                                                            <img id="author_portrait" class="img_items"
                                                                                 style="width:200px;height: 200px"
                                                                                 src="<?php echo $v['author_portrait']; ?>"/>
                                                                            <input type="hidden"
                                                                                   name="author_portrait[]"
                                                                                   value="<?php echo $v['author_portrait']; ?>">
                                                                        </div>
                                                                        <br>
                                                                        <div class="file ">选择文件
                                                                            <input type="file"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="javascript:void(0)"
                                                                   onclick="tableDel2(this,<?php echo $v['id']; ?>, '/filmFestivalSingleChip/ajaxDel/')">删除</a>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                                <tr class="last_tr">
                                                    <td colspan="5">
                                                        <input type="hidden" name="film_festival_id"
                                                               value="<?php echo $model->id; ?>"/>
                                                        <input class="btn btn-info btn-sm" id="btn_add" type="button"
                                                               onclick="tableAdd('single_chip',true)"
                                                               value="添加"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <input class="btn btn-info pull-right" id="btn_save_single_chip"
                                                   type="button"
                                                   value="保存"></td>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-3" class="tab-pane">
                                <div class="panel-body">
                                    <div class="col-sm-12" id="movie_list">
                                        <form id="movie_list_form" method="post"
                                              action="/filmFestivalMovieList/ajaxCreate/">
                                            <table class="table table-bordered" id="movie_list" width="100%">
                                                <tbody>
                                                <tr id="movieList_tr">
                                                    <th>排序</th>
                                                    <th>影片ID</th>
                                                    <th>影片名</th>
                                                    <th>排片时间</th>
                                                    <th>操作</th>
                                                </tr>
                                                <tr class="last_tr">
                                                    <td colspan="5">
                                                        <input class="btn btn-info btn-sm" id="btn_add" type="button"
                                                               onclick="tableAdd('movie_list',false,true)" value="添加">
                                                        <input type="hidden" name="film_festival_id"
                                                               value="<?php echo $model->id; ?>"/>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                        <input class="btn btn-info pull-right" id="btn_save_movie_list" type="submit"
                                               value="保存"></td>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-4" class="tab-pane">
                                <div class="panel-body">
                                    <div class="col-sm-12" id="screening_unit">
                                        <form action="/filmFestivalScreeningUnit/ajaxCreate/" method="post"
                                              id="screening_unit_form">
                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr id="screeningUnit_tr">
                                                    <th>排序</th>
                                                    <th>一级单元</th>
                                                    <th>二级单元</th>
                                                    <th>影片id</th>
                                                    <th>操作</th>
                                                </tr>
                                                <?php
                                                if (isset($screeningUnit)) {
                                                    foreach ($screeningUnit as $k => $v) {
                                                        ?>
                                                        <tr>
                                                            <td width="10%">
                                                                <input type="text" class="sort_num col-sm-12"
                                                                       name="sort_num[]"
                                                                       value="<?php echo $v['sort_num']; ?>"></td>
                                                            <td width="20%">
                                                                <input name="level1_unit[]" type="text"
                                                                       class="col-sm-12"
                                                                       value="<?php echo $v['level1_unit']; ?>">
                                                            </td>
                                                            <td width="20%">
                                                                <input name="level2_unit[]" type="text"
                                                                       class="col-sm-12"
                                                                       value="<?php echo $v['level2_unit']; ?>">
                                                            </td>
                                                            <td width="40%">
                                                                <input name="movie_id[]" type="text"
                                                                       class="col-sm-12 movie_ids"
                                                                       value="<?php echo $v['movie_id']; ?>">
                                                            </td>
                                                            <td width="10%">
                                                                <a href="javascript:void(0)"
                                                                   onclick="tableDel2(this,<?php echo $v['id']; ?>,'/filmFestivalScreeningUnit/ajaxDel/')">删除</a>
                                                                <input type="hidden" name="screening_unit_id[]"
                                                                       value="<?php echo $v['id']; ?>"/>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                                <tr class="last_tr">
                                                    <td colspan="5">
                                                        <input class="btn btn-info btn-sm" id="btn_add" type="button"
                                                               value="添加" onclick="tableAdd('screening_unit')"></td>
                                                    <input type="hidden" name="film_festival_id"
                                                           value="<?php echo $model->id; ?>"/>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                        <input class="btn btn-info pull-right" id="btn_save_screening_unit"
                                               type="button"
                                               value="保存"></td>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-5" class="tab-pane">
                                <div class="panel-body">
                                    <div class="col-sm-12" id="cinema_list">
                                        <form method="post" action="/filmFestivalCinemaList/ajaxCreate/"
                                              id="cinema_list_form">
                                            <table class="table table-bordered" id="cinema_list">
                                                <tbody>
                                                <tr id="cinemaList_tr">
                                                    <th>排序</th>
                                                    <th>影院ID</th>
                                                    <th>影院名</th>
                                                    <th>操作</th>
                                                </tr>
                                                <?php
                                                if (isset($cinemaList)) {
                                                    foreach ($cinemaList as $k => $v) {
                                                        ?>
                                                        <tr class="cinema_tr">
                                                            <td width="10%">
                                                                <input type="text" class="sort_num col-sm-12"
                                                                       name="sort_num[]"
                                                                       value="<?php echo $v['sort_num']; ?>">
                                                            </td>
                                                            <td width="30%">
                                                                <input name="cinema_id[]" type="text"
                                                                       class="col-sm-12"
                                                                       value="<?php echo $v['cinema_id']; ?>">
                                                            </td>
                                                            <td width="50%">
                                                                <input name="cinema_name[]" type="text"
                                                                       class="col-sm-12"
                                                                       value="<?php echo $v['cinema_name']; ?>">
                                                            </td>
                                                            <td width="10%">
                                                                <input type="hidden" name="cinema_list_id[]"
                                                                       value="<?php echo $v['id']; ?>"/>
                                                                <a href="javascript:void(0)"
                                                                   onclick="tableDel2(this,<?php echo $v['id']; ?>,'/filmFestivalCinemaList/ajaxDel/')">删除</a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                <tr class="last_tr">
                                                    <td colspan="5">
                                                        <input type="hidden" name="film_festival_id"
                                                               value="<?php echo $model->id; ?>"/>
                                                        <input class="btn btn-info btn-sm" id="btn_add" type="button"
                                                               value="添加" onclick="tableAdd('cinema_list')"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                        <input class="btn btn-info pull-right" id="btn_save_cinema_list" type="button"
                                               value="保存"></td>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 各个table组件 -->
<!-- 顶部推荐 -->
<script type="text/html" style="display: none" id="top_info_tr">
    <tr>
        <td width="10%">
            <input class="col-xs-12 sort_num" name="FilmFestival[top_info][sort][]" type="text">
        </td>
        <td width="30%">
            <input class="col-xs-12" name="FilmFestival[top_info][movieId][]" type="text">
        </td>
        <td width="50%">
            <input class="col-xs-12" name="FilmFestival[top_info][describe][]" type="text">
        </td>
        <td width="10%">
            <a href="javascript:void(0)" onclick="tableDel(this)">删除</a>
        </td>
    </tr>
</script>
<!-- 异常短信 -->
<script type="text/html" style="display: none" id="abnormal_SMS_tr">
    <tr>
        <td width="30%">
            <input class="col-xs-12" name="FilmFestival[SMS][cinemaId][]" type="text">
        </td>
        <td width="60%">
            <input class="col-xs-12" name="FilmFestival[SMS][copy][]" type="text">
        </td>
        <td width="10%">
            <a href="javascript:void(0)" onclick="tableDel(this)">删除</a>
        </td>
    </tr>
</script>
<!-- 片单列表 -->
<script type="text/html" style="display: none" id="single_chip_tr">
    <tr class="items">
        <td width="10%">
            <input name="sort_num[]" type="text" class="sort_num" value="1" style="width:100%">
        </td>
        <td width="80%">
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right required"
                       for="share_describe">标题
                    <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    <input class="col-xs-10" name="title[]" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right required"
                       for="share_describe">描述
                    <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    <input class="col-xs-10" name="brief[]" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right required"
                       for="share_describe">作者名
                    <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    <input class="col-xs-10" name="author_name[]" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right required"
                       for="share_describe">影片ID
                    <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    <input class="col-xs-10" name="movie_id[]" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right required"
                       for="share_describe">封面图
                    <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    <div class="cover_map">
                        <img id="cover_map" class="img_items" style="width:166px;height:110px"/>
                        <input type="hidden" name="cover_map[]"/>
                    </div>
                    <br>
                    <div class="file ">选择文件
                        <input type="file" id="cover_map_file"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label no-padding-right required"
                       for="share_describe">作者头像
                    <span class="required">*</span>
                </label>
                <div class="col-sm-10">
                    <div class="author_portrait">
                        <img id="author_portrait" class="img_items" style="width:200px;height:200px"/>
                        <input type="hidden" name="author_portrait[]"/>
                    </div>
                    <br>
                    <div class="file ">选择文件
                        <input type="file"/>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <a href="javascript:void(0)" onclick="tableDel(this)">删除</a>
        </td>
    </tr>
</script>
<!-- 影片列表 -->
<script type="text/html" style="display: none" id="movie_list_tr">
    <tr>
        <td width="10%">
            <input type="text" class="col-sm-12 sort_num" name="sort_num[]">
        </td>
        <td width="20%">
            <input name="movie_id[]" class="col-sm-12" type="text">
        </td>
        <td width="20%">
            <input name="movie_name[]" class="col-sm-12" type="text" readonly="readonly">
        </td>
        <td width="40%">
            <input name="row_piece_time[]" type="text"
                   data-kal="mode:'multiple',format:'YYYY-MM-DD',multipleDelimiter:';'"
                   class="piece_time auto-kal col-sm-12" type="text">
        </td>
        <td width="10%">
            <a href="javascript:void(0)" onclick="tableDel(this)">删除</a>
        </td>
    </tr>
</script>
<!-- 展映单元 -->
<script type="text/html" style="display:none" id="screening_unit_tr">
    <tr>
        <td width="10%">
            <input type="text" class="sort_num col-sm-12" name="sort_num[]">
        </td>
        <td width="20%">
            <input name="level1_unit[]" type="text" class="col-sm-12"">
        </td>
        <td width="20%">
            <input name="level2_unit[]" type="text" class="col-sm-12">
        </td>
        <td width="40%">
            <input name="movie_id[]" type="text" class="movie_ids col-sm-12">
        </td>
        <td width="10%">
            <a href="javascript:void(0)" onclick="tableDel(this)">删除</a>
        </td>
    </tr>
</script>
<!-- 影院列表 -->
<script type="text/html" style="display: none" id="cinema_list_tr">
    <tr class="cinema_tr">
        <td width="10%">
            <input type="text" class="sort_num col-sm-12" name="sort_num[]">
        </td>
        <td width="30%">
            <input name="cinema_id[]" type="text" class="col-sm-12">
        </td>
        <td width="50%">
            <input name="cinema_name[]" type="text" class="col-sm-12">
        </td>
        <td width="10%">
            <a href="javascript:void(0)" onclick="tableDel(this)">删除</a>
        </td>
    </tr>
</script>
<div id="showMovieName" class="text-center modal-body" style="padding:20px;">
    <strong id="showMovieNameList"></strong>
</div>
<script>
    $(function () {
        //数据单向绑定
        $('#filmfest_name').bind('input propertychange', function () {
            var _filmfest_name = $(this).val();
            $('#filmfest_name_show').val(_filmfest_name);
        });
    })
    /*时间控件*/
    //初始化时间配置
    //购票时间
    var ticket = {
        elem: '#ticket_time',
        format: 'YYYY-MM-DD hh:mm:ss',
        max: '2099-06-16 23:59:59', //最大日期
        istime: true,
        istoday: false,
    }
    //日期范围限制
    var start = {
        elem: '#start_time',
        format: 'YYYY-MM-DD hh:mm:ss',
        min: laydate.now(), //设定最小日期为当前日期
        max: '2099-06-16 23:59:59', //最大日期
        istime: true,
        istoday: false,
        choose: function (datas) {
            end.min = datas; //开始日选好后，重置结束日的最小日期
            end.start = datas //将结束日的初始值设定为开始日
            var _end_time = $("#end_time").val();
            $("#end_time").val('');
            if (_end_time.length > 0) {
                layer.msg('请重新选择结束时间!');
            }
        }
    };
    var oldStartTime = $('#start_time').val();
    var end = {
        elem: '#end_time',
        format: 'YYYY-MM-DD hh:mm:ss',
        max: '2099-06-16 23:59:59',
        min: oldStartTime,
        istime: true,
        istoday: false,
        choose: function (datas) {
            //start.max = datas; //结束日选好后，重置开始日的最大日期
        }
    };
    //执行设置
    laydate(start);
    laydate(end);
    laydate(ticket);
    /*刷新时间选择器*/
    $(document).on('click', '.auto-kal', function () {
        if ($(this).attr('hasPle') != undefined) {
            return true;
        }
        new Kalendae.Input(this, {mode: 'multiple', format: 'YYYY-MM-DD', multipleDelimiter: ';'}).show();
        $(this).attr('hasPle', 1);
    })
    function pieceTimeInitialization() {
        $('.auto-kal').each(function () {
            new Kalendae.Input(this, {mode: 'multiple', format: 'YYYY-MM-DD', multipleDelimiter: ';'});
        })
    }
    /*table添加*/
    function tableAdd(tableId, _initialize=false, _timeInitialize=false) {
        if (!tableId) {
            return false;
        }
        var _trName = tableId + '_tr';
        var _trValue = $('#' + _trName).html();

        var index = $('#' + tableId).find(".sort_num").length;
        var pdSort = index + 1;

        $('#' + tableId).find('.last_tr').before(_trValue);

        //设置默认排序id值
        $('#' + tableId).find("input[name^='cover_map']").eq(index).attr('name', "cover_map[" + index + "]");
        $('#' + tableId).find("input[name^='author_portrait']").eq(index).attr('name', "author_portrait[" + index + "]");
        $("#" + tableId).find(".sort_num").eq(index).val(pdSort);

        //初始化图片加载
        if (_initialize) {
            tableImgInitialize(tableId);
        }
        //初始化时间
        if (_timeInitialize) {
            //pieceTimeInitialization();
        }
    }
    //异步加载板块
    $(document).on('click', "#movieListContainer", function () {
        layer.msg('加载中', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
        $.get("<?php echo Yii::app()->getController()->createUrl('filmFestival/ajaxLoadMovieList/' . $model->id)?>", function (result) {
            $('#movieList_tr').nextAll("tr[class!=last_tr]").remove();
            $('#movieList_tr').after(result);
            //pieceTimeInitialization();
            layer.closeAll();
        });
    });
    //table初始化图片加载
    function tableImgInitialize(tableId) {
        if (!tableId) {
            return false;
        }
        $('#' + tableId).find("tr[class='items']").each(function (top_index) {
            $(this).find('.img_items').each(
                function (index) {
                    var img_id = $(this).attr('id') + '_' + top_index;
                    $(this).attr('id', img_id);
                    $(this).parent().nextAll('div').find('input').attr('id', img_id + '_file');

                    new uploadPreview({
                        UpBtn: img_id + '_file',
                        ImgShow: img_id,
                        ImgType: ['jpg'],
                        ErrMsg: '选择文件错误,图片类型必须是jpg'
                    });
                }
            );
        });
    }
    //片单保存
    $("#btn_save_single_chip").click(function () {
        layer.msg('提交中', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
        //表单验证
        var single_chip_items = $("#single_chip_form").find('.items');
        $("#single_chip_form").ajaxSubmit({
            success: function (data) {
                if (data.status == 200) {
                    $.get("<?php echo Yii::app()->getController()->createUrl('filmFestivalSingleChip/ajaxLoadList/' . $model->id)?>", function (result) {
                        $('#singleChip_tr').nextAll("tr[class!=last_tr]").remove();
                        $('#singleChip_tr').after(result);
                        tableImgInitialize('single_chip');
                    });
                } else {
                    if (typeof(data.data.name) != "undefined" && typeof(data.data.name) != "undefined") {
                        var single_chip_item = single_chip_items.find("input[name='" + data.data.name + "[]']").eq(data.data.i);
                        layer.tips(data.msg, single_chip_item, {
                            tips: [2, '#3595CC'],
                            time: 4000
                        });
                        single_chip_item.focus();
                    }
                }
                layer.msg(data.msg);
            },
            dataType: "json" /*设置返回值类型为文本*/
            , error: function () {
                layer.msg('操作失败');
            }
        });
        return false;
    })
    //影院保存
    $("#btn_save_cinema_list").click(function () {
        layer.msg('提交中', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
        $("#cinema_list_form").ajaxSubmit({
            success: function (data) {
                if (data.status == 200) {
                    $.get("<?php echo Yii::app()->getController()->createUrl('filmFestival/ajaxLoadCinemaList/' . $model->id)?>", function (result) {
                        $('#cinemaList_tr').nextAll("tr[class!=last_tr]").remove();
                        $('#cinemaList_tr').after(result);
                    });
                }
                layer.msg(data.msg);
            },
            dataType: "json" /*设置返回值类型为文本*/
            , error: function () {
                layer.msg('操作失败');
            }
        });
        return false;
    })
    //展映保存
    $("#btn_save_screening_unit").click(function () {
        layer.msg('提交中', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
        var screening_unit_items = $("#screeningUnit_tr").nextAll('tr');
        $("#screening_unit_form").ajaxSubmit({
            success: function (data) {
                if (data.status == 200) {
                    $.get("<?php echo Yii::app()->getController()->createUrl('filmFestival/ajaxLoadScreeningUnit/' . $model->id)?>", function (result) {
                        $('#screeningUnit_tr').nextAll("tr[class!=last_tr]").remove();
                        $('#screeningUnit_tr').after(result);
                    });
                } else {
                    if (typeof(data.data.name) != "undefined" && typeof(data.data.i) != "undefined") {
                        var screening_unit_item = screening_unit_items.find("input[name='" + data.data.name + "[]']").eq(data.data.i);
                        layer.tips(data.msg, screening_unit_item, {
                            tips: [1, '#3595CC'],
                            time: 4000
                        });
                        screening_unit_item.focus();
                    }
                }
                layer.msg(data.msg);
            },
            dataType: "json" /*设置返回值类型为文本*/
            , error: function () {
                layer.msg('操作失败');
            }
        });
        return false;
    })
    //影片保存
    $("#btn_save_movie_list").click(function () {
        //表单验证
        <?php echo !isset($model->id)?'layer.msg("请完成运营编辑");return false;':''?>
        layer.msg('提交中', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
        var movie_list_items = $("#movieList_tr").nextAll("tr[class!='last_tr']");
        var _movie_ids = new Array();
        var hasError = 0;
        var _dateTest = /^(\d{4}\-\d{2}\-\d{2})+(;?(\d{4}\-\d{2}\-\d{2})+)*$/;//date格式限制
        $(".auto-kal").each(function () {
            var autoValue = $(this).val();
            if (!_dateTest.test(autoValue) && autoValue.length > 0) {
                layer.closeAll();
                layer.tips('该排片时间格式有误!', $(this), {
                    tips: [4, '#3595CC'],
                    time: 4000
                });
                hasError = 1;
                $(this).focus();
                return false;
            }
        })
        if (hasError) {
            return false;
        }
        $("#movie_list_form").find("[name='sort_num[]'],[name='movie_id[]'],[name='movie_name[]']").each(function () {
            var errorMsg = {'sort_num[]': '排序不能为空!', 'movie_id[]': '影片ID不能为空!', 'movie_name[]': '请输入正确的影片ID'};
            var valueData = $(this).val();
            var name = $(this).attr('name');
            if ((valueData.length == 0 || valueData == '未找到影片信息') && name != 'row_piece_time[]') {
                layer.closeAll();
                layer.tips(errorMsg[name], $(this), {
                    tips: [1, '#3595CC'],
                    time: 4000
                });
                $(this).focus();
                hasError = 1;
                return false;
            }
            if (name == 'movie_id[]') {
                if ($.inArray(valueData, _movie_ids)>-1) {
                    layer.closeAll();
                    layer.tips('影片ID'+ valueData + '重复', $(this), {
                        tips: [1, '#3595CC'],
                        time: 4000
                    });
                    hasError = 1;
                    $(this).focus();
                    return false;
                } else {
                    _movie_ids.push(valueData);
                }
            }
        });
        if (hasError) {
            return false;
        }
        //数据组装
        var items_length = movie_list_items.length;
        var items_page = 100;
        var MovieAjaxCreate = function (valueData, end=0) {
            $.ajax({
                url: '/filmFestivalMovieList/ajaxCreate/',
                data: valueData,
                type: "post",
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (end) {
                        $.get("<?php echo Yii::app()->getController()->createUrl('filmFestival/ajaxLoadMovieList/' . $model->id)?>", function (result) {
                            $('#movieList_tr').nextAll("tr[class!=last_tr]").remove();
                            $('#movieList_tr').after(result);
                            //pieceTimeInitialization();
                        });
                        layer.msg('保存成功');
                    }
                },
                error: function (data) {
                    layer.msg("请求异常");
                }
            });
        }
        if (items_length > items_page) {
            var item_num = Math.ceil(items_length / items_page);
            var film_festival_id_data = '<?php echo 'film_festival_id=' . $model->id . '&';?>'
            for (var i = 1; i <= item_num; i++) {
                if (i == item_num) {
                    MovieAjaxCreate(film_festival_id_data+movie_list_items.slice((i - 1) * items_page).find('input').serialize(),1);
                } else {
                    MovieAjaxCreate(film_festival_id_data+movie_list_items.slice((i-1) * items_page, i * items_page).find('input').serialize());
                }
            }
        } else {
            MovieAjaxCreate($('#movie_list_form').serialize(), 1);
        }
        return false;
    })
    //获取影院信息
    $(document).on('blur', "input[name^='cinema_id']", function () {
        var cinemaId = $(this).val();
        var movieName = $(this).parent().parent().find("input[name^=cinema_name]");
        $.ajax({
            data: "id=" + cinemaId,
            url: "/filmFestivalCinemaList/ajaxGetCinemaInfo/",
            type: "post",
            dataType: 'json',
            success: function (data) {
                if (data.status == 200) {
                    movieName.val(data.data.name);
                }
                else if (data.status == 199) {
                    layer.msg('不存在的影院ID');
                }
            },
            error: function (data) {
                layer.msg("请求异常");
            }
        });
    });
    //自动排序
    $(document).on("change", ".sort_num", function () {
        var tr = $(this).parent().parent(),
            index = tr.index(),
            trNum = tr.parent().find('.sort_num').length,
            sortValue = $(this).val(),
            tableDom = $(this).parents('table:eq(0)');
        if (!tableDom) {
            return false;
        }
        if (sortValue > trNum || sortValue < 1) {
            layer.msg('请输入有效的排序数值');
            refreshSort('top_info');
            return false;
        }
        if (sortValue == 1) {
            tableDom.find('tr:eq(0)').after(tr);
        }
        else if (index > sortValue) {
            tableDom.find('tr:eq(' + sortValue + ')').before(tr);
        }
        else {
            tableDom.find(' tr:eq(' + sortValue + ')').after(tr);
        }
        refreshSortByDom(tableDom);
    });
    //Dom排序
    function refreshSortByDom(tableDom) {
        tableDom.find("tr td .sort_num").each(function (i) {
            $(this).val(i + 1);
        });
    }
    //Id排序
    function refreshSort(divId) {
        $("#" + divId + " tr td .sort_num").each(function (i) {
            $(this).val(i + 1);
        });
    }
    function tableDel2(tableA, id, ajaxurl) {
        var tableDom = $(tableA).parents("table:eq(0)");
        var tr = $(tableA).parent().parent();
        if (!tableA) {
            return false;
        }
        layer.confirm('确定删除该行？', {
            btn: ['确定', '取消'], //按钮
            shade: false //不显示遮罩
        }, function () {
            $.ajax({
                data: "id=" + id,
                url: ajaxurl,
                type: "get",
                dataType: 'json',
                success: function (data) {
                    if (data.status == 200) {
                        layer.msg(data.msg);
                        tr.remove();
                        layer.closeAll();
                        refreshSortByDom(tableDom);
                        // window.location.reload();
                    }
                    else {
                        layer.msg(data.msg);
                    }
                },
                error: function (data) {
                    layer.msg("请求异常");
                }
            });
        }, function () {
        });
    }
    /*table删除*/
    function tableDel(tableA) {
        if (!tableA) {
            return false;
        }
        layer.confirm('确定删除该行？', {
            btn: ['确定', '取消'], //按钮
            shade: false //不显示遮罩
        }, function () {
            var tableDom = $(tableA).parents("table:eq(0)");
            $(tableA).parent().parent().remove();
            refreshSortByDom(tableDom);
            layer.closeAll();
        }, function () {
        });
    }
    /*图片文件上传*/
    //顶部条图
    new uploadPreview({
        UpBtn: "top_bar_chart_file",
        ImgShow: "top_bar_chart_img",
        ImgType: ['png'],
        ErrMsg: '选择文件错误,图片类型必须是png'
    });
    //电影节介绍
    new uploadPreview({
        UpBtn: "introduce_top_file",
        ImgShow: "introduce_top_img",
        ImgType: ['jpg'],
        ErrMsg: '选择文件错误,图片类型必须是jpg'
    });
    //节日日程
    new uploadPreview({
        UpBtn: "schedule_top_file",
        ImgShow: "schedule_top_img",
        ImgType: ['jpg'],
        ErrMsg: '选择文件错误,图片类型必须是jpg'
    });
    //分享图标
    new uploadPreview({
        UpBtn: "share_icon_file",
        ImgShow: "share_icon_img",
        ImgType: ['jpg'],
        ErrMsg: '选择文件错误,图片类型必须是jpg'
    });
    //片单上传
    tableImgInitialize('single_chip');
    /*表单验证*/
    var icon = "&nbsp;&nbsp; <i class='fa fa-times-circle'></i>";
    //城市id输入限制
    $.validator.addMethod("checkCityId", function (value, element, params) {
        var checkCityId = /^\d+(;?\d+)*$/;
        return this.optional(element) || (checkCityId.test(value));
    }, "城市id只能为数字,或以';'分割的多个数字！");
    $("#film-festival-form").validate({
        ignore: [],//取消忽略隐藏域
        errorPlacement: function (error, element) {
            if (element.attr('type') == 'radio') {
                error.appendTo(element.parent().parent());
            } else if (element.attr('type') == 'file') {
                error.appendTo(element.parent().parent());
            } else if (element.attr('type') == 'hidden') {
                error.appendTo(element.parent().parent());
            } else {
                error.appendTo(element.parent());
            }
        },
        rules: {
            'FilmFestival[filmfest_name]': {
                required: true,
                maxlength: 100
            },
            'FilmFestival[url_param]': {
                required: true,
                maxlength: 255,
                remote: {
                    url: "/filmFestival/ajaxCheckUrlParam",
                    type: "post",
                    dataType: "json",
                    data: {
                        'url_param': function () {
                            return $('#url_param').val();
                        },
                        'id': function () {
                            return $('#id').val();
                        }
                    }
                }
            },
            'FilmFestival[bisServerid]': {
                required: true
            },
            'FilmFestival[city_ids]': {
                required: true,
                checkCityId: true
            },
            'FilmFestival[city_name]': {
                required: true,
            },
            'FilmFestival[open_top]': {
                required: true
            },
            'FilmFestival[top_bar_chart]': {
                required: true
            },
            'FilmFestival[open_introduce]': {
                required: true
            },
            'FilmFestival[share_icon]': {
                required: true
            },
            'FilmFestival[share_title]': {
                required: true
            },
            'FilmFestival[share_describe]': {
                required: true
            },
            'FilmFestival[ticket_time]': {
                required: true
            },
            'FilmFestival[start_time]': {
                required: true
            },
            'FilmFestival[end_time]': {
                required: true
            },
            'FilmFestival[open_abnormal]': {
                required: true
            },
            'FilmFestival[visual_color]': {
                required: true
            }
        },
        messages: {
            'FilmFestival[city_name]': {
                required: icon + "请输入城市名称!",
            },
            'FilmFestival[filmfest_name]': {
                required: icon + "请输入电影节名称!",
                maxlength: icon + "电影节名称最长100位!"
            },
            'FilmFestival[url_param]': {
                required: icon + "请输入URL参数!",
                remote: icon + "该参数已被占用,请确保唯一性"
            },
            'FilmFestival[bisServerid]': {
                required: icon + "请输入bisServerid!",
            },
            'FilmFestival[city_ids]': {
                required: icon + "请输入城市ID(多个;分割)!",
            },
            'FilmFestival[open_top]': {
                required: icon + "请选择是否开启顶部推荐!",
            },
            'FilmFestival[top_bar_chart]': {
                required: icon + "请选择顶部条图!",
            },
            'FilmFestival[open_introduce]': {
                required: icon + "请选择是否开启电影介绍!",
            },
            'FilmFestival[share_icon]': {
                required: icon + "请选择分享图标!",
            },
            'FilmFestival[share_title]': {
                required: icon + "请输入分享标题!",
            },
            'FilmFestival[visual_color]': {
                required: icon + "请输入主视觉色!",
            },
            'FilmFestival[ticket_time]': {
                required: icon + "请选择购票时间!",
            },
            'FilmFestival[share_describe]': {
                required: icon + "请输入分享描述!",
            },
            'FilmFestival[start_time]': {
                required: icon + "请选择起始时间!",
            },
            'FilmFestival[end_time]': {
                required: icon + "请选择结束时间!",
            },
            'FilmFestival[open_abnormal]': {
                required: icon + "请选择是否开启异常短信!",
            }
        },
        submitHandler: function (form) {
            <?php
            $url = Yii::app()->getController()->createUrl('filmFestival/create');
            if ($model->getIsNewRecord()) {
                $alert_url = Yii::app()->getController()->createUrl('filmFestival/index');
            } else {
                $alert_url = Yii::app()->getController()->createUrl('filmFestival/update/' . $model->id);
            }
            ?>
            layer.msg('提交中', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
            $(form).ajaxSubmit({
                type: 'post',
                url: '<?php echo $url?>',
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        location.href = "<?php echo $alert_url?>";
                    } else {
                        layer.msg(res.msg);
                    }
                }, error: function () {
                    layer.msg('操作失败');
                }
            });
            return false;
        }
    })
    /*异步获取影片信息*/
    $(document).on("click", ".movie_ids", function (event) {
        layer.tips('轻按Enter键查看影片名称', $(this), {
            tips: [1, '#3595CC'],
            time: 4000
        });
    });
    $(document).on("change", "#movie_list input[name='movie_id[]']", function (event) {
        var movieName;
        var nextDom = $(this).parent().next().children();
        $.ajax({
            type: 'POST',
            url: "/filmFestival/ajaxGetMovieInfo",
            data: {movieId: $(this).val()},
            success: function (result) {
                if (result.code == 0) {
                    movieName = result.data.movieName;
                } else {
                    movieName = '未找到影片信息';
                }
                nextDom.val(movieName);
            },
            dataType: 'json'
        });
    });
    var OpenIndexId = 0;
    $(document).on("keydown", ".movie_ids", function (event) {
        var _movieIds = $(this).val();
        var index = 0, movieId = 0, res = '', showMovieName = '', MovieInfo = '';
        if ((48 < event.keyCode && event.keyCode < 57) || (65 < event.keyCode && event.keyCode < 90)) {
            layer.tips('轻按Enter键查看影片名称', $(this), {
                tips: [1, '#3595CC'],
                time: 4000
            });
        }
        if (event.keyCode != 13) {
            return true;
        }
        OpenIndexId > 0 ? OpenIndexId : 0;
        var _movieIdArr = _movieIds.split(';');
        for (index in _movieIdArr) {
            movieId = _movieIdArr[index];
            if (movieId.length >= 4) {
                MovieInfo = getMovieInfo(movieId)
                showMovieName += MovieInfo + ';';
            } else {
                showMovieName += _movieIdArr[index] + ';';
            }
        }
        if (OpenIndexId > 0) {
            $("#showMovieNameList").html(showMovieName);
        } else {
            layer.closeAll();
            layer.open({
                type: 1,
                shade: false,
                title: '电影名称:', //不显示标题
                skin: 'layui-layer-lan',
                area: ['250px', '150px'],
                content: $('#showMovieName'),
                success: function (layero, index) {
                    $("#showMovieNameList").html(showMovieName);
                    OpenIndexId = index;
                },
                cancel: function () {
                    OpenIndexId = 0;
                }
            });
        }
        return false;
    });
    function getMovieInfo(movieId) {
        var _movieMap = {};
        if (typeof(_movieMap.movieId) != "undefined") {
            return _movieMap.movieId;
        } else {
            var movieName = null;
            $.ajax({
                type: 'POST',
                async: false,
                url: "/filmFestival/ajaxGetMovieInfo",
                data: {movieId: movieId},
                success: function (result) {
                    if (result.code == 0) {
                        _movieMap[result.data.movieId] = result.data.movieName;
                        movieName = result.data.movieName;
                    } else {
                        movieName = '未找到影片信息';
                    }
                },
                dataType: 'json'
            });
            return movieName;
        }
    }
</script>