<?php

class MessageController extends Controller
{
    private $error = [];

    public function actionIndex()
    {
        $this->render('index');
    }

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
        return array(
            array('allow',
                'actions' => array('index', 'create', 'update', 'delete', 'save', 'status'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new UserTag;
        $tagList = $model->getTagList();
        
        //print_r($tagList);die;  
        $this->render('create', array(
            'model' => [],
            'tagList' => $tagList,
        ));
    }

    public function actionStatus()
    {
        $model = new Activity('search');
        $model->unsetAttributes();
        $this->render('status', array(
            'model' => $model,
        ));
    }


    public function actionSave()
    {

        $channelIds = explode(',', $_POST['channel']);
        //判断是否设置了推送时间
        if(empty($_POST['channel']))
        {
            $_POST['push_time'] = date("Y-m-d H:i:s",time() + 3600*24);
        }
        //判断是否推送Android渠道
        if (in_array(9, $channelIds)) {
            $this->_xg_android_push_id($_POST);
        }

        if (in_array(8, $channelIds)) {
            $this->_xg_ios_push_id($_POST);
        }

        if (empty($this->error)) {
            echo json_encode(['ret' => 0, "msg" => 'ok']);
            exit;
        } else {
            echo json_encode(['ret' => -1, "msg" => $this->error]);
            exit;
        }

    }

    public function actionDelete()
    {
        $this->_xg_client("/v2/push/delete_offline_msg", "GET", ['push_id' => '1519848096']);

    }

    /**
     * @param $post
     * @return bool
     */
    private function _xg_android_push_id($post)
    {
        $message = [];
        $message['title'] = $post['msg_title'];
        $message['content'] = $post['content_text'];
        $message['vibrate'] = 1;
        $message['message_type'] = 1;
        $message['message'] = json_encode($message);
        $message['environment'] = 0;
        $ret = $this->_xg_client('/v2/push/create_multipush', 'POST', $message);
        //Android pushId生成成功
        if ($ret['ret_code'] == 0) {
            $pushIdAndroid = $ret['result']['push_id'];
            $activity = new Activity;
            $activity->title = $post['msg_title'];
            $activity->content = $post['content_text'];
            $activity->c_t = date("Y-m-d H:i:s");
            $activity->u_t = date("Y-m-d H:i:s");
            $activity->push_date = date("Y-m-d H:i:s", strtotime($post['push_time']));
            $activity->push_type = 0;
            $activity->push_id = $pushIdAndroid;
            $activity->channel = 9;
            $activity->status = 0;
            $activity->content_type = $post['content_type'];
            //判断是否是图文消息
            if ($post['content_type'] == 2) {
                $activity->cover_pic_id = $post['img_path'];
                $activity->cover_pic = $post['msg_link'];
            } else {
                $activity->cover_pic = 0;
                $activity->link = 0;
            }

            //处理用户标签
            if (empty($post['taglist'])) {
                $activity->is_all = 1;
                $activity->tag = 0;
            } else {
                $activity->is_all = 1;
                $activity->tag = $post['taglist'];
            }

            if ($activity->save()) {
                return true;
            } else {
                //调用SDK删除生成成功的pushId
                $this->error['android'] = [];
                $this->error['android']['model_err'] = $activity->errors;
                return false;
            }

        } else {
            $this->error['android'] = [];
            $this->error['android']['error_code'] = $ret['ret_code'];
            $this->error['android']['err_msg'] = $ret['err_msg'];
            return false;
        }
    }

    private function _xg_ios_push_id($post)
    {
        $apns = [];
        $apns['alert'] = ['title' => $post['msg_title'], 'body' => $post['content_text']];
        $message = [];
        $message['aps'] = json_encode($apns);
        $message['environment'] = 2;
        $message['message_type'] = 0;
        $message['message'] = json_encode($apns);
        $ret = $this->_xg_client('/v2/push/create_multipush', 'POST', $message);
        //Android pushId生成成功
        if ($ret['ret_code'] == 0) {
            $pushIdAndroid = $ret['result']['push_id'];
            $activity = new Activity;
            $activity->title = $post['msg_title'];
            $activity->content = $post['content_text'];
            $activity->c_t = date("Y-m-d H:i:s");
            $activity->u_t = date("Y-m-d H:i:s");
            $activity->push_date = date("Y-m-d H:i:s", strtotime($post['push_time']));
            $activity->push_type = 0;
            $activity->push_id = $pushIdAndroid;
            $activity->channel = 8;
            $activity->status = 0;
            $activity->content_type = $post['content_type'];
            //判断是否是图文消息
            if ($post['content_type'] == 2) {
                $activity->cover_pic = $post['img_path'];
                $activity->link = $post['msg_link'];
            } else {
                $activity->cover_pic = 0;
                $activity->link = 0;
            }

            //处理用户标签
            if (empty($post['taglist'])) {
                $activity->is_all = 1;
                $activity->tag = 0;
            } else {
                $activity->is_all = 1;
                $activity->tag = $post['taglist'];
            }

            if ($activity->save()) {
                return true;
            } else {
                //调用SDK删除生成成功的pushId
                $this->error['android'] = [];
                $this->error['android']['model_err'] = $activity->errors;
                return false;
            }

        } else {
            $this->error['android'] = [];
            $this->error['android']['error_code'] = $ret['ret_code'];
            $this->error['android']['err_msg'] = $ret['err_msg'];
            return false;
        }
    }


    private function _xg_client($url, $method, $api_param)
    {
        $method = strtoupper($method);
        $config = Yii::app()->params['tencent_xg_push'];
        $api_url = $config['api_host'] . $url;
        $base_params = [
            'access_id' => $config['access_id'],
            'timestamp' => time(),
        ];
        $params = array_merge($api_param, $base_params);
        //腾讯要求为字典排序目前非字典排序签名可能生成失败不过信鸽自己的SDK也用的这种方式
        ksort($params);
        $str_param = null;
        foreach ($params as $key => $value) {
            $str_param .= $key . '=' . $value;
        }
        $sign = md5($method . $api_url . $str_param . $config['secret_key']);
        $params['sign'] = $sign;
        $curl_handler = curl_init();
        //设置API超时时间 2000毫秒
        curl_setopt($curl_handler, CURLOPT_NOSIGNAL, 1);
        curl_setopt($curl_handler, CURLOPT_TIMEOUT_MS, 2000);
        curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);

        //初始化CURL 判断使用的是GET方法还是POST方法
        if ($method == "GET") {
            $remote_url = "http://" . $api_url . "?" . http_build_query($params);
            die($remote_url);
            curl_setopt($curl_handler, CURLOPT_URL, $remote_url);
        } elseif ($method == "POST") {
            $remote_url = "http://" . $api_url;
            curl_setopt($curl_handler, CURLOPT_URL, $remote_url);
            curl_setopt($curl_handler, CURLOPT_POST, 1);
            curl_setopt($curl_handler, CURLOPT_POSTFIELDS, http_build_query($params));
            $headers = array("Content-type: application/x-www-form-urlencoded");
            curl_setopt($curl_handler, CURLOPT_HTTPHEADER, $headers);
        }
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
}