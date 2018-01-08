<?php
/* @var $this ActorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '热门搜索' => array('index'),
    '推荐词设置',
);
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
Yii::app()->getClientScript()->registerScriptFile("/assets/js/layer.min.js");
?>
<div class="page-header">
    <h1><?php echo str_replace('综合搜索-', '', $title); ?>
        <small>推荐词设置</small>
    </h1>
</div>
<div class="row">
    <div class="col-xs-8 col-xs-offset-1 form-horizontal">
        <div id="list">
            <?php
            foreach ($data as $val) {
                ?>
                <div class="form-group">
                    <div class="col-sm-5">
                        <input type="type" class="form-control" value="<?php echo $val['name'] ?>" readonly="readonly">
                    </div>
                    <div class="col-sm-2">
                        <input onchange="scoreChange(this)" type="type" class="form-control score" autocomplete="off"
                               info="<?php echo $val['name'] ?>"
                               value="<?php echo $val['score'] ?>">
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="form-group">
            <div class="col-sm-4">
                <a class="btn btn-primary" href="/index.php/SearchRec/thesaurus/<?php echo $id; ?>">设置词库</a>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <div class="alert alert-warning">
                    <label class="alert-link">关键词展示:</label>
                    <span>在词库中的词,默认展示搜索量前十的关键字,可以修改权重值调整排列顺序,默认是10,排序数值正序排列</span>
                    <br/>
                    <label class="alert-link">设置词库:</label>
                    <span>设置关键词的词库,所有排序从词库中筛选</span>
                    <br/>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function scoreChange(e) {
        layer.msg('请等候', {icon: 16, shade: 0.8, shadeClose: false, time: 0});
        var _info = $(e).attr('info');
        var _num = $(e).val();
        if (_num > 10 || _num < 1) {
            layer.msg('请输入1到10的正整数');
            return false;
        }
        var _data = {'num': _num, 'name': _info, 'id':<?php echo $id?>};
        $.ajax({
            type: "post",
            url: "<?php echo Yii::app()->getController()->createUrl('SearchRec/editInfo/');?>",
            data: _data,
            dataType: "json",
            success: function (data) {
                if (data.code == 0) {

                    $('#list').html(data.data);
                }
                layer.msg(data.msg);
            },
            error: function () {
                layer.msg('系统繁忙');
            }
        });
    }
</script>