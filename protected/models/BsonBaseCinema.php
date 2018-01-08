<?php
class BsonBaseCinema extends EMongoDocument {

    public $CinemaNo;
	public $CinemaName;
    public $TheaterChain;
    public $TicketSaleSystem;
    public $CityName;

    public $groups;

	public static function model($className = __CLASS__){
		return parent::model($className);
	}

	public function getCollectionName()
	{
		return 'Bson_Base_Cinema';
	}

    public function searchCinema( $keyword, $city)
    {
        $where = new EMongoCriteria();
        $where->CinemaName = new MongoRegex('/'.$keyword.'/i');
        $where->CityName('in', $city);
        return $this->findAll($where);
    }

}