<?php

/**
 * Class PeeCommand
 *
 */
class PeeCommand extends CConsoleCommand
{
    /**
     * 点尿总数落地
     */
    public function actionSavePeeCount($movieId ='')
    {
        echo 'start pee count '.date('Y-m-d H:i:s',time())."\n";
        $sql = "UPDATE t_pee_info SET t_pee_info.pee_count = (SELECT count(*) FROM t_pee_user WHERE t_pee_info.p_id = t_pee_user.p_id)  ";
        if(!empty($movieId)){
            $sql .= " WHERE t_pee_info.t_id = '$movieId' ";
        }
        $peeInfo = PeeInfo::model()->saveSql($sql);;
        echo 'save count limit = '. $peeInfo."\n";
        echo 'pee end '.date('Y-m-d H:i:s',time())."\n";
        
    }

    /**
     * @tutorial 更新数据
     * @author liulong
     */
    private function savePee($movieId='')
    {
        $sql = "UPDATE t_pee_info SET t_pee_info.pee_count = (SELECT count(*) FROM t_pee_user WHERE t_pee_info.p_id = t_pee_user.p_id)  ";
        if(!empty($movieId)){
            $sql .= " WHERE t_pee_info.t_id = '$movieId' ";
        }
        //echo $sql."\n";exit;
        $peeInfo = PeeInfo::saveSql($sql);
        return $peeInfo;
    }
}
