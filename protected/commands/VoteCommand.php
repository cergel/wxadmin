<?php
/**
 * Class PeeCommand
 *
 */
class VoteCommand extends CConsoleCommand
{
      private $voteTableName = 't_vote';
    /**
     * 投票活动状态更改
     */
    public function run()
    {
        $now = time();
        $sql = "update {$this->voteTableName} set end_flag = 1 where end_flag = 0 and end_time <= $now";
        //echo $sql;die;
        $command = Yii::app()->db_active->createCommand($sql);
        $command->execute();
   
        
    }

   
}

