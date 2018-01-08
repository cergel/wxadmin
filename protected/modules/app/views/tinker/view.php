<?php
$this->breadcrumbs = array(
    'Tinker' => array('index'),
    '查看补丁',
);
?>
<div class="page-header">
    <h1>APP版本: ( <?php echo $version->app_version ?> )</h1> <a
        href="/app/tinker/patch/version/<?php echo $version->app_version ?>" class="btn btn-success view_patch"
        role="button">新建补丁</a>
</div>
<div class="row">
    <?php foreach ($items as $value): ?>
        <div class="col-xs-4 container">

            <div class="caption">
                <h3>补丁版本: ( <?php echo $value['patchver'] ?> )</h3>
                <p>创建时间: ( <?php echo $value['created_at'] ?> ) </p>
                <p>MD5: ( <?php echo $value['md5'] ?> ) </p>
                <p>创建者: ( <?php echo $value['created_by'] ?> ) </p>
                <p><a target="_blank" href="<?php echo $value['patch_link'] ?>" class="btn btn-primary view_patch"
                      role="button">下载补丁</a>
                <p>
                    <a href="/app/tinker/delpatch/patch/<?php echo $value['id'] ?>/appver/<?php echo $value['appver'] ?>"
                       class="btn btn-danger del_patch"
                       role="button">删除补丁</a>
            </div>
        </div>
    <?php endforeach ?>
</div>