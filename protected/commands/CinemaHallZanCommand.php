<?php

class CinemaHallZanCommand extends CConsoleCommand
{
    public function run($args)
    {
        //更新赞
        $this->actionSaveZan(true);
        //更新踩
        $this->actionSaveZan(false);
    }

    /**
     * 刷新特效厅点赞踩人数
     * @param $is_zan
     * @return bool
     */
    public function actionSaveZan($is_zan = true)
    {
        if ($is_zan) {
            $f_status = 1;
            $update_str = 'zan_num';
        } else {
            $f_status = 0;
            $update_str = 'step_num';
        }
        $sql = 'SELECT 
                 	COUNT(t_cinema_favor.id) as real_zan_num, 
                  	t_cinema_hall_feature.zan_num, 
                  	t_cinema_hall_feature.id
               FROM 
               	  `t_cinema_hall_feature` 
               JOIN t_cinema_favor ON t_cinema_hall_feature.cinema_no = t_cinema_favor.cinema_id 
                	AND t_cinema_hall_feature.hall_no = t_cinema_favor.hall_id 
               WHERE t_cinema_favor.f_status = ' . $f_status . '
               GROUP BY 
                	t_cinema_hall_feature.cinema_no, 
                	t_cinema_hall_feature.hall_no 
               HAVING 
	               zan_num <> real_zan_num';
        $connection = Yii::app()->db_app;
        $commandData = $connection->createCommand($sql)->queryAll();
        if (empty($commandData)) {
            echo ' CinemaHallZan No update ' . $update_str . ' ' . date('Y-m-d H:i:s', time()) . "\n";
            return false;
        }
        $update_sql = 'UPDATE t_cinema_hall_feature SET ' . $update_str . ' = CASE id ';
        $ids = [];
        $whens = [];
        array_map(function ($val) use (&$ids, &$whens) {
            $ids[] = $val['id'];
            $whens[] = ' WHEN ' . $val['id'] . ' THEN ' . $val['real_zan_num'];
        }, $commandData);
        $update_sql .=
            implode(' ', $whens) . '  END WHERE id IN (' . implode(',', $ids) . ')';
        echo ' CinemaHallZan To update ' . $update_str . ' ' . date('Y-m-d H:i:s', time()) . "\n";
        return $connection->createCommand($update_sql)->execute();
    }

}
