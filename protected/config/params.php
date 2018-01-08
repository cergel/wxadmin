<?php
if (SERVICE_TYPE == 'master') {
    $_SERVER["HTTP_HOST"] = 'wxadmin.wepiao.com';
} elseif (SERVICE_TYPE == 'pre') {
    $_SERVER["HTTP_HOST"] = 'wxadmin.pre.wepiao.com';
}
if (empty($_SERVER["HTTP_HOST"])) {
    $_SERVER["HTTP_HOST"] = '';
}
//各环境区分
if ($_SERVER["HTTP_HOST"] == 'wxadmin.wepiao.com') {
    $commoncgiIP = 'http://commoncgi.intra.wepiao.com';
    $activeCdn = 'https://promotion.wepiao.com';
    $cinemaNotificationMemcacheKey = 'cinema_notification';
    $appnfs = 'https://appnfs.wepiao.com';
    $commentIp = 'http://comment.intra.wepiao.com';
    $commoncgiUrl = 'http://commoncgi.intra.wepiao.com';
    $delayQueue = "http://sisy.slb.wepiao.com";//延时队列
    $scheduledUrl = "http://10.66.139.148:80"; //商品中心排期批价
    $messageCenter = 'http://message-center.slb.wepiao.com';
} elseif ($_SERVER["HTTP_HOST"] == 'wxadmin.pre.wepiao.com') {
    $commoncgiIP = 'http://10.104.1.55';
    $cinemaNotificationMemcacheKey = 'cinema_notification_pre';
    $activeCdn = 'https://promotion-pre.wepiao.com';
    $appnfs = $_SERVER["HTTP_HOST"];
    $commentIp = 'http://comment-pre.wepiao.com';
    $commoncgiUrl = 'http://commoncgi-pre.wepiao.com';
    $delayQueue = "http://sisyphus.testslb.wepiao.com";
    $scheduledUrl = "http://10.250.168.15:8080";
    $messageCenter = 'http://10.104.1.55:82';
} else {
    $cinemaNotificationMemcacheKey = 'cinema_notification';
    $commoncgiIP = 'http://commoncgi-pre.wepiao.com';
    $activeCdn = 'http://test.promotion.wepiao.com';
    $appnfs = $_SERVER["HTTP_HOST"];
    $commentIp = 'http://dev.comment.wepiao.com';
    $commoncgiUrl = 'http://commoncgi-pre.wepiao.com';
    $delayQueue = "http://sisyphus.testslb.wepiao.com";
    $scheduledUrl = "http://192.168.101.40:8080";
    $messageCenter = 'http://10.104.1.55:82';
}
return [
    'base_url' => dirname(dirname(dirname(__FILE__))),
    'activeCdn' => $activeCdn,
    'company' => '北京微影时代科技有限公司',
    'cinema_notification_memcache_Key' => $cinemaNotificationMemcacheKey,
    //刷新CDN的用户权限
    'cdn_user' =>
        [
            '31',
        ],
    //影院排期，这个是用的app的
    'app_url' => [
        'cinema_sche_url' => 'http://androidcgi.wepiao.com/sche/cinema',//排期的
    ],
    'active_page_new' => array(
        // 'template'   => 'modules/weixin/template/active_page_template',
        //'template'   => dirname(dirname(dirname(dirname(__FILE__)))).'/active/template/wxactivepage/active_page_template',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/active/template/wxactivepage/hermes',
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/activepage_h5",
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/activepage_h5',
        'final_url' => $activeCdn . '/activepage_h5'
    ),
    //微信促销模板
    'active_page' => array(
        // 'template'   => 'modules/weixin/template/active_page_template',
        //'template'   => dirname(dirname(dirname(dirname(__FILE__)))).'/active/template/wxactivepage/active_page_template',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/active/template/wxactivepage/hermes',
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/activepage_h5",
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/activepage_h5',
        'final_url' => $activeCdn . '/activepage_h5'
    ),
    //QQ手游促销模板
    'active_page_QQ' => array(
        //'template'   => 'modules/weixin/template/active_page_template_QQ',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/active/template/wxactivepage/hermes',
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/activepage_h5",
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/activepage_h5',
        'final_url' => $activeCdn . '/activepage_h5'
    ),
    //移动端促销模板
    'active_page_Mobile' => array(
        //'template'   => 'modules/weixin/template/active_page_template_Mobile',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/active/template/wxactivepage/hermes',
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/activepage_h5",
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/activepage_h5',
        'final_url' => $activeCdn . '/activepage_h5'
    ),
    //手Q红包模板
    'active_page_for_QQ' => array(
        //'template'   => 'modules/weixin/template/active_page_template_for_qq',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/active/template/qq_hongbao',
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/activepage_for_qq",
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/activepage_for_qq',
        'final_url' => $activeCdn . '/activepage_for_qq',
        'template_img' => 'http://' . $_SERVER["HTTP_HOST"] . '/protected/modules/weixin/template/active_page_template_for_qq/images',
    ),
    'live_show_temp' => [
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/active/liveshow/preheat_template',
        'local_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/liveshowtemp_h5",
        'local_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/liveshowtemp_h5',
        //活动h5最终地址
        'final_url' => $activeCdn . '/liveshowtemp_h5',
        //图片最终地址
        'img_cdn_url' => 'https://appnfs.wepiao.com',//上线使用 http://appnfs.wepiao.com
        'img_local_url' => 'http://' . $_SERVER["HTTP_HOST"],
    ],

    'jpush' => array('appkey' => 'c80e17806685319e4b06ceb3', 'masterSecret' => '55de62bbb291eec77085d049'),
    //微信banner模块
    'weixin_discovery_banner' => array(
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/weixin_discovery_banner", // 生成目录
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/weixin_discovery_banner',
        'final_url' => 'https://appnfs.wepiao.com/uploads/weixin_discovery_banner'
    ),
    //微信banner
    'weixin_banner' => array(
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/weixin_banner", // 生成目录
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/weixin_banner',
        'final_url' => 'https://appnfs.wepiao.com/uploads'
    ),
    //微信头条
    'weixin_discovery_head' => array(
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/weixin_discovery_head", // 生成目录
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/weixin_discovery_head',
        'final_url' => 'https://appnfs.wepiao.com/uploads/weixin_discovery_head'
    ),
    //安卓分渠道
    'app_version_from' => array(
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/app_version_from", // 生成目录
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/app_version_from',
        'final_url' => 'https://appnfs.wepiao.com/uploads/app_version_from'
    ),
    //图标资源
    'weixin_icon' => array(
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/weixin_icon", // 生成目录
        'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/weixin_icon',
        'final_url' => 'https://appnfs.wepiao.com/uploads/weixin_icon'
    ),


    'ad' => array(
        'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/ad", // 生成目录
        'final_url' => 'https://appnfs.wepiao.com/uploads/ad'
    ),
    'version' => [
        'path' => 'https://appnfs.wepiao.com/uploads/app_version/',
    ],
    //首页模块配置
    'app_module' =>
        [
            'Platform' => ['iOS' => 'iOS', 'Android' => 'Android'],
            'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/Assets",
            'path' => 'https://appnfs.wepiao.com/uploads/Assets/',
            'target_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/Assets/',
        ],
    //APP临时静态资源管理
    'Assets' =>
        [
            'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/Assets",
            'cdn' => 'https://appnfs.wepiao.com/uploads/Assets/',
            'local' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/Assets/',
        ],

    //QQ福利社相关配置
    'Fulishe' =>
        [
            'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/fulishe",
            'cdn' => $appnfs . '/uploads/fulishe/',
            'local' => 'http://' . $_SERVER["HTTP_HOST"] . '/uploads/fulishe/',
        ],
    'WeiXinFind' =>
        [
            'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/weixin_find",
            'cdn' => 'https://appnfs.wepiao.com/uploads/weixin_find/',
        ],
    'logFilePath' => dirname(dirname(__FILE__)) . '/runtime/log',
//commoncgi相关的配置
    'commoncgi' =>
        [
            'encrypt_token' => $commoncgiIP . "/common/default/encrypt",//commoncgi加密生成token
            'comment_seen_movie' => $commoncgiIP . "/common/comment-manager/get-trace-movies",
            'movie_music_del' => $commoncgiIP . "/music/delMovieMusic.php",//删除某个电影的原生音乐
            'movie_music_set' => $commoncgiIP . "/music/setMovieMusic.php",//设置某个电影的原生音乐
            'save_pintuan_cache' => $commoncgiIP . "/v1/pintuan/saveRedis",//拼团保存缓存
            'vote_add' => $commoncgiIP . "/vote-manager/save",
            'vote_update' => $commoncgiIP . "/vote-manager/update",
            'vote_del' => $commoncgiIP . "/vote-manager/del",
            'vote_movie' => $commoncgiIP . "/vote-manager/save-vote-movie",
            'mqq_recommmend_add' => $commoncgiIP . "/mqq-manager/add-recommend-will-manager",
            'mqq_recommmend_del' => $commoncgiIP . "/mqq-manager/del-recommend-will-manager",
            'mqq_recommend_find_set' => $commoncgiIP . "/mqq-manager/add-recommend-find-manager",
            'mqq_recommend_find_get' => $commoncgiIP . "/mqq-manager/get-recommend-find-manager",
            //cos相关接口
            'cos_upload' => $commoncgiIP . '/v1/cos/upload',  //上传文件
            'cos_reateFolder' => $commoncgiIP . '/v1/cos/create-folder',  //创建文件夹
            'cos_statFolder' => $commoncgiIP . '/v1/cos/stat-folder',  //创建文件夹
            //获取影院排期
            'cinema_sche' => $commoncgiIP . '/channel/sche/get-cinema-sche',
        ],
    //评论中心相关
    'comment' => [
        'movie_seen' => $commentIp . '/v1/movies/{movieId}/seen',//看过接口
        'comment_del' => $commentIp . "/v1/admin/shield-comment",
        'comment_save' => $commentIp . "/v1/admin/edit-comment",
        'reply_del' => $commentIp . "/v1/admin/shield-reply",
        'reply_save' => $commentIp . "/v1/admin/edit-reply",
        'comment_hot' => $commentIp . "/v1/admin/score-comments",
        'add_start_comment' => $commentIp . "/v1/admin/save-star-comment",
        'save_comment_star' => $commentIp . "/v1/admin/save-comment-star",
        'save_movie_base_score' => $commentIp . "/v1/admin/save-movie-base-score",
        'cms_comment_del' => $commentIp . "/v1/admin/delete-article-comments",
        'cms_comment_save' => $commentIp . "/v1/admin/edit-article-comments",
        'start_voice_comment_add' => $commentIp . "/v1/admin/add-voice-comments",
        'start_voice_comment_update' => $commentIp . "/v1/admin/update-voice-comments",
        'start_voice_comment_del' => $commentIp . "/v1/admin/delete-voice-comments",
        'cinema_hall_feature' => $commentIp . "/v1/cinemas",
        'comment_tags' => $commentIp . '/v1/admin/save-comment-tag',

    ],
    //cms发布平台
    'release_platform' => [
        '3' => '微信电影票',
        '8' => 'IOS',
        '9' => 'Android',
        '28' => '手Q',   //其实应该是28，但历史原因写成了4，现在（春节前）来不及，也无法测试，所以就不修改了
        '6' => 'M站',
        '55' => '票房分析APP',
        '56' => '票房分析WEB',
    ],
    'AppnfsPath' =>
        [
            'target_dir' => dirname(dirname(dirname(__FILE__))) . "",
            'cdn' => 'https://appnfs.wepiao.com',
            'local' => 'http://' . $_SERVER["HTTP_HOST"] . '/',
        ],

    //app的分享平台配置文件
    'share_platform' => [
        '6' => '微信好友',
        '7' => '微信朋友圈',
        '1' => '新浪微博',
        '2' => 'QQ空间',
    ],
    //活动cms模板//
    'CMS' => [
        //'template'   => dirname(dirname(__FILE__)).'/template/cms',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/active/template/cms',
        'local_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/cms",
        'local_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/cms',
        //活动h5最终地址
        'final_url' => $activeCdn . '/cms',
        //图片最终地址
        'img_cdn_url' => 'https://appnfs.wepiao.com',//上线使用 http://appnfs.wepiao.com
        'img_local_url' => 'http://' . $_SERVER["HTTP_HOST"],
    ],
    //CMS新版地址
    'CMS_new' => [
        //'template'   => dirname(dirname(__FILE__)).'/template/cms',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/promotion_explore_template',
        'local_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/cms_h5",
        'local_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/cms_h5',
        //活动h5最终地址
        'final_url' => $activeCdn . '/cms_h5',
        //图片最终地址
        'img_cdn_url' => 'https://appnfs.wepiao.com',//上线使用 http://appnfs.wepiao.com
        'img_local_url' => 'http://' . $_SERVER["HTTP_HOST"],
    ],
    //报名活动模板//
    'apply_active' => [
        //'template'   => dirname(dirname(__FILE__)).'/template/cms',
        'template' => dirname(dirname(dirname(dirname(__FILE__)))) . '/FEI_activity/sphinx',
        'local_dir' => dirname(dirname(dirname(__FILE__))) . "/active_template/events",
        'local_url' => 'http://' . $_SERVER["HTTP_HOST"] . '/active_template/events',
        //活动h5最终地址
        'final_url' => $activeCdn . '/events',
        //图片最终地址
        'img_cdn_url' => 'https://appnfs.wepiao.com',//上线使用 http://appnfs.wepiao.com
        'img_local_url' => 'http://' . $_SERVER["HTTP_HOST"],
    ],
    //投票模板
    'vote_template' => [
        //投票h5最终地址
        'final_url' => $activeCdn . '/vote/index.html',
    ],
    //腾讯敏感词地址
    'tx_key_word_path' => 'http://10.3.40.23/kw.php',
    //福利频道
    'fulipindao' => dirname(dirname(dirname(__FILE__))) . "/uploads/fuli/",
    //腾讯信鸽推送相关设置
    'tencent_xg_push' => [
        'access_id' => '2100206730',
        'access_key' => 'A8I4C6QVM46Z',
        'secret_key' => 'dcf95f4e50f3ec2f3c4d8fb076e7afe3',
        'api_host' => 'openapi.xg.qq.com',
    ],
    'live_show' => [
        'redbag_info_url' => 'https://appnfs.wepiao.com',
        'seatcard_info_url' => 'https://appnfs.wepiao.com',
        'cdn' => 'https://appnfs.wepiao.com',
        'setShareInfo' => 'http://commoncgi.local.com/streamlive/streamlive/set-share-info',
        'liveshowUrl' => 'http://promotion.wepiao.com/live/index.html?liveid=',
    ],
    //iOS JsPatch配置
    'JsPatch' =>
        [
            'target_dir' => dirname(dirname(dirname(__FILE__))) . "/uploads/JsPatch",
            'path' => 'https://appnfs.wepiao.com/uploads/JsPatch/',
        ],
    //影人相关
    'actor' =>
        [
            'getActorInfo' => $commoncgiUrl . '/msdb/getActorInfo',
            'manageBaseActorLike' => $commoncgiUrl . '/msdb/manageBaseActorLike',
        ],
    //媒资库影片
    'movie' =>
        [
            'getMovieInfo' => $commoncgiUrl . '/msdb/getMovieInfo.php',
            'getHotMovie' => $commoncgiUrl . '/v1/movies/futurelist',
            'getMovieName' => $commoncgiUrl . '/v1/movies/',

        ],
    'film_list' =>
        [
            'removeFilmListMovie' => $commoncgiUrl . '/v1/film-list/remove-movie',
        ],
    //电影card链接
    'movie_card_url' => ['3' => 'http://wx.wepiao.com/movie_detail.html?movie_id=<!--movieId-->',
        '6' => 'http://m.wepiao.com/#/movies/<!--movieId-->',
        '28' => 'http://mqq.wepiao.com/movie_detail.html?movie_id=<!--movieId-->',
        '8' => 'wxmovie://filmdetail?movieid=<!--movieId-->',
        '9' => 'wxmovie://filmdetail?movieid=<!--movieId-->',
        '55' => 'javascript:void(0);',
        '56' => 'javascript:void(0);',
    ],
    //演出card链接
    'show_card_url' => ['3' => 'http://wechat.show.wepiao.com/detail/onlineId=<!--showId-->',
        '6' => 'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
        '28' => 'http://show.wepiao.com/mobile/?page=detail&onlineId=<!--showId-->',
        '8' => 'wxmovie://showdetail?onlineid=<!--showId-->',
        '9' => 'wxmovie://showdetail?onlineid=<!--showId-->',
        '55' => 'javascript:void(0);',
        '56' => 'javascript:void(0);',
    ],
    //明星问候
    'starGreeting' =>
        [
            'set' => $commoncgiUrl . '/v1/greeting/setCache',
            'del' => $commoncgiUrl . '/v1/greeting/delCache',
        ],
    //城市列表
    'citysList' =>
        [
            'getCityList' => "http://wxapi.wepiao.com/v1/cities",
        ],
    //演出评论
    'showComment'=>[
        //openid获取用户信息
        'getUserInfoByOpenid'=>'10.104.140.34:8080/uc/v1/getuserinfobyopenid',
        //修改评论
        'showEditComment'=>$commentIp.'/v1/admin',
    ],
    //延时队列
    'delayQueue' =>
        [
            'add' => $delayQueue . '/job/add',
            'check' => $delayQueue . 'job/check',
            'revoke' => $delayQueue . '/job/revoke',
        ],
    //电影节排期
    'schedule' => $scheduledUrl . '/api/schedule/cancel?baseCinemaNo=',
    'messageCenter' =>
        [
            'push' => $messageCenter . '/v1/msg/push',
            'delete' => $messageCenter . '/v1/msg/delete',
            'fileupload' => $messageCenter . '/v1/msg/fileupload',
        ],
    //电影节修改通知
    'festivalEdit' => $commoncgiUrl . '/v1/festival/',
];

