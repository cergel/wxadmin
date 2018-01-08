<?php

class m150408_031944_active_page_bonus_resource extends CDbMigration
{
	public function up()
	{
        $transaction=$this->getDbConnection()->beginTransaction();
        try
        {
            $this->execute('
CREATE TABLE IF NOT EXISTS `t_active_page_bonus_resource` (
  `iActivePageID` int(11) NOT NULL COMMENT \'页面ID\',
  `iResourceID` int(11) NOT NULL COMMENT \'活动ID\'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=\'活动页与活动的关联表\';
            ');
            $this->execute('
ALTER TABLE `t_active_page_bonus_resource`
ADD PRIMARY KEY (`iActivePageID`,`iResourceID`);
            ');
            $this->execute("
CREATE TABLE `t_cinema_notification` (
  `iNotificationID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sName` varchar(100) NOT NULL COMMENT '名称',
  `sContent` text NOT NULL COMMENT '公告内容',
  `iShow` tinyint(4) DEFAULT NULL COMMENT '显示位置',
  `iStartAt` int(11) DEFAULT NULL COMMENT '开始时间',
  `iEndAt` int(11) DEFAULT NULL COMMENT '结束时间',
  `iStatus` tinyint(4) DEFAULT '0' COMMENT '状态 0：停用 1：可用',
  `iCreated` int(11) DEFAULT NULL COMMENT '创建时间',
  `iUpdated` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`iNotificationID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='影城公告';
            ");
            $this->execute("
CREATE TABLE `t_cinema_notification_cinema` (
  `iNotificationID` int(11) NOT NULL COMMENT '公告ID',
  `iCinemaID` int(11) NOT NULL COMMENT '影城ID',
  PRIMARY KEY (`iNotificationID`,`iCinemaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='影城与公告关联';
            ");
            $pages = ActivePage::model()->findAll();
            foreach($pages as $page) {
                //echo $page->iActivePageID . " " . $page->iActiveID . "\n";
                $this->execute('
INSERT INTO `t_active_page_bonus_resource` (`iActivePageID`, `iResourceID`)
VALUES (\''.$page->iActivePageID.'\', \''.$page->iActiveID.'\');
            ');
            }
            $this->execute("ALTER TABLE `t_active_page` DROP `iActiveID`;");
            $transaction->commit();
        }
        catch(Exception $e)
        {
            echo "Exception: ".$e->getMessage()."\n";
            $transaction->rollback();
            return false;
        }
        //return false;
	}

	public function down()
	{
		echo "m150408_031944_active_page_bonus_resource does not support migration down.\n";
        //DROP TABLE t_active_page_bonus_resource
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