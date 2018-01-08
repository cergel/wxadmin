<?php
// 从mango读取数据并整理
//$regions = BsonBaseRegionInfo::model()->findAll();
//$regions = Https::getCityList();
//$provinces = array();
//foreach($regions as $region) {
//    if ($region->Name == '全国')
//        continue;
//    if ($region['LevelType'] == 1) {
//        $provinces[$region->RegionNumPath] = array(
//            'name' => $region->Name,
//            'RegionNum' => $region->WeiXinNo,
//            'WeiXinNo' => $region->WeiXinNo,
//        );
//    } else if ($region['LevelType'] == 2) {
//        $provinces[substr($region->RegionNumPath, 0, 10)]['children'][$region->RegionNumPath] = array(
//            'name' => $region->Name,
//            'RegionNum' => $region->WeiXinNo,
//            'WeiXinNo' => $region->WeiXinNo,
//        );
//    }
//}

$regions = Https::getCityList();
$regions = json_decode(json_encode($regions));
$provinces = array();
foreach($regions as $region) {
    if ($region->name == '全国')
        continue;
    if ($region->levelType == 1) {
        $provinces[$region->regionNumPath] = array(
            'name' => $region->name,
            'RegionNum' => $region->id,
            'WeiXinNo' => $region->id,
        );
    }else if ($region->levelType == 2) {
        $provinces[substr($region->regionNumPath, 0, 10)]['children'][$region->regionNumPath] = array(
            'name' => $region->name,
            'RegionNum' => $region->id,
            'WeiXinNo' => $region->id,
        );
    }
}

ksort($provinces);
Yii::app()->clientScript->registerScript('city-selector', "
    refresh_cities = function(){
        var keyword = $('#city-selector-".$name."-keyword').val();
        if (keyword)
            var re = new RegExp(keyword, 'gi');
        var provinces=[];
        $('#city-selector-".$name." .provincename li.selected').each(function(){
            provinces.push($(this).html());
        });

        // 隐藏城市
        $('#city-selector-".$name." .cities li').each(function(){
            var in_province = provinces.length === 0 ? true : false;
            var keyword_match = (keyword.length === 0 ? true : re.test($(this).attr('name')));
            var province = $(this).closest('.province').attr('name');

            for (i in provinces) {
                if(province === provinces[i]) {
                    in_province = true;
                    break;
                }
            }

            if (in_province && keyword_match) {
                $(this).show().removeClass('city-selector-hide').addClass('city-selector-show');
            } else {
                $(this).hide().removeClass('city-selector-show').addClass('city-selector-hide');
            }
        });

        // 隐藏省份
        $('#city-selector-".$name." .province').each(function(){
            if($(this).find('.city-selector-show').length === 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    }
    update_selected = function(){
        var selected = '全国';
        $('#city-selector-".$name." .cities :checkbox:checked').each(function(){
            //console.log($(this).closest('li').attr('name'));
            if (selected != '全国')
                selected += ',';
            else
                selected = '';
            selected += $(this).closest('li').attr('name')
        });
        $('#city-selector-selected').html(selected);
    }
    $('#city-selector-".$name." .provincename li').click(function(){
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected').removeClass('btn-gray').addClass('btn-light');
        } else {
            $(this).addClass('selected').removeClass('btn-light').addClass('btn-gray');
        }
        refresh_cities();
    });
    $('#city-selector-".$name."-select').click(function(){
        $('#city-selector-".$name." .cities .city-selector-show input[type=checkbox]').prop('checked',true);
    });
    $('#city-selector-".$name."-clear').click(function(){
        $('#city-selector-".$name." .cities .city-selector-show input[type=checkbox]').prop('checked',false);
    });
    $('#city-selector-".$name."-clearall').click(function(){
        $('#city-selector-".$name." .cities input[type=checkbox]').prop('checked',false);
    });
    $('#city-selector-selected').click(function(){
        $('#city-selector-".$name."').show();
    });
    $('#city-selector-ok').click(function(){
        $('#city-selector-".$name."').hide();
        update_selected();
    });
    // 即时搜索
    $('#city-selector-".$name."-keyword').keydown(function(e)
    {
        if(e.keyCode==13)
        {
            e.preventDefault();
            return false;
        }
    }).keyup(function(e)
    {
        if(e.keyCode==13)
        {
            e.preventDefault();
            return false;
        }
        refresh_cities();
    });
    update_selected();
");
?>
<style>
    .city-selector-widget .provincename li{
        margin-bottom:2px;
    }
    #city-selector-<?php echo $name;?>{
        position: fixed;
        top:0;
        background:#000;
        z-index:2000;
        left:0;
        height:100%;
    }
    #city-selector-selected{
        cursor:pointer;
        color:#999;
        line-height: 30px;
    }
    .city-selector-widget .cities h3{
        padding:5px;
        font-weight:bold;
        width:100%;
        clear:both;
        font-size:14px;
        border-bottom: 1px solid #e0e0e0;
    }
</style>
<div class="row" id="city-selector-selected">全国</div>
<div class="row city-selector-widget" id="city-selector-<?php echo $name;?>" style="display:none;">
    <div class="col-xs-8 col-xs-offset-2" style="background:#fff;margin-top:2em;height:90%;">
        <div style="min-height:39px;padding:5px;margin:0 -12px;">
            <div class="row">
                <div class="col-xs-3">
                    <div class="input-group">
                        <input type="text" class="form-control search-query"  id="city-selector-<?php echo $name;?>-keyword" placeholder="关键字" />
                    </div>
                </div>
                <div class="col-xs-9">
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="city-selector-<?php echo $name;?>-clearall">清空所有</button>
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="city-selector-<?php echo $name;?>-clear">清空当前</button>
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="city-selector-<?php echo $name;?>-select">选中当前</button>
                </div>
            </div>
        </div>
        <div class="row" style="height:90%;">
            <div class="col-xs-2" style="height: 100%; overflow-y: scroll; ">
                <ul class="list-unstyled provincename">
                    <?php foreach($provinces as $k => $province) { ?>
                        <li class="btn btn-light btn-minier" style="width:100%"><?php echo $province['name'];?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xs-10 cities" style="height: 100%; overflow-y: scroll; background:#efefef;">
                <?php foreach($provinces as $ck => $province) { ?>
                    <div class="province" name="<?php echo $province['name'];?>">
                        <h3><?php echo $province['name'];?></h3>
                        <ul class="list-inline">
                            <?php if(!empty($province['children'])) foreach($province['children'] as $city) { ?>
                                <li class="col-xs-2 city-selector-show"
                                    province="<?php echo $province['name'];?>"
                                    name="<?php echo $city['name'];?>">
                                    <input type="checkbox"
                                           name="<?php echo $name;?>[]"
                                           value="<?php echo $city['RegionNum'];?>"
                                        <?php echo in_array($city['RegionNum'], $this->selectedCities) ? " checked='checked' " : "" ;?>
                                        />
                                    <?php echo $city['name'];?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix" style="background:#fff;padding:5px;margin:0 -12px;">
                <button class="btn btn-info btn-sm pull-right" type="button" id="city-selector-ok">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    确定
                </button>
                <!--
                <button class="btn" type="reset" id="city-selector-reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
                -->
        </div>
    </div>
</div>