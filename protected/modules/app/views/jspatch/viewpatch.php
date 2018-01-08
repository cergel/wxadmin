<?php
$this->breadcrumbs = array(
    'JsPatch' => array('index'),
    '查看补丁',
);
?>
<div class="page-header">
    <h1>APP版本: ( <?php echo $model['appver'] ?> )</h1>
    <h1>MD5: ( <?php echo $model['md5'] ?> )</h1> <a
        href="/app/jspatch/view/version/<?php echo $model['appver'] ?>" class="btn btn-success view_patch"
        role="button">返回</a>
</div>
<div class="row">
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="control-group">
                <label class="control-label">openId列表半角逗号分割(,)不写为全量用户</label>
                <div class="controls">
                    <textarea class="form-control" name="openId" rows="3"><?php echo $model['openId'] ?></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">操作</label>

                <!-- Button -->
                <div class="controls">
                    <button class="btn btn-success">修改</button>
                </div>
            </div>
        </fieldset>
    </form>
    <hr>

    <div class="col-xs-12 container">
            <pre>
                <code class="JavaScript">
        <?php echo htmlspecialchars($content); ?>
                    </code>
                </pre>
    </div>
</div>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
