<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/6/2
 * Time: 11:23
 */
class StatisticsVoiceController extends Controller
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
                'actions' => array('index','dataExcel'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $searchResult=array();
        $start_time = $end_time = $channel = $type ='';
        if(!empty($_REQUEST['start_time']) && !empty($_REQUEST['end_time'])){
            $start_time=$_REQUEST['start_time'];
            $end_time= $_REQUEST['end_time'];
            $channel=intval($_REQUEST['channel']);
            $end_time = date('Ymd',strtotime($end_time));
            $start_time = date('Ymd',strtotime($start_time));
            if(strtotime($end_time)-strtotime($start_time)<=2678400){
                $searchResult=Comment::model()->searchVoicsData($start_time,$end_time,$channel);
            }
            if(!empty($_REQUEST['type'])){
                self::saveFile($searchResult);
            }
        }
        $this->render('index',
            [
                'searchResult'=>$searchResult,
                'start_time' =>$start_time,
                'end_time' =>$end_time,
                'channel' =>$channel,
                'type'  =>$type,
            ]
        );
    }

    /**
     * 导出
     */
    private  function saveFile($searchResult)
    {
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=file_data.xls");
            echo '<table >';
            echo '<tr>';
            echo '<td align="center">时间</td>';
            echo '<td align="center">回复总量</td>';
            echo '<td align="center">点赞总量</td>';
            echo ' </tr>';
        foreach ($searchResult as $key => $result) {
            echo '<tr>';
            echo '<td  align="center">' . $key . '</td>';
            echo '<td align="center">' . $result['replyCount'] . '</td>';
            echo '<td align="center">' . $result['favorCount'] . '</td>';
            echo '</tr>';
        }
            echo '</table>';
        exit;
    }

}