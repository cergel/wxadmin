<?php

/**
 * Class ApplyActiveCommand
 *
 */
class ApplyActiveCommand extends CConsoleCommand
{
    /**
     * 总数落地
     */
    public function actionSaveApplyCount($id ='')
    {
        echo 'start apply count '.date('Y-m-d H:i:s',time())."\n";
        $sql = "UPDATE t_apply_active SET t_apply_active.support_count=(SELECT COUNT(1) FROM t_apply_record WHERE t_apply_record.a_id =t_apply_active.id )";
        if(!empty($id)){
            $sql .= " WHERE t_apply_active.id = '$id' ";
        }

        $count = ApplyActive::model()->saveSql($sql);
        echo 'save count limit = '. $count."\n";
        echo 'apply end '.date('Y-m-d H:i:s',time())."\n";
        
    }
}
