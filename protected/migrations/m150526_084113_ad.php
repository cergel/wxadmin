<?php

class m150526_084113_ad extends CDbMigration
{
	public function up()
	{
        $transaction=$this->getDbConnection()->beginTransaction();
        try
        {
            $this->execute("
CREATE TABLE `t_ad` (
  `iAdID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sTitle` varchar(200) NOT NULL COMMENT '标题',
  `sPath` varchar(200) DEFAULT NULL COMMENT '图片',
  `sLink` varchar(200) NOT NULL COMMENT '链接',
  `iType` tinyint(4) NOT NULL DEFAULT '1' COMMENT '广告类型, 方便以后扩展',
  `iShowAt` int(11) NOT NULL COMMENT '开始',
  `iHideAt` int(11) NOT NULL COMMENT '结束',
  `iMovieID` int(11) DEFAULT NULL COMMENT '电影',
  `iStatus` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `iCreated` int(11) DEFAULT NULL COMMENT '创建时间',
  `iUpdated` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`iAdID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");
            $this->execute("
CREATE TABLE `t_ad_cinema` (
  `iAdID` int(11) NOT NULL,
  `iCinemaID` int(11) NOT NULL,
  PRIMARY KEY (`iAdID`,`iCinemaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");
            $this->execute("
CREATE TABLE `t_ad_movie` (
  `iAdID` int(11) NOT NULL,
  `iMovieID` int(11) NOT NULL,
  PRIMARY KEY (`iAdID`,`iMovieID`)
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
        //return false;
	}

	public function down()
	{
		echo "m150526_084113_ad does not support migration down.\n";
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
