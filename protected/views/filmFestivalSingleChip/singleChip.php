<?php
if (isset($singleChip)) {
    foreach ($singleChip as $k=>$v) {
        ?>
        <tr class="items">
            <td width="10%">
                <input type="hidden" name="single_chip_id[]" value="<?php echo $v['id'] ?>" />
                <input name="sort_num[]" type="text" class="sort_num" value="<?php echo $v['sort_num'] ?>" style="width:100%">
            </td>
            <td width="80%">
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right required"
                           for="share_describe">标题
                        <span class="required">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input class="col-xs-10" name="title[]" type="text" value="<?php echo $v['title']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right required"
                           for="share_describe">描述
                        <span class="required">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input class="col-xs-10" name="brief[]" type="text" value="<?php echo $v['brief']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right required"
                           for="share_describe">作者名
                        <span class="required">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input class="col-xs-10" name="author_name[]" type="text"  value="<?php echo $v['author_name']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right required"
                           for="share_describe">影片ID
                        <span class="required">*</span>
                    </label>
                    <div class="col-sm-10">
                        <input class="col-xs-10" name="movie_id[]" type="text" value="<?php echo $v['movie_id']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right required"
                           for="share_describe">封面图
                        <span class="required">*</span>
                    </label>
                    <div class="col-sm-10">
                        <div class="cover_map">
                            <img id="cover_map" class="img_items" style="width:166px;height:110px" src="<?php echo $v['cover_map']; ?>"/>
                            <input type="hidden" name="cover_map[]" value="<?php echo $v['cover_map'];?>">
                        </div>
                        <br>
                        <div class="file">选择文件
                            <input type="file"/>
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
                            <img id="author_portrait" class="img_items" style="width:200px;height:200px" src="<?php echo $v['author_portrait']; ?>"/>
                            <input type="hidden" name="author_portrait[]" value="<?php echo $v['author_portrait'];?>">
                        </div>
                        <br>
                        <div class="file ">选择文件
                            <input type="file"/>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="tableDel2(this,<?php  echo $v['id'];?>, '/filmFestivalSingleChip/ajaxDel/')">删除</a>
            </td>
        </tr>
    <?php  }}?>