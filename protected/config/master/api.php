<?php
return [
    'redis' => array('host' => '119.29.5.173', 'port' => 6379),
    'wechat' => array(
        'app_id' => 'wx92cf60f7577e2d48',
        'app_secret' => '6347543223aa409d36108565b51edd9a',
        'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
    ),
    'weixin_service_record' => array(
        'access_token_url' => 'http://182.254.230.26:8080/CreateWeiXinToken.php?app_id=%s&app_secret=%s&force=0',
        'app_id' => 'wx92cf60f7577e2d48',
        'app_secret' => '6347543223aa409d36108565b51edd9a',
    ),
    'baymax_active_java' => 'http://10.66.150.98/baymaxqry?resId=',// java active -- master

    'uctent_uid_openid' => '10.66.154.209/uc/v1/getopenidbyuid4dragon',// java ucent
    'ucenter_api'=>array(//用户中心接口
        'user_info'=>'http://10.66.154.209/uc/v1/getuserinfobyopenid',//通过openid获取用户信息
        'mobileNo_openid'=>'http://10.66.154.209/uc/v1/getopenidlistbymobile',//根据手机号获取openid列表
        'user_tag_add'=>'http://10.66.106.195/ucopen/v1/addusertag/static',  //添加用户静态标签
        'user_tag_get'=>'http://10.66.106.195/ucopen/v1/getstatictag',  //获取用户静态标签
    ),
    'om_base_url'=>[//调用om数据--改为接口
        'city_list'=>'http://data-core.wp.wepiao.com/datacore/city/list',
        'cinema_list'=>'http://data-core.wp.wepiao.com/datacore/baseCinema',
    ],
];
	
