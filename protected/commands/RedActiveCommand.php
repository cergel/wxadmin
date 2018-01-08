<?php

/**
 * Class PeeCommand
 *
 */
class RedActiveCommand extends CConsoleCommand
{

    /**
     * @tutorial 更新数据
     * @author liulong
     */
    public function actionSaveRedActive()
    {
        echo "start time ".date('Y-m-d H:i:s')."\n";
        RedActive::model()->saveCache();
        echo "end time ".date('Y-m-d H:i:s')."\n";
    }
}
