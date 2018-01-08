<?php
$cinemasByCity=$cities=$systems=$chains=array();
//// 从mango读取全部影城数据
//$cinemas = BsonBaseCinema::model()->findAll();
//// 读取分组信息
//$groups = CinemaGroup::model()->with('cinemas')->findAll();
//foreach ($cinemas as $cinema) {
//    // 查找分组
//    $cinema->groups = array();
//    foreach ($groups as $group) {
//        foreach ($group->cinemas as $gck => $gc) {
//            if ($gc->iCinemaID == $cinema->CinemaNo) {
//                $cinema->groups[$group->iGroupID] = $group->sName;
//                break;
//            }
//        }
//    }
//    $cinemasByCity[$cinema->CityName][] = $cinema;
//    $cinema->TheaterChain = str_replace(
//        array('院线发展有限责任公司', '有限公司', '有限责任公司', '院线公司'),
//        '',
//        $cinema->TheaterChain
//    );
//    if ($cinema->CityName)
//        if (isset($cities[$cinema->CityName]))
//            $cities[$cinema->CityName]++;
//        else
//            $cities[$cinema->CityName]=1;
//    if ($cinema->TicketSaleSystem)
//        if (isset($systems[$cinema->TicketSaleSystem]))
//            $systems[$cinema->TicketSaleSystem]++;
//        else
//            $systems[$cinema->TicketSaleSystem]=1;
//    if ($cinema->TheaterChain)
//        if (isset($chains[$cinema->TheaterChain]))
//            $chains[$cinema->TheaterChain]++;
//        else
//            $chains[$cinema->TheaterChain]=1;
//    arsort($cities);arsort($systems);//arsort($chains);
//    //$cities = array_slice($cities,0,20);
//    //$chains = array_slice($chains,0,20);
//}



// 从mango读取全部影城数据
//$cinemas = BsonBaseCinema::model()->findAll();
$cinemas = Https::getCinemaList();
$cinemas = json_decode(json_encode($cinemas));
// 读取分组信息
$groups = CinemaGroup::model()->with('cinemas')->findAll();
foreach ($cinemas as $cinema) {
    // 查找分组
    $cinema->groups = array();
    foreach ($groups as $group) {
        foreach ($group->cinemas as $gck => $gc) {
            if ($gc->iCinemaID == $cinema->cinemaNo) {
                $cinema->groups[$group->iGroupID] = $group->sName;
                break;
            }
        }
    }
    $cinemasByCity[$cinema->cityName][] = $cinema;
    $cinema->TheaterChain = str_replace(
        array('院线发展有限责任公司', '有限公司', '有限责任公司', '院线公司'),
        '',
        $cinema->lineName
    );
    if ($cinema->cityName)
        if (isset($cities[$cinema->cityName]))
            $cities[$cinema->cityName]++;
        else
            $cities[$cinema->cityName]=1;
    if ($cinema->ticketSaleSystemName)
        if (isset($systems[$cinema->ticketSaleSystemName]))
            $systems[$cinema->ticketSaleSystemName]++;
        else
            $systems[$cinema->ticketSaleSystemName]=1;
    if ($cinema->lineName)
        if (isset($chains[$cinema->lineName]))
            $chains[$cinema->lineName]++;
        else
            $chains[$cinema->lineName]=1;
    arsort($cities);arsort($systems);//arsort($chains);
    //$cities = array_slice($cities,0,20);
    //$chains = array_slice($chains,0,20);
}

//print_r($systems);exit;
Yii::app()->clientScript->registerScript('cinema-selector', "
// 根据各项条件进行显示控制
refresh_cinemas = function(){
    var keyword = $('#cinema-selector-".$name."-keyword').val();
    if (keyword)
        re = new RegExp(keyword, '');
    var systems=[],chains=[],cities=[],groups=[];

    $('#cinema-selector-".$name." .ticketsalesystem li.selected').each(function(){
        systems.push($(this).html());
    });
    $('#cinema-selector-".$name." .cityname li.selected').each(function(){
        cities.push($(this).html());
    });
    $('#cinema-selector-".$name." .theaterchain li.selected').each(function(){
        chains.push($(this).html());
    });
    $('#cinema-selector-".$name." .groups li.selected').each(function(){
        groups.push($(this).html());
    });

    $('#cinema-selector-".$name." .cinemas li').each(function(ii,item){
        var keyword_match = (keyword.length === 0 ? true : re.test($(this).attr('name')));

        var in_city = cities.length === 0 ? true : false;
        var in_system = systems.length === 0 ? true : false;
        var in_chain = chains.length === 0 ? true : false;
        var in_group = groups.length === 0 ? true : false;

        var city = $(this).attr('city');
        var system = $(this).attr('system');
        var chain = $(this).attr('chain');
        var group = $(this).attr('groups');

        for (i in cities) {
            if(city === cities[i]) {
                in_city = true;
                break;
            }
        }
        for (i in systems) {
            if(system === systems[i]) {
                in_system = true;
                break;
            }
        }
        for (i in chains) {
            if(chain === chains[i]) {
                in_chain = true;
                break;
            }
        }
        for (i in groups) {
            g = group.split(',');
            for (j in g) {
                if (g[j] == groups[i]) {
                    in_group = true;
                    break;
                }
            }
        }
        if (in_city && in_system && in_chain && in_group && keyword_match) {
            $(this).show().removeClass('cinema-selector-hide').addClass('cinema-selector-show');
        } else {
            $(this).hide().removeClass('cinema-selector-show').addClass('cinema-selector-hide');
        }
    });
    // 隐藏省份
    $('#cinema-selector-".$name." .province').each(function(){
        if($(this).find('.cinema-selector-show').length === 0) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
}
// 显示已选中影院
update_selected_cinemas = function(){
    var selected = '点击设置影院';
    $('#cinema-selector-".$name." .cinemas :checkbox:checked').each(function(){
        //console.log($(this).closest('li').attr('name'));
        if (selected != '点击设置影院')
            selected += ',';
        else
            selected = '';
        selected += $(this).closest('li').attr('name')
    });
    $('#cinema-selector-selected').html(selected);
}

// 按钮样式切换
$('#cinema-selector-".$name." .ticketsalesystem li, #cinema-selector-".$name." .cityname li, #cinema-selector-".$name." .groups li, #cinema-selector-".$name." .theaterchain li').click(function(){
    if($(this).hasClass('selected')) {
        $(this).removeClass('selected').removeClass('btn-gray').addClass('btn-light');
    } else {
        $(this).addClass('selected').removeClass('btn-light').addClass('btn-gray');
    }
    refresh_cinemas();
});

/* 分组方式切换 */
$('#cinema-selector-".$name."-tabs button').click(function(){
    $(this).removeClass('btn-light').addClass('btn-gray').siblings().removeClass('btn-gray').addClass('btn-light');
    switch($(this).attr('id')) {
        case 'cinema-selector-".$name."-by-system':
            className='ticketsalesystem';
            break;
        case 'cinema-selector-".$name."-by-chain':
            className='theaterchain';
            break;
        case 'cinema-selector-".$name."-by-group':
            className='groups';
            break;
        case 'cinema-selector-".$name."-by-cinema':
            className='cityname';
            break;
    }
    $('.'+className).show().siblings().hide();
});

/* 工具栏 */
$('#cinema-selector-".$name."-select').click(function(){
    $('#cinema-selector-".$name." .cinemas .cinema-selector-show input[type=checkbox]').prop('checked',true);
});
$('#cinema-selector-".$name."-clear').click(function(){
    $('#cinema-selector-".$name." .cinemas .cinema-selector-show input[type=checkbox]').prop('checked',false);
});
$('#cinema-selector-".$name."-clearall').click(function(){
    $('#cinema-selector-".$name." .cinemas input[type=checkbox]').prop('checked',false);
});
$('#cinema-selector-".$name."-search').click(function(){
    refresh_cinemas();
});
$('#cinema-selector-selected').click(function(){
    $('#cinema-selector-".$name."').show();
});
/* 确认按钮 */
$('#cinema-selector-ok').click(function(){
    $('#cinema-selector-".$name."').hide();
    update_selected_cinemas();
});
/* 回车事件处理 */
$('#cinema-selector-".$name."-keyword').keydown(function(e){
    if(e.keyCode==13){
        e.preventDefault();
        return false;
    }
}).keyup(function(e){
    if(e.keyCode==13){
        e.preventDefault();
        refresh_cinemas();
        return false;
    }
});
update_selected_cinemas();
");
?>
<style>
    .cinema-selector-widget .ticketsalesystem li,
    .cinema-selector-widget .theaterchain li,
    .cinema-selector-widget .groups li,
    .cinema-selector-widget .cityname li{
        margin-bottom:2px;
    }
    #cinema-selector-<?php echo $name;?>{
        position: fixed;
        top:0;
        background:#000;
        z-index:2000;
        left:0;
        height:100%;
    }
    #cinema-selector-selected{
        cursor:pointer;
        color:#999;
        line-height: 30px;
    }
    .cinema-selector-widget .cinemas h3{
        padding:5px;
        font-weight:bold;
        width:100%;
        clear:both;
        font-size:14px;
        border-bottom: 1px solid #e0e0e0;
    }
</style>
<div class="row" id="cinema-selector-selected">点击设置影院</div>
<div class="row cinema-selector-widget" id="cinema-selector-<?php echo $name;?>" style="display:none;">
    <div class="col-xs-8 col-xs-offset-2" style="background:#fff;margin-top:2em;height:90%;">
        <div id="cinema-selector-<?php echo $name;?>-tabs" style="min-height:44px;padding:5px 5px 5px;margin:0 -12px;">
            <button type="button" class="btn btn-sm btn-light" id="cinema-selector-<?php echo $name;?>-by-system">按系统</button>
            <button type="button" class="btn btn-sm btn-light" id="cinema-selector-<?php echo $name;?>-by-chain">按院线</button>
            <button type="button" class="btn btn-sm btn-light" id="cinema-selector-<?php echo $name;?>-by-group">按分组</button>
            <button type="button" class="btn btn-sm btn-gray" id="cinema-selector-<?php echo $name;?>-by-cinema">按影院</button>
        </div>
        <div style="min-height:39px;padding:0 5px 5px;margin:0 -12px;">
            <div class="row">
                <div class="col-xs-3">
                    <div class="input-group">
                        <input type="text" class="form-control search-query"  id="cinema-selector-<?php echo $name;?>-keyword" placeholder="关键字" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-gray btn-sm" id="cinema-selector-<?php echo $name;?>-search">
                            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                        </button>
                    </span>
                    </div>
                </div>
                <div class="col-xs-9">
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="cinema-selector-<?php echo $name;?>-clearall">清空所有</button>
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="cinema-selector-<?php echo $name;?>-clear">清空当前</button>
                    <button type="button" class="btn btn-sm btn-gray pull-right" id="cinema-selector-<?php echo $name;?>-select">选中当前</button>
                </div>
            </div>
        </div>
        <div class="row" style="height:80%;">
            <div class="col-xs-2" style="height: 100%; overflow-y: scroll; ">
                <ul class="list-unstyled cityname">
                    <?php foreach($cities as $city => $count) { ?>
                        <li class="btn btn-light btn-minier" style="width:100%"><?php echo $city;?></li>
                    <?php } ?>
                </ul>
                <ul class="list-unstyled ticketsalesystem" style="display:none;">
                    <?php foreach($systems as $system => $count) { ?>
                        <li class="btn btn-light btn-minier" style="width:100%"><?php echo $system;?></li>
                    <?php } ?>
                </ul>
                <ul class="list-unstyled theaterchain" style="display:none;">
                    <?php foreach($chains as $chain => $count) { ?>
                        <li class="btn btn-light btn-minier" style="width:100%"><?php echo $chain;?></li>
                    <?php } ?>
                </ul>
                <ul class="list-unstyled groups" style="display:none;">
                    <?php foreach($groups as $key => $group) { ?>
                        <li class="btn btn-light btn-minier" style="width:100%"><?php echo $group->sName;?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xs-10 cinemas" style="height: 100%; overflow-y: scroll; background:#efefef;">
                <?php foreach ($cities as $city => $count) {?>
                    <div class="province" name="<?php echo $city;?>">
                        <h3><?php echo $city;?></h3>
                        <ul class="list-inline">
                            <?php foreach ($cinemasByCity[$city] as $ck => $cinema) { ?>
                                            <li class="col-xs-4 cinema-selector-show" city="<?php echo $cinema->cityName;?>"
                                    chain="<?php echo $cinema->lineName;?>"
                                    system="<?php echo $cinema->ticketSaleSystemName;?>"
                                    name="<?php echo $cinema->cinemaName;?>"
                                    groups="<?php echo implode(',', $cinema->groups);?>"
                                    >
                                    <input type="checkbox"
                                           name="<?php echo $name;?>[]"
                                           value="<?php echo $cinema->cinemaNo;?>"
                                        <?php echo in_array($cinema->cinemaNo, $this->selectedCinemas) ? " checked='checked' " : "" ;?>
                                        />
                                    <?php echo $cinema->cinemaName;?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix" style="background:#fff;padding:5px;margin:0 -12px;">
                <button class="btn btn-info btn-sm pull-right" type="button" id="cinema-selector-ok">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    确定
                </button>
                <!--
                <button class="btn" type="reset" id="cinema-selector-reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    重置
                </button>
                -->
        </div>
    </div>
</div>