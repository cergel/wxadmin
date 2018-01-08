<?php

/**
 * 活动cms的脚本程序
 * Class ActivecmsCommand
 */
class ActivecmsCommand extends CConsoleCommand
{
    //每天凌晨执行一次 将redis中的阅读数保存到db中
    public function actionSaveReadNum()
    {
        $db = new Active();
        $db->saveReadNum();
    }

    /**
     * 落地阅读数
     */
    public function actionSaveNum()
    {
       // $model = ActiveCms::model()->findAll();
       // foreach($model as $val){
       //     ActiveCms::model()->saveReadAndReply($val->iActive_id);
       // }
//        for($i=1;$i<=20000;$i++){
//        $model = ActiveCms::model()->findAll();
//        foreach($model as $val){
//            ActiveCms::model()->saveReadAndReply($val->iActive_id);
//        }
        for($i=1;$i<=25000;$i++){
            ActiveCms::model()->saveReadAndReply($i);
        }
        //落地点赞数

        $sql = "update t_active set iLikes =(select count(1) from `t_active_like_user` where t_active_like_user.`a_id` = t_active.`iActive_id`)";
        echo "\n";
        echo ActiveCms::model()->saveSql($sql);
        echo "\n";
        echo 'ok';
    }

    /**
     * 发现频道文章-推荐文章：自动
     */
    public function actionSaveFindList()
    {
        ActiveFind::model()->saveFindCmsOther();
    }

    /**
     * 发现-自动上线
     */
    public function actionSaveFindInfo()
    {
        ActiveFind::model()->saveRedisCacheForActiveFindList();
    }

    /**
     * 批量更新CMS的H5页面，请不要随便使用,
     */
    public function actionSaveCmsH5($t = 1)
    {
        $arrData = ActiveCms::model()->findAll();
        foreach($arrData as $val){
            if(empty($val->iStatus)){
                continue;
            }
//            if($val->iActive_id >= 160)
            sleep($t);
            //更新H5页面
            ActiveCms::model()->createFileList($val->iActive_id);
            //更新缓存
            ActiveCms::model()->saveCache($val->iActive_id);

            echo $val->iActive_id." is OK \n";
        }
    }

    /**
     * 更新发现内容的缓存,只执行一次就行
     */
    public function actionSaveAllFindInfo()
    {
        ActiveFind::model()->saveAllFindInfo();
    }
    /**
     * 资讯自动上线
     */
    public function actionSaveNews()
    {
        ActiveNews::model()->saveMovieNews();
    }

    /**
     * 要执行的sql   pre
     * db_activeuser
     * update t_active_find_channel set f_url = REPLACE(f_url,'http://pre.promotion.wepiao.com','https://promotion-pre.wepiao.com') ;
     *
     * update t_active set sShare_link = REPLACE(sShare_link,'http://pre.promotion.wepiao.com','https://promotion-pre.wepiao.com') ;
     * update t_active set sShare_link = REPLACE(sShare_otherLink,'http://pre.promotion.wepiao.com','https://promotion-pre.wepiao.com') ;
     *
     * 执行命令 全量刷新发现 ：/data/soft/php/bin/php /data/www/wxadmin/protected/yiic activeCms SaveAllFindInfo
     * chown -R www.www  /data/www/wxadmin/active_template/cms_h5/*
     *
     */


    /**
     * 要执行的sql   master
     * 数据库  db_activeuser
     * 更新发现跳转链接
     * update t_active_find_channel set f_url = REPLACE(f_url,'http://promotion','https://promotion') ;
     * 更新分享链接
     * update t_active set sShare_link = REPLACE(sShare_link,'http://pre.promotion.wepiao.com','https://promotion-pre.wepiao.com') ;
     * update t_active set sShare_link = REPLACE(sShare_otherLink,'http://pre.promotion.wepiao.com','https://promotion-pre.wepiao.com') ;
     *
     * 执行命令 全量刷新发现   /data/soft/php/bin/php /data/www/wxadmin/protected/yiic activeCms SaveAllFindInfo
     *
     * 修改文件权限  chown -R www.www  /data/www/wxadmin/active_template/cms_h5/*
     */

}
