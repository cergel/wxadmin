<?php
/**
 * Class MovieListCommand
 *
 */
class MovieListCommand extends CConsoleCommand
{
    private $movieListTableName = 't_movie_list';
    /**
     * 片单定时上线
     */
    public function run()
    {
        date_default_timezone_set('Asia/Shanghai'); 
        $now = time();
        $sql = "update {$this->movieListTableName} set state = 1 where state = 0 and online_time <= $now and online_time!=0";
        $command = Yii::app()->db->createCommand($sql);
        $res = $command->execute();   
        echo date('Y-m-d H:i:s',time())."上线了".$res."个片单".'<br/>';
    }

   
}

