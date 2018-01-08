<?php
class BsonBaseMovie extends EMongoDocument {

    public $MovieNo;
	public $MovieNameChs;
    public $FirstShow;

    public function scopes()
    {
        return array(
            'default'=>array(
                'sort'=>array('FirstShow'=>EMongoCriteria::SORT_DESC),
	            //'limit'=>5,
	      ),
        );
    }
	public static function model($className = __CLASS__){
		return parent::model($className);
	}

	public function getCollectionName()
	{
		return 'Bson_Base_Movie';
	}

    public function searchMovie($keyword)
    {
        try{
            $where = new EMongoCriteria;
            $where->MovieNameChs = new MongoRegex('/'.$keyword.'/i');
            return $this->findAll($where);
        }catch (Exception $e){
            var_dump($e);die;
        }

    }

    public function findMovieByNo($movieNos)
    {
        $where = new EMongoCriteria();
        $where->MovieNo('in', $movieNos);
        return $this->findAll($where);
    }
}