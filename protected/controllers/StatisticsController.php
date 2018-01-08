<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/6/2
 * Time: 11:23
 */
class StatisticsController extends Controller
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
                $searchResult=Comment::model()->searchData($start_time,$end_time,$channel);
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
            echo '<td align="center">看过量</td>';
            echo '<td align="center">想看量</td>';
            echo '<td align="center">评论总量</td>';
            echo '<td align="center">push评论量</td>';
            echo '<td align="center">购票评论</td>';
            echo '<td align="center">评论回复总量</td>';
            echo '<td align="center">点赞总量</td>';
            echo '<td align="center">超赞</td>';
            echo '<td align="center">不错</td>';
            echo '<td align="center">一般</td>';
            echo '<td align="center">睡着</td>';
            echo ' <td align="center">失望</td>';
            echo '<td align="center">烂片</td>';
            echo '<td align="center">表情合计</td>';
            echo ' </tr>';
        foreach ($searchResult as $key => $result) {
            echo '<tr>';
            echo '<td  align="center">' . $key . '</td>';
            echo '<td align="center">' . $result['seenCount'] . '</td>';
            echo '<td align="center">' . $result['wantCount'] . '</td>';
            echo '<td align="center">' . $result['commentCount'] . '</td>';
            echo '<td align="center">' . $result['pushCount'] . '</td>';
            echo '<td align="center">' . $result['purchaseCount'] . '</td>';
            echo '<td align="center">' . $result['replyCount'] . '</td>';
            echo '<td align="center">' . $result['favorCount'] . '</td>';
            echo '<td align="center">' . $result['score_1'] . '</td>';
            echo '<td align="center">' . $result['score_2'] . '</td>';
            echo '<td align="center">' . $result['score_3'] . '</td>';
            echo '<td align="center">' . $result['score_4'] . '</td>';
            echo '<td align="center">' . $result['score_5'] . '</td>';
            echo '<td align="center">' . $result['score_6'] . '</td>';
            echo '<td align="center">' . $result['scoreCount'] . '</td>';
            echo '</tr>';
        }
            echo '</table>';
        exit;
    }

}