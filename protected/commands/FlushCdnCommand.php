<?php

/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/7/28
 * Time: 15:41
 * 刷新cdn地址脚本
 */
class FlushCdnCommand extends CConsoleCommand
{
    /**
     * 定时任务，写在服务器中的crontab中，每5分钟执行一次
     */
    public function actionConsumeRedis()
    {
        sleep(50);
        $flushCdn = new FlushCdn();
        $flushCdn->getUrlFromRedis();
    }

    /**
     * 刷新cdn
     */
    public function actionActiveSave()
    {
        $objData = ActivePage::model()->findAllBySql('select * from t_weixin_active_page  where iShowEndTime > '.time());
        if(!empty($objData)){
            foreach ($objData as $val){
                $model = ActivePage::model()->findByPk($val->iActivePageID);
                echo  "id ".$val->iActivePageID." is start \n";
                if($model){
                    $model->makeFile();
                    $url = Yii::app()->params['active_page_new']['final_url'] . '/' . $model->iActivePageID.'/index.html';
                    FlushCdn::setUrlToRedis($url);
                }
                echo  "id ".$val->iActivePageID." is OK \n";
            }
        }
        echo  "count = ".count($objData)."\n";
    }


}