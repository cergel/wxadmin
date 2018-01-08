<?php
/**
 * Created by PhpStorm.
 * User: xiangli
 * Date: 16/3/11
 * Time: 11:56
 */

class MovieMusicCommand extends CConsoleCommand {

    public function actionRefreshMusic($time=3600)
    {
        ini_set('memory_limit','-1');
        echo 'start time is : '.date('Y-m-d H:i:s',time())."refresh:".date('Y-m-d H:i:s',time()-$time)."\n";
        $oObjs = MovieMusic::model()->findAll('updated < :updated and status=1', [':updated' => time()-$time]);//找出3600秒没有更新的一条数据
        foreach($oObjs as $obj){
            MovieMusic::model()->saveCache($obj->id);
        }
        echo 'end time is : ' . date('Y-m-d H:i:s',time())."\n";
    }

}