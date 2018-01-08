<?php
if (isset($cinemaList)) {
    foreach ($cinemaList as $k=>$v) {
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
                <a href="javascript:void(0)" onclick="tableDel2(this,<?php echo $v['id']; ?>,'/filmFestivalCinemaList/ajaxDel/')">删除</a>
            </td>
        </tr>
        <?php
    }}
?>