<?php

/**
 * 操作日志Filter
 *
 * 所有的action执行成功都会记录到数据库
 */
class ActionLog extends CFilter
{
    protected function preFilter($filterChain)
    {
        $strJson = json_encode($_POST);
        if(strlen($strJson)>60000){
            $strJson = substr($strJson,0,60000).'...';//如果太长给他截取
        }
        $log = new Log;
        $log->sUri    = $_SERVER['REQUEST_URI'];
        $log->sIp     = $_SERVER['REMOTE_ADDR'];
        $log->sData   = $strJson;
        $log->iUserID = Yii::app()->user->getId();
        $log->iCreated = time();
        $log->save();
        /*
        if ($_POST)
            var_dump($log);
        */
        // logic being applied before the action is executed
        return true; // false if the action should not be executed
    }

    protected function postFilter($filterChain)
    {
        // logic being applied after the action is executed
        return true;
    }
}
