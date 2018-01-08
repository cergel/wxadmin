<?php
class SpellGroupJoinUser extends CActiveRecord
{
    
	public function tableName()
	{
		return 't_spell_group_join_user';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getDbConnection()
	{
		return Yii::app()->db_active;
	}
}
