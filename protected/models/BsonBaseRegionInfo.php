<?php
class BsonBaseRegionInfo extends EMongoDocument {
    public $Name;
    public $LevelType;
    public $RegionNum;
    public $DisplayPriority;
    public $RegionNumPath;
    public $RegionNamePath;
    public $WeiXinNo;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function getCollectionName() {
		return 'Bson_Base_RegionInfo';
	}
}