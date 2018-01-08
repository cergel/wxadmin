<?php
class SpellGroupTeam extends CActiveRecord
{

	public function tableName()
	{
		return 't_spell_group_id';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getDbConnection()
	{
		return Yii::app()->db_active;
	}

    /**
     * 获取某状态下某活动团的数量
     * @param $active_id
     * @param int $status
     * @return mixed
     */
	public function getTeamCount($active_id,$status=1){
            $command = $this->getDbConnection()->createCommand("
            select count(*) as total from {$this->tableName()} where active_id={$active_id} and team_status={$status}
        ");
            $row = $command->queryRow();
            return $row['total'];
    }
}
