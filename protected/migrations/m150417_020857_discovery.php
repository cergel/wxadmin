<?php

class m150417_020857_discovery extends CDbMigration
{
	public function up()
	{
        $transaction=$this->getDbConnection()->beginTransaction();
        try
        {
            $this->execute("
CREATE TABLE `t_cinema_group` (
  `iGroupID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '影院分组的主键Id',
  `sName` varchar(255) NOT NULL DEFAULT '' COMMENT '影院分组的名称',
  `iCreated` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `iUpdated` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `iStatus` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态，1表示正常',
  `iDeleted` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0表示为删除，1表示已删除',
  PRIMARY KEY (`iGroupID`),
  KEY `sName` (`sName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='影院分组';
            ");
            $this->execute("
CREATE TABLE `t_cinema_group_cinema` (
  `iGroupID` int(10) unsigned NOT NULL COMMENT '影院分组ID',
  `iCinemaID` int(10) unsigned NOT NULL COMMENT '影院ID',
  PRIMARY KEY (`iGroupID`,`iCinemaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='影城分组与影院ID关联表';
            ");
            $this->execute("
CREATE TABLE `t_weixin_discovery_channel` (
  `iBannerID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `iType` tinyint(4) NOT NULL COMMENT '活动类型:0活动 1内容',
  `sTitle` varchar(100) NOT NULL COMMENT '标题',
  `sDescription` text COMMENT '描述',
  `iCategory` int(4) NOT NULL COMMENT '分类',
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
            ");
			$this->execute("
CREATE TABLE `t_weixin_discovery_channel_city` (
  `iBannerID` int(11) NOT NULL,
  `iRegionNum` int(11) NOT NULL,
  PRIMARY KEY (`iBannerID`,`iRegionNum`)
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
		echo "m150417_020857_discovery does not support migration down.\n";
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