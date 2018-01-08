<?php

class m150506_022611_active_page_sort_and_notice extends CDbMigration
{
	public function up()
	{
        $transaction=$this->getDbConnection()->beginTransaction();
        try
        {
            $this->execute("
ALTER TABLE `db_wxmovieadmin`.`t_active_page`
  ADD COLUMN `sNotice` TEXT NULL  COMMENT '注意事项' AFTER `sExtend`;
            ");

            $this->execute("
RENAME TABLE `t_active_page` TO `t_weixin_active_page`;
            ");

            $this->execute("
RENAME TABLE `t_active_page_bonus_resource` TO `t_weixin_active_page_bonus_resource`;
            ");

            $this->execute("
CREATE TABLE `t_weixin_active_page_cinema` (
  `iActivePageID` int(11) NOT NULL,
  `iCinemaID` int(11) NOT NULL,
  PRIMARY KEY (`iActivePageID`,`iCinemaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");

            $transaction->commit();
        }
        catch(Exception $e)
        {
            echo "Exception: ".$e->getMessage()."\n";
            $transaction->rollback();
            return false;
        }
	}

	public function down()
	{
		echo "m150506_022611_active_page_sort_and_notice does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}