<?php
/* @var $this ModuleController */
Yii::app()->getClientScript()->registerScriptFile("/assets/js/jquery.min.js");
$this->breadcrumbs = array(
    '模块开关',
);
?>
<form class="form-horizontal">
    <fieldset>
        <div id="legend" class="">
            <legend class="">APP 模块开关 (不区分渠道版本)</legend>
        </div>
        <div class="control-group" id="daySign">
            <label class="control-label">日签模块</label>
            <div class="controls">
                <!-- Inline Radios -->
                <label class="radio inline">
                    <input type="radio" value=1 name="daySign">
                    开启
                </label>
                <label class="radio inline">
                    <input type="radio" value=0 name="daySign">
                    关闭
                </label>
            </div>
        </div>

        <div class="control-group" id="super8">
            <label class="control-label">猜电影</label>
            <div class="controls">
                <!-- Inline Radios -->
                <label class="radio inline">
                    <input type="radio" value=1 name="super8">
                    开启
                </label>
                <label class="radio inline">
                    <input type="radio" value=0 name="super8">
                    关闭
                </label>
            </div>
        </div>

        <div class="control-group" id="comment">
            <label class="control-label">评论模块</label>
            <div class="controls">
                <label class="radio inline">
                    <input type="radio" value=1 name="comment">
                    开启
                </label>
                <label class="radio inline">
                    <input type="radio" value=0 name="comment">
                    关闭
                </label>
            </div>
        </div>

        <div class="control-group" id="shop">
            <label class="control-label">商城模块</label>
            <div class="controls">
                <!-- Inline Radios -->
                <label class="radio inline">
                    <input type="radio" value=1 name="shop">
                    开启
                </label>
                <label class="radio inline">
                    <input type="radio" value=0 name="shop">
                    关闭
                </label>
            </div>
        </div>

    </fieldset>
</form>

<script type="text/javascript">
    $(".control-group").each(function (i, k) {
        $.ajax({
            "url": "/app/module/get",
            "data": {"element": k.id},
            "dataType": "json",
            "success": function (msg) {
                if (msg.ret == 0) {
                    $("input[name='" + k.id + "']").each(function (n, element) {
                        if (msg.data == element.value) {
                            element.checked = true;
                        }
                    });
                }
            }
        });
    });
    $("input[type='radio']").change(function () {
        $.ajax({
            "url": "/app/module/set",
            "data": {"element": this.name, "value": this.value},
            "dataType": "json",
            "success": function (msg) {
                if (msg.ret == 0) {
                    alert("设置成功!");
                }
            }
        });
    });
</script>
