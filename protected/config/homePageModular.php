<?php
/**
 * BayMax主页模块列表
 *
 * @author CHAIYUE
 * @version 2016-12-08
 *
 *
 * 这里是详细的文件说明:
 *
 * treeMap为当前模块树结构图
 * 从外层到内层bayMax当前只支持最多三层结构
 *
 * detail为每个treeMap中关键词的详细内容,每个treeMap中的词必须对应一个且只有一个详细内容。
 *      关键词说明
 *          name  对应页面展示名称
 *          urlC  对应函数createUrl(),创建url
 *          url   对应按钮的href属性
 *          gly   自定义展示图标
 *          name  对应页面展示名称
 *          index (!禁止重名)因为多个模块中的类难免出现重复的名称(这个最好避免),所以在treeMap中写你的类的别名(为了避免重复),index为此类的
 *                真实名称
 *          active 代表什么时候被选中,一般情况下都是通过url与控制器名称自动匹配并选中,某些情况下要用到active ,必须!按照如下格式
 *                 'active' => ['action' => '函数名称', 'class' => '控制器名称'],当url为此控制器的此action便会匹配选中
 *          also  并且的意思,表示并且满足此条件才会显示为选中状态
 *      声明注意:使用小驼峰
 */
return [
    'treeMap' => [
        '/' => [
            'adindex' => ['bankPrivilege', 'bankInfo'],
            'cinemaHallIndex' => ['cinemaNotification', 'cinemaHallFeature'],
            'movieIndex' => ['movie', 'actor', 'hotMovie', 'movieList'],
            'movieMusic',
            'CommentIndex' => ['comment',
                'commentTag',
                'addStartComment',
                'voiceComment',
                'statisticsVoice',
                'commentRecommend',
                'commentStar',
                'userTag',
                'commentReply',
                'statistics',
                'sensitiveWords',
                'shieldingWords',
                'blackList',
                'cmsComment'
            ],
            'userIndex' => ['user', 'userGroup'],
            'goldSeatIndex' => ['goldSeat', 'goodsCenter', 'orderInfo'],
            'customization',
            'log',
            'activeIndex' => [
                'cms',
                'cmsFind',
                'cmsFind2',
                'cmsNews',
                'spellGroup',
                'author',
            ],
            'movieNews',
            'pee',
            'starActive',
            'starGreeting',
            'searchRec',
            'redActive',
            'applyIndex' => ['applyActive', 'questionSet', 'applyRecord', 'fuli', 'liveShowTemp'],
            'liveshow',
            'vote',
            'movieOrder',
            'filmFestival',
            'promotionSharing'
        ],
        'weixin' => ['activePage',
            'activePageQq',
            'discoveryBanner2',
            'discoveryHead',
            'service',
            'icon',
            'fulisheindex' => ['fulishe', 'fulisheRes'],
            'find',
            'redspot',
            'wxpushLogcount'],
        'app' => ['banner',
//            'disIndex' => ['discoveryBanner', 'dayPush'],
            'resource',
            'feedback',
            'notice',
            'version',
            'versionFrom',
            'videomodule',
            'discoverymodule',
            'Assets',
            'daySign',
            'movieGuide',
            'iconConfig',
            'biz',
            'appHotUpdate' => ['jspatch',
                'tinker',],
            'module'],
        'mqq' => ['recommendWill',
            'findRecommendWill'],
//        'tool' => ['luckActivity'],
        'message' => [//'Mesmessage',
//            'tag',
            'messageNotice'],
        'show' => ['showComment', 'showCommentReply'],
        'weixinsp' => ['redEnvelop']
    ],
    'detail' => [
        //顶部
        '/' => ['name' => '全站', 'url' => '/'],
        'weixin' => ['name' => '微信', 'url' => '/weixin'],
        'app' => ['name' => 'APP', 'url' => '/app'],
        'mqq' => ['name' => 'MQQ', 'url' => '/mqq'],
        //'tool' => ['name' => '营销工具', 'url' => '/tool'],
        'message' => ['name' => '消息中心', 'url' => '/message'],
        'liveshow' => ['name' => '明星直播', 'gly' => 'fa fa-book', 'url' => '/liveshow/liveshow/index'],
        'show' => ['name' => '演出', 'url' => '/show'],
        'weixinsp' => ['name' => '微信小程序', 'url' => '/weixinsp'],
        //MQQ
        'recommendWill' => ['urlC' => '', 'name' => '个人中心推荐位', 'url' => '/mqq/recommendWill/index', 'gly' => 'glyphicon glyphicon-picture'],
        'findRecommendWill' => ['urlC' => '', 'name' => '发现列表推荐位', 'url' => '/mqq/findRecommendWill/index', 'gly' => 'glyphicon glyphicon-picture'],
        //APP
        'banner' => ['name' => 'Banner', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/app/banner/index',],
        'disIndex' => ['name' => '发现频道', 'gly' => 'glyphicon glyphicon-search'],
        'discoveryBanner' => ['name' => '管理活动', 'url' => '/app/discoveryBanner/index'],
        'dayPush' => ['name' => '每日主推管理', 'url' => '/app/dayPush/index'],
        'resource' => ['name' => '资源管理', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/app/dayPush/index'],
        'feedback' => ['name' => '用户反馈', 'gly' => 'glyphicon glyphicon-user', 'url' => '/app/feedback/index'],
        'notice' => ['name' => '推送消息', 'gly' => 'glyphicon glyphicon-envelope', 'url' => '/app/notice/index'],
        'version' => ['name' => '版本管理', 'gly' => 'glyphicon glyphicon-download', 'url' => '/app/version/index'],
        'versionFrom' => ['name' => '渠道版本管理', 'gly' => 'glyphicon glyphicon-download', 'url' => '/app/versionFrom/index'],
        'videomodule' => ['name' => '首页预告片模块', 'gly' => 'glyphicon glyphicon-film', 'url' => '/app/videomodule/index'],
        'discoverymodule' => ['name' => '首页发现模块', 'gly' => 'glyphicon glyphicon-search', 'url' => '/app/discoverymodule/index'],
        'Assets' => ['name' => '静态资源管理', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/app/Assets/index'],
        'daySign' => ['name' => '每日日签管理', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/app/daySign/index'],
        'movieGuide' => ['name' => '观影秘籍', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/app/movieGuide/index'],
        'iconConfig' => ['name' => '可配置资源管理', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/app/iconConfig/index'],
        'biz' => ['name' => '商业化影片详情', 'gly' => 'glyphicon glyphicon-heart-empty', 'url' => '/app/biz/index'],
        'jspatch' => ['name' => 'JsPatch 配置', 'gly' => 'glyphicon glyphicon-star', 'url' => '/app/jspatch'],
        'module' => ['name' => 'APP模块开关', 'gly' => 'glyphicon glyphicon-th', 'url' => '/app/module'],
        'appHotUpdate' => ['name' => '客户端热更新', 'gly' => 'glyphicon glyphicon-th', 'url' => '/app/module'],
        //微信
        'activePage' => ['name' => '微信促销模板', 'gly' => 'glyphicon glyphicon-file', 'url' => '/weixin/activePage/index'],
        'activePageQq' => ['name' => '手Q红包模板', 'gly' => 'glyphicon glyphicon-file', 'url' => '/weixin/activePageQq/index'],
        'discoveryBanner2' => ['index' => 'discoveryBanner', 'name' => '发现频道', 'gly' => 'glyphicon glyphicon-search', 'url' => '/weixin/discoveryBanner/index'],
        'discoveryHead' => ['name' => '头条频道', 'gly' => 'fa fa-users', 'url' => '/weixin/discoveryHead/index'],
        'service' => ['name' => '客服记录', 'gly' => 'glyphicon glyphicon-user', 'url' => '/weixin/service/index'],
        'icon' => ['name' => '图标资源', 'gly' => 'glyphicon glyphicon-user', 'url' => '/weixin/icon/index'],
        'fulisheindex' => ['name' => '手Q福利社', 'gly' => 'fa fa-angle-down'],
        'fulishe' => ['name' => '福利社', 'url' => '/weixin/fulishe/index'],
        'fulisheRes' => ['name' => '福利社配置', 'url' => '/weixin/fulisheRes/index'],
        'find' => ['name' => '发现导流', 'gly' => 'fa fa-users', 'url' => '/weixin/find/index'],
        'redspot' => ['name' => '导流红点', 'gly' => 'fa fa-users', 'url' => '/weixin/redspot/index'],
        'wxpushLogcount' => ['name' => '评论推送发送量', 'gly' => 'fa fa-users', 'url' => '/weixin/wxpushLogcount/index'],
        //营销工具
        'luckActivity' => ['name' => '抽奖管理', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/tool/LuckActivity/index'],
        //消息中心
//        'Mesmessage' => ['name' => '新建消息', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/message/messageNotice/create'],
//        'tag' => ['name' => '标签管理', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/message/tag/index'],
        'messageNotice' => ['name' => '消息列表', 'gly' => 'glyphicon glyphicon-picture', 'url' => '/message/messageNotice/index'],
        //全站
        'cinemaNotification' => ['name' => '影城公告', 'gly' => 'fa fa-exclamation-circle', 'url' => '/cinemaNotification/index'],
        'adindex' => ['name' => '银行管理', 'gly' => 'fa fa-angle-down'],
        'ad' => ['name' => '广告管理', 'url' => '/ad/index'],
        'bankPrivilege' => ['name' => '银行优惠', 'url' => '/bankPrivilege/index'],
        'bankInfo' => ['name' => '银行信息维护', 'url' => '/bankInfo/index'],
        'cinemaHallIndex' => ['name' => '影城管理', 'gly' => 'fa fa-folder'],
        'cinemaGroup' => ['name' => '影城分组', 'url' => '/cinemaGroup/index'],
        'cinemaHallFeature' => ['name' => '影城特效厅', 'url' => '/cinemaHallFeature/index'],
        'movieIndex' => ['name' => '影片管理', 'gly' => 'fa fa-film'],
        'movie' => ['name' => '影片管理', 'url' => '/movie/index'],
        'actor' => ['name' => '影人管理', 'url' => '/actor/index'],
        'hotMovie' => ['name' => '热映影片', 'url' => '/hotMovie/index'],
        'movieList' => ['name' => '片单管理', 'url' => '/movieList/index'],
        'movieMusic' => ['name' => '原声音乐', 'gly' => 'fa fa-folder', 'url' => '/movieMusic/index'],
        'CommentIndex' => ['name' => '评论回复管理', 'gly' => 'glyphicon glyphicon-file'],
        'comment' => ['name' => '影片评论管理', 'url' => '/comment/index'],
        'commentTag' => ['name' => '标签管理', 'url' => '/commentTag/index'],
        'addStartComment' => ['name' => '添加明星评论', 'url' => '/addStartComment/index'],
        'voiceComment' => ['name' => '主创说管理', 'url' => '/VoiceComment/index'],
        'statisticsVoice' => ['name' => '主创说统计', 'url' => '/statisticsVoice/index'],
        'commentRecommend' => ['name' => '影片推荐评论管理', 'url' => '/commentRecommend/index'],
        'commentStar' => ['name' => '认证用户(明星)管理', 'url' => '/commentStar/index'],
        'userTag' => ['name' => '认证用户(KOL)管理', 'url' => '/userTag/index'],
        'commentReply' => ['name' => '影片回复管理', 'url' => '/commentReply/index'],
        'statistics' => ['name' => '影片评论统计', 'url' => '/statistics/index'],
        'sensitiveWords' => ['name' => '敏感词管理', 'url' => '/sensitiveWords/index'],
        'shieldingWords' => ['name' => '屏蔽词管理', 'url' => '/shieldingWords/index'],
        'blackList' => ['name' => '黑名单管理', 'url' => '/blackList/index'],
        'cmsComment' => ['name' => '内容评论管理', 'url' => '/cmsComment/index'],
        'userIndex' => ['name' => '用户管理', 'gly' => 'fa fa-users'],
        'user' => ['name' => '用户管理', 'url' => '/user/index'],
        'userGroup' => ['name' => '用户权限分组', 'url' => '/userGroup/index'],
        'goldSeatIndex' => ['name' => '黄金锁座', 'gly' => 'fa fa-calendar-o'],
        'goldSeat' => ['name' => '过滤条件', 'url' => '/goldSeat/index'],
        'goodsCenter' => ['name' => '商品中心', 'url' => '/goldSeat/goodsCenter'],
        'orderInfo' => ['name' => '订单详情', 'url' => '/goldSeat/orderInfo'],
        'customization' => ['name' => '定制化选座', 'gly' => 'glyphicon glyphicon-wrench', 'urlC' => '/customization'],
        'log' => ['name' => '后台日志', 'gly' => 'fa fa-book', 'urlC' => '/log'],
        'activeIndex' => ['name' => '活动管理', 'gly' => 'glyphicon glyphicon-file'],
        'active' => ['name' => '活动管理(原)', 'url' => '/active/index'],
        'cms' => ['name' => 'CMS管理(新)', 'url' => '/cms/index'],
        'cmsFind' => ['name' => '发现管理(新)', 'url' => '/cmsFind/index', 'also' => empty($_GET['type'])],
        'cmsFind2' => ['index' => 'cmsFind', 'name' => '电影头条', 'url' => '/cmsFind/index?type=19', 'also' => !empty($_GET['type'])],
        'cmsNews' => ['name' => '资讯管理(新)', 'url' => '/cmsNews/index'],
        'author' => ['name' => '作者管理', 'url' => '/author/index'],
        'promotionSharing' => ['name' => '拉新分享', 'gly' => 'glyphicon glyphicon-picture', 'url' => 'promotionSharing/index'],
        'spellGroup' => ['name' => '拼团活动', 'url' => '/spellGroup/index'],
        'movieNews' => ['name' => '首页推荐管理', 'gly' => 'fa fa-book', 'urlC' => '/movieNews'],
        'pee' => ['name' => '尿点管理', 'gly' => 'fa fa-book', 'urlC' => '/pee'],
        'starActive' => ['name' => '明星见面会', 'gly' => 'fa fa-book', 'urlC' => '/starActive'],
        'starGreeting' => ['name' => '明星问候', 'gly' => 'fa fa-star', 'urlC' => '/starGreeting'],
        'searchRec' => ['name' => '推荐搜索', 'gly' => 'glyphicon glyphicon-search', 'urlC' => '/searchRec'],
        'redActive' => ['name' => '红点活动', 'gly' => 'fa fa-book', 'urlC' => '/redActive'],
        'applyIndex' => ['name' => '发现活动管理', 'gly' => 'glyphicon glyphicon-picture'],
        'applyActive' => ['name' => '活动管理', 'url' => '/applyActive/index'],
        'questionSet' => ['name' => '活动题集', 'url' => '/questionSet/index'],
        'applyRecord' => ['name' => '报名管理', 'url' => '/applyRecord/index'],
        'fuli' => ['name' => '福利频道', 'url' => '/fuli/index'],
        'liveShowTemp' => ['name' => '直播预热', 'url' => '/liveShowTemp/index'],
        'vote' => ['name' => '投票管理', 'gly' => 'fa fa-book', 'urlC' => '/vote'],
        'movieOrder' => ['name' => '影片排序', 'gly' => 'glyphicon fa fa-film', 'urlC' => '/movieOrder'],
        'filmFestival' => ['name' => '电影节管理', 'gly' => 'glyphicon fa fa-film', 'urlC' => '/filmFestival'],
        'tinker' => ['name' => 'Tinker 配置', 'gly' => 'glyphicon glyphicon-star', 'url' => '/app/tinker'],
        //演出
        'showComment' => ['name' => '评论管理', 'gly' => 'glyphicon glyphicon-file', 'url' => '/show/showComment/index'],
        'showCommentReply' => ['name' => '回复管理', 'gly' => 'glyphicon glyphicon-file', 'url' => '/show/showCommentReply/index'],
        //微信小程序
        'redEnvelop' => ['name' => '红包管理', 'gly' => 'glyphicon glyphicon-bold', 'url' => '/weixinsp/redEnvelop/index'],
    ]
];