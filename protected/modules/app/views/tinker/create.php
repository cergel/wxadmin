<form class="form-horizontal" method="post" enctype="multipart/form-data">
    <fieldset>
        <div id="legend" class="">
            <legend class="">新建tinker补丁 【 版本:( <?php echo $model['version'] ?> ) 】 【
                当前版本为 <?php echo $model['versioncount'] + 1 ?> 】
            </legend>
        </div>


        <div class="control-group">
            <label class="control-label">请选择zip文件</label>

            <!-- File Upload -->
            <div class="controls">
                <input type="file" name="patch"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">openId列表半角逗号分割(,)不写为全量用户</label>
            <div class="controls">
                <textarea class="form-control" name="openId" rows="3"></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">操作</label>

            <!-- Button -->
            <div class="controls">
                <button class="btn btn-success">保存</button>
            </div>
        </div>


    </fieldset>
</form>
