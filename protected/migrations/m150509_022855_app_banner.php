<?php

class m150509_022855_app_banner extends CDbMigration
{
	public function up()
	{
        $transaction=$this->getDbConnection()->beginTransaction();
        try
        {

            $this->execute("
DROP TABLE IF EXISTS `t_weixin_discovery_channel`;
            ");

            $this->execute("
CREATE TABLE `t_weixin_discovery_channel` (
  `iBannerID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `iType` tinyint(4) NOT NULL COMMENT '活动类型:0活动 1内容',
  `sTitle` varchar(100) NOT NULL COMMENT '标题',
  `sDescription` text COMMENT '描述',
  `iCategory` int(4) NOT NULL COMMENT '分类',
  `sTag` varchar(20) DEFAULT NULL COMMENT '自定义标签',
  `sPicture` varchar(200) DEFAULT NULL COMMENT '封面',
  `iStartAt` int(11) DEFAULT NULL COMMENT '活动开始时间',
  `iEndAt` int(11) DEFAULT NULL COMMENT '活动结束时间',
  `sLink` varchar(500) NOT NULL COMMENT '跳转链接',
  `iShowAt` int(11) NOT NULL COMMENT '前台显示时间',
  `iHideAt` int(11) NOT NULL COMMENT '前台显示结束时间',
  `iTop` tinyint(4) NOT NULL DEFAULT '0' COMMENT '置顶',
  `iStatus` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0关闭 1开启',
  `iSort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `iCreated` int(11) DEFAULT NULL COMMENT '创建时间',
  `iUpdated` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`iBannerID`)
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
