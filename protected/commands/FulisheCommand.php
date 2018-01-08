<?php

/**
 * 福利社
 *
 */
class FulisheCommand extends CConsoleCommand
{
    /**
     */
    public function actionSave()
    {
        echo 'start fulishe   '.date('Y-m-d H:i:s',time())."\n";
        echo Fulishe::model()->createJsonFile()."\n";
        echo 'fulishe end '.date('Y-m-d H:i:s',time())."\n";
        
    }

    /**
     * 福利
     */
    public function actionSaveFuli()
    {
        echo 'start fuli   '.date('Y-m-d H:i:s',time())."\n";
        echo Fuli::model()->saveFile('cron_fuli');
        echo 'fuli end '.date('Y-m-d H:i:s',time())."\n";
    }


}
