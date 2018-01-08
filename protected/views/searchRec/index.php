<?php
/* @var $this ActorController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    '热门搜索' => array('index'),
    '推荐',
);

?>
<div class="page-header">
    <h1>热门搜索推荐</h1>
</div>
<div class="row">
    <div class="col-xs-9 col-xs-offset-1">
        <div id="actor-id" class="grid-view">
            <div class="dataTables_wrapper form-inline " role="grid">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>项目</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $key => $value) { ?>
                        <tr>
                            <td><?php echo $key; ?></td>
                            <td><?php echo $value; ?></td>
                            <td><a href="/index.php/SearchRec/info/<?php echo $key; ?>">查看</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>