<?php
if (isset($movieList)) {
    foreach ($movieList as $k => $v) {
        ?>
        <tr>
            <td width="10%">
                <input type="text" class="col-sm-12 sort_num" name="sort_num[]"
                       value="<?php echo $v['sort_num']; ?>">
            </td>
            <td width="20%">
                <input name="movie_id[]" class="col-sm-12" type="text"
                       value="<?php echo $v['movie_id']; ?>">
            </td>
            <td width="20%">
                <input name="movie_name[]" class="col-sm-12" type="text"
                       value="<?php echo $v['movie_name']; ?>"
                       readonly="readonly">
            </td>
            <td width="40%">
                <input name="row_piece_time[]"
                       class="auto-kal col-sm-12" type="text"
                       data-kal="mode:'multiple',format:'YYYY-MM-DD',multipleDelimiter:';'"
                       value="<?php echo $v['row_piece_time']; ?>">
            </td>
            <td width="10%">
                <a href="javascript:void(0)"
                   onclick="tableDel2(this,<?php echo $v['id']; ?>,'/filmFestivalMovieList/ajaxDel/')">删除</a>
                <input type="hidden" name="movie_list_id[]"
                       value="<?php echo $v['id']; ?>"/>
            </td>
        </tr>
    <?php }
} ?>