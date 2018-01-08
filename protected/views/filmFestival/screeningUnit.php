<?php
if (isset($screeningUnit)) {
    foreach ($screeningUnit as $k => $v) {
        ?>
        <tr>
            <td width="10%">
                <input type="text" class="sort_num col-sm-12" name="sort_num[]"
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
                       class="movie_ids col-sm-12"
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