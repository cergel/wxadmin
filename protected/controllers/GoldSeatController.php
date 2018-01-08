<?php

class GoldSeatController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array( // 操作日志过滤器
                'application.components.ActionLog'
            ),
            'accessControl', // perform access control for CRUD operations
            //	'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('index','goodsCenter','searchCinemaLockCount','searchMovie','searchCinema','changeRestrict','addMovie','delCinema','orderInfo','orderInfoExcel','delMovie'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $restrict = new SeatRestrict();
        $restrictStatus = $restrict->getAllRestrictStatus();
        $restrictCinemas = SeatRestrictCinema::getAllRestrictCinema();
        $restrictMovies = SeatRestrictMovie::getAllRestrictMovie();

        $this->render('index',
            [
                'restrictStatus' => $restrictStatus,
                'restrictCinemas' => $restrictCinemas,
                'restrictMovies' => $restrictMovies,
            ]
        );
    }

    public function actionGoodsCenter()
    {
        $startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : date("Y-m-d 00:00:00");
        $endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : date("Y-m-d 23:59:59");
        $goodsModel = new GoodsCenterLocalOrder();
        $orderCount = $goodsModel->getAllOrder($startTime, $endTime);
        $this->render('goodsCenter', ['orderCount' => $orderCount, 'startTime' => $startTime, 'endTime' => $endTime]);
    }

    public function actionSearchCinemaLockCount()
    {
        $startTime = isset($_REQUEST['startTime']) && empty($_REQUEST['startTime']) ? $_REQUEST['startTime'] : date("Y-m-d 00:00:00");
        $endTime = isset($_REQUEST['endTime']) && empty($_REQUEST['endTime']) ? $_REQUEST['endTime'] : date("Y-m-d 23:59:59");
        $cinemaNo = $_REQUEST['cinemaNo'];
        $goodsModel = new GoodsCenterLocalOrder();
        $orderCount = $goodsModel->getOrderByCinema($startTime, $endTime, $cinemaNo);
        echo json_encode($orderCount);die;
    }

    /**
     * 搜索影片
     */
    public function actionSearchMovie()
    {
        $keyword = $_REQUEST['keyword'];
        $result = [];
        $url = API_MOVIEDATABASE . '/movie/search';
        $sendData=[
            'keyWord'=>$keyword,
            'page'=> 1,
            'num' => 20,
            'movieInfo' => 1,
            'from' => 2,
        ];
        $strJson = Https::getPost($sendData,$url);
        $obj = json_decode($strJson);
        if($obj->ret==0 && $obj->sub==0){
            if(!empty($obj->data->data)) {
                foreach($obj->data->data as $key => $value)
                {
                    $result[] = ['id' => $key, 'movieName' => $value->MovieNameChs];
                }

            }
        }
        echo json_encode($result);die;
    }

    /**
     * 搜索影院
     */
    public function actionSearchCinema()
    {
        $keyword = $_REQUEST['keyword'];
        $city = explode(',', trim($_REQUEST['city'], ','));
//        $cinemaMongoModel = new BsonBaseCinema();
//        $result = $cinemaMongoModel->searchCinema($keyword, $city);
        echo json_encode([]);die;
    }

    /**
     * 添加过滤影片
     */
    public function actionAddMovie()
    {
        $movieNos = $_REQUEST['movieNos'];
        $movieNos = explode(',', trim( $movieNos, ','));
        $status = SeatRestrictMovie::setRestrictMovieNo($movieNos);
        $restrictMovies = SeatRestrictMovie::getAllRestrictMovie();
        $result = ['result' => $status, 'restrictMovies'=>$restrictMovies];
        echo json_encode($result);die;
    }

    /**
     * 删除过滤影片
     */
    public function actionDelMovie()
    {
        $movieNo = $_REQUEST['movieNo'];
        $status = SeatRestrictMovie::setRestrictMovieNo([$movieNo], true);
        $restrictMovies = SeatRestrictMovie::getAllRestrictMovie();
        $result = ['result' => $status, 'restrictMovies'=>$restrictMovies];
        echo json_encode($result);die;
    }

    /**
     * 改变是否过滤开关
     */
    public function actionChangeRestrict()
    {
        $status = (int)$_REQUEST['status'];
        $type = $_REQUEST['type'];

        $restrict = new SeatRestrict();
        $result = $restrict->changeRestrictStatus($status, $type);
        echo json_encode($result);die;
    }

    /**
     * 添加要过滤的影院
     */
    public function actionAddCinema()
    {
        $cinemaNos = explode(',', trim($_REQUEST['cinemaNos'], ','));
        $cinemaNames = explode(',', trim($_REQUEST['cinemaNames'], ','));
        $status = SeatRestrictCinema::setRestrictCinemaNo($cinemaNos, $cinemaNames);
        $restrictCinemas = SeatRestrictCinema::getRestrictCinemaList();
        $result = ['result' => $status, 'restrictCinemas'=>$restrictCinemas];
        echo json_encode($result);die;
    }
    /**
     * 删除要过滤的影院
     */
    public function actionDelCinema()
    {
        $cinemaNo = (int)$_REQUEST['cinemaNo'];
        $status = SeatRestrictCinema::setRestrictCinemaNo([$cinemaNo], [], true);
        $restrictCinemas = SeatRestrictCinema::getRestrictCinemaList();
        $result = ['result' => $status, 'restrictCinemas'=>$restrictCinemas];
        echo json_encode($result);die;
    }

    /**
     * 订单明细接口
     */
    public function actionOrderInfo(){
        $startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : date("Y-m-d 00:00:00");
        $endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : date("Y-m-d 23:59:59");
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;//第几页
        $goodsModel = new GoodsCenterLocalOrder();
        $dbResult = $goodsModel->getOrderInfo($startTime, $endTime );
        $renderData = $this->_formatOrderInfo($dbResult);
        //格式化数据
        $this->render('goodsCenterInfo', ['data' => $renderData, 'startTime' => $startTime, 'endTime' => $endTime]);
    }

    private function _formatOrderInfo($dbResult){
        if(!empty($dbResult)){
            foreach($dbResult as $v){
                //格式化状态
                switch($v->local_seat_status){
                    case 0:
                        $v->local_seat_status = '未锁坐';
                        break;
                    case 1:
                        $v->local_seat_status = '已锁座';
                        break;
                    case 2:
                        $v->local_seat_status = '出票中';
                        break;
                    case 3:
                        $v->local_seat_status = '已出票';
                        break;
                    case 4:
                        $v->local_seat_status = '已取票';
                        break;
                    case 6:
                        $v->local_seat_status = '已退票';
                        break;
                    case 7:
                        $v->local_seat_status = '退票失败';
                        break;
                    default:
                        $v->local_seat_status = '其他';
                        break;
                }
            }
        }
        return $dbResult;
    }

    /**
     * 订单明细接口--导出excel
     */
    public function actionOrderInfoExcel(){
        $startTime = isset($_REQUEST['startTime']) ? $_REQUEST['startTime'] : date("Y-m-d 00:00:00");
        $endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] : date("Y-m-d 23:59:59");
        $goodsModel = new GoodsCenterLocalOrder();
        $dbResult = $goodsModel->getOrderInfo($startTime, $endTime );
        $renderData = $this->_formatOrderInfo($dbResult);
        $outStr = '';
        $outStr .="采购时间,采购id,影院id,排期id,座位,状态,放映日期\n";
        foreach($renderData as $v){
            $v->created_time = (string)$v->created_time;
            $v->fix_order_id = (string)$v->fix_order_id;
            $outStr .="{$v->created_time},{$v->fix_order_id},{$v->cinema_no},{$v->schedule_id},{$v->seat},{$v->local_seat_status},{$v->show_time}\n";
        }
        $outStr = iconv('UTF-8',"GB2312//IGNORE",$outStr);
        header("Content-type:text/csv");
        header("Content-Disposition: attachment; filename=goldSeatOrder.csv");
        echo $outStr;
    }
}