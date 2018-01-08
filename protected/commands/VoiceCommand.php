<?php

/**
 * 主创说的脚本程序
 */
class VoiceCommand extends CConsoleCommand
{
    //每天凌晨执行一次
    public function actionSaveAllVoice()
    {
        VoiceComment::model()->saveAllData();
    }



}
