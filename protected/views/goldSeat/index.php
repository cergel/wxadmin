<?php
/* @var $this GoldSeatController */

$this->breadcrumbs = array(
    '过滤条件',
);
?>

    <div class="panel panel-info">
        <div class="panel-heading">总开关</div>
        <div class="panel-body form-horizontal" style="height: 50px;">
            <div class="form-group">
                <label class="col-sm-2 right-align">锁座总开关：</label>
                <div class="col-sm-10" style="padding-top: 4px;">
                    <input type="radio" name="is_lock"
                           id="open_lock" <?php if ($restrictStatus['isLock'] === 1) echo 'checked' ?> value="1"/>
                    <label for="open_lock">开</label>
                    <input type="radio" name="is_lock"
                           id="close_lock" <?php if ($restrictStatus['isLock'] === 0) echo 'checked' ?> value="0"/>
                    <label for="close_lock">关</label>
                </div>
            </div>

        </div>
    <div class="panel-body form-horizontal" style="height: 50px;">
        <div class="form-group">
            <label class="col-sm-2 right-align" for="lock_seat_count">购票限制:</label>
            <div class="col-sm-10">
                <input id="buy_limit" value="<?php echo $restrictStatus['buyLimit'] ?>" type="text"/>
                <button id="save_buy_limit" type="button" class="btn btn-success ">
                    保存
                </button>
            </div>
        </div>
    </div>
        <div class="panel-heading">自有库存售卖时间配置</div>
        <div class="panel-body form-horizontal" style="height: 130px;">
            <div class="form-group">
                <label class="control-label col-sm-2 align-right">开场前售卖时间：</label>
                <div class="col-sm-10">
                    <input id="sale_time" value="<?php echo $restrictStatus['sale'] ?>" type="text"/>分
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2 align-right">开场前退票时间：</label>
                <div class="col-sm-10">
                    <input id="refund_time" value="<?php echo $restrictStatus['refund'] ?>" type="text"/>分
                    <button id="save_time" type="button" class="btn btn-success ">
                        保存
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-heading">过滤条件</div>
        <div class="panel-body form-horizontal">

            <div class="form-group">
                <label class="control-label col-sm-2">人工过滤开关:</label>
                <div class="col-sm-10" style="padding-top: 4px;">
                    <input type="radio" name="restrict"
                           id="open_restrict" <?php if ($restrictStatus['restrict'] === 1) echo 'checked' ?> value="1"/>
                    <label for="open_restrict">开</label>
                    <input type="radio" name="restrict"
                           id="close_restrict" <?php if ($restrictStatus['restrict'] === 0) echo 'checked' ?>
                           value="0"/>
                    <label for="close_restrict">关</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 right-align" for="lock_seat_count">单场锁座最大数量:</label>
                <div class="col-sm-10">
                    <input id="lock_seat_count" name="lock_seat_count" name value="<?php echo $restrictStatus['lockLimit'] ?>" type="text"/>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="col-sm-2 right-align" for="lock_movie">过滤影片:</label>
                <div class="col-sm-3" style="padding-top: 4px;">
                    <input type="radio" name="restrict_movie"
                           id="restrict_all_movie" <?php if ($restrictStatus['movie'] === 0) echo 'checked' ?>
                           value="0"/>
                    <label for="restrict_all_movie">全部影片</label>
                    <input type="radio" name="restrict_movie"
                           id="restrict_movie" <?php if ($restrictStatus['movie'] === 1) echo 'checked' ?>
                           value="1"/>
                    <label for="restrict_movie">仅限影片</label>
                    <input type="radio" name="restrict_movie"
                           id="restrict_other_movie" <?php if ($restrictStatus['movie'] === 2) echo 'checked' ?>
                           value="2"/>
                    <label for="restrict_other_movie">排除影片</label>
                </div>
                <div class="input-group col-sm-4" style="">
                    <input id="searchInput" type="text" class="form-control" placeholder="搜索影片">
                    <span class="input-group-btn">
                        <button id="search" class="btn btn-default" type="button"
                                style="padding-bottom: 2px;padding-top: 2px;">搜索
                        </button>
                    </span>
                </div>
            </div>
            <div id="movie_result_null" class="form-group hidden">
                <label class="col-sm-2 right-align">搜索结果:</label>
                <div class="col-sm-10 center">
                    没有您想要的结果
                </div>
            </div>
            <div class="form-group ">
                <div id="movie_result_container" class="col-sm-offset-2 col-sm-10 row "></div>
            </div>
            <div id="movie_seat_container" class="form-group hidden"></div>
            <div id="movie_list_container" class="form-group">
                <label class="col-sm-2 right-align" for="lock_movie">过滤影片集合:</label>
                <div id="restrict_movie_container" class="col-sm-10 row">
                    <?php foreach ($restrictMovies as $key => $movie): ?>
                        <div id="restrict_movie_container_<?php echo $movie['MovieNo'] ?>" class="col-sm-2 center"
                             style="height: 30px;line-height: 30px;">
                            <span><?php echo $movie['MovieNameChs'] ?></span>
                            <a movieId="<?php echo $movie['MovieNo'] ?>"
                               class="fa fa-minus-circle del_restrict_movie"></a>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 right-align" for="">过滤院线:</label>
                <div class="col-sm-4" style="padding-top: 4px;">
                    <input type="radio" name="restrict_cinema"
                           id="restrict_all_cinema" <?php if ($restrictStatus['cinema'] === 0) echo 'checked' ?>
                           value="0"/>
                    <label for="restrict_all_cinema">全部院线</label>
                    <input type="radio" name="restrict_cinema"
                           id="restrict_cinema" <?php if ($restrictStatus['cinema'] === 1) echo 'checked' ?>
                           value="1"/>
                    <label for="restrict_all_cinema">仅限院线</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 right-align" for="">过滤影院集合:</label>
                <table id="restrict_cinema_container" class="col-sm-8 table table-bordered" style="width: 500px;">
                    <tr>
                        <th>影院</th>
                        <th>操作</th>
                    </tr>
                    <?php foreach ($restrictCinemas as $key => $cinema): ?>
                        <tr>
                            <td><?php echo $cinema['cinema_name'] ?></td>
                            <td><a class="del_cinema_restrict" value="<?php echo $cinema['cinema_no'] ?>"
                                   href="javascript:;">删除</a></td>
                        </tr>
                    <?php endforeach ?>
                </table>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
                        新增院线
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" style="width:1000px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">新增院线</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="key_cinema">影院：</label>
                        <input type="text" id="key_cinema" placeholder="填写影院关键字"/>
                        <button id="search_cinema" class="btn btn-success">查询</button>
                        <br>
                        <label for="city">城市:</label>
                        <span>
                            <?php $this->widget('application.components.CitySelectorWidget', array(
                                'name' => 'cities',
                                'selectedCities' => [],
                                'id' => 'key_city'
                            )); ?>
                        </span>
                    </div>
                    <div id="search_cinema_list_container">
                        <table id="cinema_list_container" class="table-bordered" style="width: 100%;height: 100%;">
                            <tr>
                                <th>影院ID</th>
                                <th>影院名称</th>
                                <th>院线</th>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="save_all_cinema" type="button" class="btn btn-primary">全部添加</button>
                </div>
            </div>
        </div>
    </div>
<?php Yii::app()->getClientScript()->registerScriptFile("/js/goldSeat.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile("/assets/css/bootstrap-datetimepicker.css");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/moment.min.js", CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/date-time/bootstrap-datetimepicker.min.js", CClientScript::POS_END);
?>