<?php

class RedspotController extends Controller
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
                'actions' => array('index', 'export', 'download'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        //读取之前数据
        $info = $this->getChannelInfo();
        $data = [
            'starttime' => date('Y-m-d H:i:s', $info[1]),
            'endtime' => date('Y-m-d H:i:s', $info[2]),
            'status' => $info[0],
            'indexid' => $this->operateId(),
        ];
        //更新处理
        if (!empty($_REQUEST['opType']) && $_REQUEST['opType'] == 'add') {
            if (!empty($_REQUEST['starttime']) && !empty($_REQUEST['endtime'])) {
                $newStartTime = $_REQUEST['starttime'];
                $newEndTime = $_REQUEST['endtime'];
                $newStatus = !empty($_REQUEST['status']) ? 1 : 0;
                if ($newStartTime == $data['starttime'] &&
                    $newEndTime == $data['endtime']
                ) {
                    //如果起始结束时间都不改变，则认为是同一次修改，只会改变状态
                    $this->setChannelInfo('show', $newStatus, $newStartTime, $newEndTime);
                } else {
                    //时间有改动，则用输入数据更新，同时索引ID加一
                    $this->setChannelInfo('show', $newStatus, $newStartTime, $newEndTime);
                    $this->operateId('incr');
                }

                $data = [
                    'starttime' => $newStartTime,
                    'endtime' => $newEndTime,
                    'status' => $newStatus,
                    'indexid' => $this->operateId(),
                ];
            }
        }
        $this->render('index', ['data' => $data]);
    }

    /**
     * 导流红点，读取要查询的渠道
     * @return array 示例 [ 开关0|1, 开始时间戳, 结束时间戳]
     */
    public function getChannelInfo($channelName = 'show')
    {
        $strKey = 'wx_3_redspot_info_channels';
        $redis = new Redis();
        $redisConfig = $this->getRedisConfig($strKey);
        $redis->connect($redisConfig, 28006);
        $redspotChannels = $redis->hGetAll($strKey);
        $arrTime = [];
        foreach ($redspotChannels as $redspotName => $strTime) {
            if ($redspotName == $channelName) {
                $arrTime = explode('|', $strTime);//开始时间|结束时间
            }
        }
        return $arrTime;
    }

    /**
     * 修改导流信息
     */
    public function setChannelInfo($channelName = 'show', $status, $starttime, $endtime)
    {
        $strKey = 'wx_3_redspot_info_channels';
        $redis = new Redis();
        $redisConfig = $this->getRedisConfig($strKey);
        $redis->connect($redisConfig, 28006);
        $channelInfo = implode('|', [$status, strtotime($starttime), strtotime($endtime)]);
        $redis->hMset($strKey, [$channelName => $channelInfo]);
    }

    /**
     * 获取导流红点对应ID，用于前端存储
     * @param string $operate 传入get或incr，默认get
     * @return int
     */
    public function operateId($operate = 'get')
    {
        $channelName = 'show';
        $strKey = 'wx_3_redspot_' . $channelName . '_id';
        $redis = new Redis();
        $redisConfig = $this->getRedisConfig($strKey);
        $redis->connect($redisConfig, 28006);
        if ($operate == 'incr') {
            $id = $redis->incr($strKey);
        } else {
            $id = $redis->get($strKey);
        }
        return $id;
    }

    /**
     * 获取key对应的redis地址
     * 注：由于commoncgi正在重构，因此临时写成直接读取redis写死在这里，后续需要进行修改！！
     * @param $strKey
     * @return string
     */
    public function getRedisConfig($strKey)
    {
        $config = [
            'wx_3_redspot_info_channels' => '10.66.183.245',
            'wx_3_redspot_show_id' => '10.66.183.244',
        ];
        $redisConfig = isset($config[$strKey]) ? $config[$strKey] : '';
        return $redisConfig;
    }
}
