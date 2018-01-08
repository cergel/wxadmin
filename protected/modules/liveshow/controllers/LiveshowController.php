<?php

class LiveshowController extends Controller
{
    private $error = [];

    private $indexUri = '/liveshow/liveshow/index';

    private $cntvCreateRoomUrl = 'http://api.cnlive.com/open/api2/interacitve_chat/createRoom';

    const SP_KEY = '34adc9a9553a21b4979a0e473c700f1619004ecaf9d30a';
    const SP_ID_DEFAULT = '27_is8b1vkf35';

    //TODO
//    private $urlGetPlayVideo = 'http://api.cnlive.com/open/api2/zbsc/getLiveActivityPlayInfo';
//    private $urlGetPlayVideo = 'http://api.cnlive.com/open/api2/live_epg/getLiveActivityPlayInfo4App';
    private $urlGetPlayVideo = 'http://api.cnlive.com/open/api2/live_epg/play/app';
    private $urlGetRoomPopulation = 'http://api.cnlive.com/open/api/chat/host/getRoomPopulation';


    private $liveShowParams = [
        'name',//直播名称
        'star_name',//分享名称
        'wx_share_title',
        'qzone_share_title',
        'share_description',
        'pre_page_link',
        'live_page_link',
        'is_app',
        'start_time',
        'end_time',
    ];
    private $liveMainPartParams = [
        'name',//直播名称
        'star_name',//分享名称
        'wx_share_title',
        'qzone_share_title',
        'share_description',
    ];

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
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [];
    }

    private function filterParams($models, $filterArr)
    {
        $retArr = [];
        foreach ($models as $i => $item) {
            $obj = [];
            foreach ($item as $key => $value) {
                if (in_array($key, $filterArr)) {
                    $obj[$key] = $value;
                }
            }
            $retArr[] = $obj;
        }
        return $retArr;
    }

    /**
     * 首页直播列表
     */
    public function actionIndex()
    {
        $model = new Liveshow('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Liveshow'])) {
            $model->attributes = $_GET['Liveshow'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param Vote $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'vote-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function checkLink($arr)
    {
        $needle = [
            'http://',
            'https://',
            'wxmovie://',
        ];
        foreach ($arr as $item) {
            $isFalse = false;
            foreach ($needle as $need) {
                $ret = strpos(trim($item), $need);
                if ($ret !== false) {
                    $isFalse = true;
                    break;
                }
            }
            if (!$isFalse) {
                return false;
            }
        }
        return true;
    }

    private function checkTime($start, $end)
    {
        if ($start > $end) {
            return false;
        }
        return true;
    }

    private function checkParams($case)
    {
        $picTmpData = $_FILES['LiveshowPic']['tmp_name'];
        $picData = $_FILES['LiveshowPic']['name'];
        $paramsAll = $this->liveShowParams;
        $updateArr = [];
        if ($case == 1) {
            $params = $this->liveShowParams;
            //校验链接是否有效
            $checkLinkArr = [
                $_REQUEST['Liveshow']['pre_page_link'],
                $_REQUEST['Liveshow']['live_page_link'],
            ];
            $ret = $this->checkLink($checkLinkArr);
            if (!$ret) {
                Yii::app()->user->setFlash('error', "链接不合法");
                if (isset($_REQUEST['id'])) {
                    $this->redirect('/liveshow/liveshow/edit?id=' . $_REQUEST['id']);
                } else {
                    $this->redirect('/liveshow/liveshow/edit');
                }
            }
            //校验时间是否有效
            $ret = $this->checkTime(strtotime($_REQUEST['Liveshow']['start_time']),
                strtotime($_REQUEST['Liveshow']['end_time']));
            if (!$ret) {
                Yii::app()->user->setFlash('error', "开始时间/结束时间不合法");
                if (isset($_REQUEST['id'])) {
                    $this->redirect('/liveshow/liveshow/edit?id=' . $_REQUEST['id']);
                } else {
                    $this->redirect('/liveshow/liveshow/edit');
                }
            }
        } else {
            $params = $this->liveMainPartParams;
        }

        if ($_REQUEST['yt0'] == 'Save') { //新建
            if (empty($picData['sharepic_link']) || empty($picData['pre_link'])) {
                Yii::app()->user->setFlash('error', "参数不完整");
                if (isset($_REQUEST['id'])) {
                    $this->redirect('/liveshow/liveshow/edit?id=' . $_REQUEST['id']);
                } else {
                    $this->redirect('/liveshow/liveshow/edit');
                }
            }
            if ($case == 1) {
                if (empty($picData['pre_banner_pic']) || empty($picData['live_banner_pic'])) {
                    Yii::app()->user->setFlash('error', "参数不完整");
                    if (isset($_REQUEST['id'])) {
                        $this->redirect('/liveshow/liveshow/edit?id=' . $_REQUEST['id']);
                    } else {
                        $this->redirect('/liveshow/liveshow/edit');
                    }
                }
            }
            foreach ($params as $key => $value) {
                if (!isset($_REQUEST['Liveshow'][$value]) || empty($_REQUEST['Liveshow'][$value])) {
                    Yii::app()->user->setFlash('error', "参数不完整");
                    $this->redirect('/liveshow/liveshow/edit');
                }
            }
        } else { //编辑
            $model = new Liveshow();
            $liveshow = $model->findByPk($_REQUEST['id']);
            if ($case == 1) {
                if((empty($liveshow->pre_banner_pic) && empty($picData['pre_banner_pic'])) || (empty($liveshow->live_banner_pic) && empty($picData['live_banner_pic']))){
                    Yii::app()->user->setFlash('error', "参数不完整");
                    if (isset($_REQUEST['id'])) {
                        $this->redirect('/liveshow/liveshow/edit?id=' . $_REQUEST['id']);
                    } else {
                        $this->redirect('/liveshow/liveshow/edit');
                    }
                }
            }

        }
        foreach ($paramsAll as $key => $value) {
            if (isset($_REQUEST['Liveshow'][$value]) && !empty($_REQUEST['Liveshow'][$value])) {
                $updateArr[$value] = $_REQUEST['Liveshow'][$value];
            }
        }

        return $updateArr;
    }

    /**
     * 新建\编辑\保存
     */
    public function actionEdit()
    {
        $liveshow = null;
        $model = new Liveshow();
        $cdn = Yii::app()->params['live_show']['cdn'];
        $picUriArr = [];
        $updateArr = [];
        if (isset($_REQUEST['yt0'])) {
            $picTmpData = $_FILES['LiveshowPic']['tmp_name'];
            $picData = $_FILES['LiveshowPic']['name'];
            if (isset($_REQUEST['Liveshow']['is_app']) && ($_REQUEST['Liveshow']['is_app'] == 1)) {
                $updateArr = $this->checkParams(1);
            } else {
                $updateArr = $this->checkParams(0);
            }


            //图片更新
            foreach ($picData as $key => $value) {
                $picUriArr[$key] = empty($value) ? '' : $this->uploadFile($picTmpData[$key], $value);
            }

            $postArr = [
                'wx_share_title' => $_REQUEST['Liveshow']['wx_share_title'],
                'qzone_share_title' => $_REQUEST['Liveshow']['qzone_share_title'],
                'share_content' => $_REQUEST['Liveshow']['share_description'],
                'pic_link' => $cdn . $picUriArr['sharepic_link'],
                'pre_link' => $cdn . $picUriArr['pre_link'],
                'pre_banner_pic' => $cdn . $picUriArr['pre_banner_pic'],
                'live_banner_pic' => $cdn . $picUriArr['live_banner_pic'],
                'movie_id' => $_REQUEST['Liveshow']['movie_id'],
                'pre_page_link' => $_REQUEST['Liveshow']['pre_page_link'],
                'live_page_link' => $_REQUEST['Liveshow']['live_page_link'],
                'is_app' => isset($_REQUEST['Liveshow']['is_app']) ? $_REQUEST['Liveshow']['is_app'] : 0,
                'start_time' => strtotime($_REQUEST['Liveshow']['start_time']),
                'end_time' => strtotime($_REQUEST['Liveshow']['end_time']),
            ];
        }
        if (!empty($picUriArr['sharepic_link'])) {
            $updateArr['sharepic_link'] = $postArr['pic_link'];
        }
        if (!empty($picUriArr['pre_link'])) {
            $updateArr['beforelive_piclink'] = $postArr['pre_link'];
        }
        if (!empty($picUriArr['pre_banner_pic'])) {
            $updateArr['pre_banner_pic'] = $postArr['pre_banner_pic'];
        }
        if (!empty($picUriArr['live_banner_pic'])) {
            $updateArr['live_banner_pic'] = $postArr['live_banner_pic'];
        }


        if (isset($_REQUEST['id']) && isset($_REQUEST['yt0']) && $_REQUEST['yt0'] == 'Update') {//更新
            $updateArr['moveId'] = $_REQUEST['Liveshow']['movie_id'];
            $updateArr['start_time'] = strtotime($_REQUEST['Liveshow']['start_time']);
            $updateArr['end_time'] = strtotime($_REQUEST['Liveshow']['end_time']);
            $updateArr['is_app'] = isset($_REQUEST['Liveshow']['is_app']) ? $_REQUEST['Liveshow']['is_app'] : 0;
            $model->updateByPk($_REQUEST['id'], $updateArr);
            $liveshow = $model->findByPk($_REQUEST['id']);
            $postArr['pic_link'] = $liveshow->sharepic_link;
            $postArr['pre_link'] = $liveshow->beforelive_piclink;
            $postArr['pre_banner_pic'] = $liveshow->pre_banner_pic;
            $postArr['live_banner_pic'] = $liveshow->live_banner_pic;

            $postArr['streamId'] = $_REQUEST['id'];
            $model->setShareInfo($postArr);
            $this->redirect($this->indexUri);
        } else {
            if (isset($_REQUEST['id'])) {//渲染编辑页面
                $liveshow = $model->findByPk($_REQUEST['id']);
            } else {
                if (isset($_REQUEST['yt0']) && ($_REQUEST['yt0'] == 'Save')) {//新建保存
                    $updateArr['movie_id'] = $_REQUEST['Liveshow']['movie_id'];

                    $updateArr['start_time'] = (strtotime($_REQUEST['Liveshow']['start_time']));
                    $updateArr['end_time'] = (strtotime($_REQUEST['Liveshow']['end_time']));
                    $model->setAttributes($updateArr, false);
                    $ret = $model->save();
                    $postArr['streamId'] = $model->getPrimaryKey();
                    $model->setShareInfo($postArr);
                    $this->redirect($this->indexUri);
                } else {
                    $liveshow = $model;
                }
            }
        }
        $this->render('edit', ['model' => $liveshow,]);
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete($id);

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Vote the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Liveshow::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * 下线
     */
    public function actionOffline()
    {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $model = new StarLiveshow();
        $ret = $model->updateByPk($id, [
            'online_status' => $status,
        ]);
        if ($ret == 0) {
            //下线异常
            echo json_encode(['ret' => 0, "msg" => 'ok']);
            exit;
        }
    }


    /**
     * @param $id红包Id
     * @return array 红包Info
     */
    private function _getRedBagInfo($id)
    {
        $url = Yii::app()->params['live_show']['redbag_info_url'];
        $params_arr = [];
        $ret = $this->_curlUtil($url, $params_arr);
        if ($ret) {

        }
    }

    /**
     * @param $id选座券Id
     * @return array 选座券Info
     */
    private function _getSeatCardInfo($id)
    {
        $url = Yii::app()->params['live_show']['redbag_info_url'];
        $params_arr = [];
        $ret = $this->_curlUtil($url, $params_arr);
        if (!$ret) {
            return json_encode($params_arr);
        }
    }

    /**
     * curl封装
     * @param $remote_url 请求url
     * @param $params 请求参数数组
     * @return array|mixed
     */
    private function _curlUtil($remote_url, $params)
    {
//        $remote_url = $params['url'];
        $curl_handler = curl_init();
        //设置API超时时间 2000毫秒
        curl_setopt($curl_handler, CURLOPT_NOSIGNAL, 1);
        curl_setopt($curl_handler, CURLOPT_TIMEOUT_MS, 2000);
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl_handler, CURLOPT_URL, $remote_url);
        curl_setopt($curl_handler, CURLOPT_POST, 1);
        curl_setopt($curl_handler, CURLOPT_POSTFIELDS, http_build_query($params));
        $headers = array("Content-type: application/x-www-form-urlencoded");
        curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($curl_handler);
        $http_code = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE);
        if ($output !== false && $http_code == 200) {
            curl_close($curl_handler);
            return json_decode($output, true);
        } elseif ($output !== false && $http_code != 200) {
            return ['ret_code' => '-9998', 'err_msg' => "Http Code {$http_code}", 'result' => []];
        } else {
            $err_no = curl_errno($curl_handler);
            $err_msg = curl_error($curl_handler);
            curl_close($curl_handler);
            return ['ret_code' => '-9999', 'err_msg' => "({$err_no}) {$err_msg}", 'result' => []];
        }
    }

    /**
     * 上传文件
     * @param $tmpFileName
     * @param $fileName
     * @return bool|string
     */
    private function uploadFile($tmpFileName, $fileName)
    {
        //保存在数据库里的路径，/uploads开头
        $Path = '/uploads/liveshow/' . date("Ymd") . '/' . date('H');
        //本地文件路径,上传后要将文件移动到的地方
        $localPath = dirname(Yii::app()->basePath) . $Path;
        if (!is_dir($localPath)) {
            mkdir($localPath, 755, true);//第三个参数，递归创建
        }
        $extension = pathinfo($fileName);
        $extension = $extension['extension'];
        $fileName = md5(file_get_contents($tmpFileName));
        $fileName = $fileName . '.' . $extension;//文件名:  md5(文件内容).类型
        if (move_uploaded_file($tmpFileName, $localPath . '/' . $fileName)) {
            return $Path . '/' . $fileName;
        } else {
            return false;
        }
    }

    /**
     * 创建直播房间
     * @param $activeId
     * @param $name
     * @param string $appId
     */
    private function createCntvRoom($activeId, $name, $appId = self::SP_ID_DEFAULT)
    {
        $param = [
            'appId' => $appId,
            'id' => $activeId,
            'name' => $name,
        ];
        $strSign = $this->_makeSign($param, self::SP_KEY);
        $param['sign'] = $strSign;
        $response = $this->_curlUtil($this->cntvCreateRoomUrl, $param);
    }

    /**
     * 获取直播地址
     * @param array $arrInput ['activityId'] 活动ID，必须
     * @param array $arrInput ['sp_id']  应用ID，非必须，默认使用固定的
     */
    public function getPlayVideo($arrInput = [])
    {
        $param = [
            'appId' => self::_getParam($arrInput, 'sp_id', self::SP_ID_DEFAULT),
            'channelID' => self::_getParam($arrInput, 'activityId'),
            'platform_id' => 'com.tencent.movieticket',
//            'timestamp' => time(),
        ];
        $response = $this->_buildURL($this->urlGetPlayVideo, $param, self::SP_KEY);
        header('Content-type: application/json');
        return $response;
    }

    /**
     * 获取聊天室累计人数
     * @param array $arrInput ['sp_id']  应用ID，非必须，默认使用固定的
     * @param array $arrInput ['mediaId'] 聊天室ID，必须
     */
    public function getRoomPopulation($arrInput = [])
    {
        $param = [
            'sp_id' => self::_getParam($arrInput, 'sp_id', self::SP_ID_DEFAULT),
            'mediaId' => self::_getParam($arrInput, 'mediaId'),
        ];
        $response = $this->_buildURL($this->urlGetRoomPopulation, $param, self::SP_KEY);
        header('Content-type: application/json');
        echo $response;
        die;
    }

    /**
     * 返回前一段时间的所有redis集合名
     * @param int $timestep
     * @return array
     */
    public function _getPastSets($timestep = 60)
    {
        if ($timestep < $this->interval) {
            $timestep = $this->interval;
        }
        $firstTime = $this->time - $timestep;
        $firstSet = $this->_getNowSet($firstTime);
        $lastSet = $this->_getNowSet($this->time);
        $data = [];
        while ($firstSet < $lastSet) {
            $data[] = $firstSet;
            $firstTime = $firstTime + $this->interval;
            $firstSet = $this->_getNowSet($firstTime);
        }
        return $data;
    }

    /**
     * 返回输入时间戳对应的redis集合名
     * @param string $timestamp
     * @return string 2016070810X X=0~3
     */
    public function _getNowSet($timestamp = '')
    {
        $timestamp = ($timestamp) ? $timestamp : $this->time;
        return date('YmdHi', $timestamp) . floor(date('s', $timestamp) / $this->interval);
    }

    /**
     * 获取评论序列号
     * @return int
     */
    public function _getSeq()
    {
        $strKey = $this->prefix . 'seq';
        return $this->objRedis->incr($strKey);
    }

    /**
     * 判断一个数组中某个元素是否存在,如果存在,返回这个元素的值,否则返回默认类型
     * @param array $input
     * @param string $key
     * @param string $default
     */
    public static function _getParam(array $input, $key = '', $default = '')
    {
        return isset($input[$key]) ? $input[$key] : $default;
    }

    /**
     * 计算签名
     * @param $url
     * @param array $param
     * @param $sp_key
     * @return string
     */
    public function _makeSign($param = [], $sp_key = self::SP_KEY)
    {
        //排除空值
        foreach ($param as $k => $v) {
            if (empty($v) && $v != '0') {
                unset($param[$k]);
            }
        }
        ksort($param);
        $strKey = urldecode(http_build_query($param));
        $strSign = strtoupper(sha1($strKey . '&key=' . $sp_key));
        return $strSign;
    }

    public function _buildURL($url, $param = [], $sp_key = self::SP_KEY)
    {
        $strSign = $this->_makeSign($param, $sp_key);
        $paramUrl = '';
        foreach ($param as $k => $v) {
            $paramUrl = $paramUrl . $k . '=' . $v . '&';
        }
        $url = $url . '?' . $paramUrl . 'sign=' . $strSign;
        $response = $this->_curlUtil($url, $param);
        return $response;
    }

    public function array_urlencode($data)
    {
        $new_data = array();
        foreach ($data as $key => $val) {
            // 这里我对键也进行了urlencode
            $new_data[urlencode($key)] = is_array($val) ? $this->array_urlencode($val) : urlencode($val);
        }
        return $new_data;
    }

    public function _curl_get($url)
    {
        // 初始化一个cURL会话
        $curl = curl_init($url);
        // 不显示header信息
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 使用自动跳转
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        // 自动设置Referer
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        // 执行一个curl会话
        $tmp = curl_exec($curl);
        $res = curl_getinfo($curl);//返回包括http_code等信息
        // 关闭curl会话
        curl_close($curl);
        #return $res;
        return $tmp;
    }

    private function _checkLen($arr)
    {
        foreach ($arr as $key => $value) {
            $len = mb_strlen($value, 'utf8');
            if ($len > 16) {
                return false;
            }
        }
        return true;
    }

    private function _checkSize($file, $field)
    {
        if ($file['size'][$field] > 30760) {
            return false;
        }
        return true;
    }
}
