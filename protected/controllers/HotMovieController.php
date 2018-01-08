<?php

class HotMovieController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

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
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'gethotmovie'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * Lists all models.
     */
    public function actionIndex() {
        $cityUrl = Yii::app()->params['citysList']['getCityList'];
        $cities = Https::curlGetPost($cityUrl);
        $citydata = json_decode($cities);
        $data = array();
        $data[''] = '全国';
        if ($citydata->ret == 0) {
            foreach ($citydata->data->list as $key => $val) {
                foreach ($val as $k => $v) {
                    $data[$v->id] = $v->name;
                }
            }
        }

        $this->render('index', array(
            'data' => $data,
            'selected' => 2,
        ));
    }

    public function actionGetHotMovie(){
        /**
         * 获取某城市的热映影片列表 cityid为空 代表全国
         */
        $cityId =  $_GET['cityid'];
        $url = Yii::app()->params['movie']['getHotMovie'];
        $info = Https::curlGetPost($url,'post',array('cityId'=>$cityId));
        $info1 = json_decode($info);
        if($info1->ret !== 0){
            $returnInfo = array(
                'ret' => -1,
                'msg' => '网络异常,稍后重试',
            );
            echo json_encode($returnInfo);
                        exit();
        }
        $movieIdArr = array();
        foreach ($info1->data as $val) {
            $movieIdArr[] = $val->movieNo;
        } 
        
        /**
         * 获取上面影片列表中 影片的上映时间
         */
        $movieDetailUrl = Yii::app()->params['movie']['getMovieInfo'];
//        echo $movieDetailUrl;die;
        $movieIdArrC = array_chunk($movieIdArr, 20);
        $movieDate = array();
        foreach ($movieIdArrC as $k => $v) {
            $movieIdStr = implode('|', $v);
            $movieDetailParam = array('channelId' => 3, 'movieId'=>$movieIdStr );
            $movieInfo = Https::curlGetPost($movieDetailUrl,'post',$movieDetailParam);
            $movieInfo = json_decode($movieInfo);
            if($movieInfo->ret==0) {
                foreach($movieInfo->data as $mv) {
                    $movieDate[$mv->MovieNo] = $mv->FirstTime ? date('Y-m-d', $mv->FirstTime): "";
//                    $movieDate[$mv->MovieNo] = $mv->FirstShow . $mv->FirstTime;

                }
            } else {
                $returnInfo = array(
                    'ret' => -1,
                    'msg' => '网络异常,稍后重试',
                );
                echo json_encode($returnInfo);
                exit();
            }
        }
        foreach ($info1->data as $k1 => $v1) {
            $info1->data[$k1]->movieDate = isset($movieDate[$v1->movieNo])?$movieDate[$v1->movieNo]:'';
        }
        
        $info1 = json_encode($info1);
        echo $info1;
    }
}
