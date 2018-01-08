<?php

class MessageNoticeController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            array(// 操作日志过滤器
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
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'create', 'update', 'delete', 'upload'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new MessageNotice;
        if (isset($_POST['MessageNotice'])) {
            $arrData = $_POST['MessageNotice'];
            unset($arrData['channel']);
            $arrData['push_date'] = strtotime($arrData['push_date']);
            $arrData['create_time'] = time();
            $arrData['update_time'] = time();
            if (!empty($_FILES['MessageNotice']['tmp_name']['msg_pic'])) {
                $tmpMsgPicName = $_FILES['MessageNotice']['tmp_name']['msg_pic'];
                $fileNameMsgPic = $_FILES['MessageNotice']['name']['msg_pic'];
               /* $fileSizeMsgPic = $_FILES['MessageNotice']['size']['msg_pic'];
                if ($fileSizeMsgPic > 1024*32) {
                    Yii::app()->user->setFlash('error', '分享图标不能大于32K');
                    $this->redirect(array('update', 'id' => $model->id));
                }
                * 
                */
                $msgPicUrl = $this->uploadFile($tmpMsgPicName, $fileNameMsgPic);
                $arrData['msg_pic'] = $msgPicUrl;
            }
            //上传用户openId文件
            $userfile = CUploadedFile::getInstanceByName('MessageNotice[user_file]');
            if ($_POST['MessageNotice']['push_type'] == 2 && $userfile) {
                $filePath = $this->_uploadFile($userfile, ['txt', 'csv']);
                if ($filePath) {
                    $arrData['user_file'] = $filePath;
                }
                else {
                    Yii::app()->user->setFlash('error', '导入openId失败');
                    $this->redirect(array('create'));
                }
            }
            $model->attributes = $arrData;
            if ($model->save()) {
                $channelArr = $this->_addMsgNoticeChannel($model->id);
                Yii::app()->user->setFlash('success', '创建成功');
//              调用接口写入消息队列
                $res = $this->_addMessageQueue($model->id, $arrData, $channelArr);
                Log::model()->sysLog('message_notice',$res);
                if($_POST['MessageNotice']['push_type'] == 2) {
                    $ret = $this->_addMessageUploadFile($model->id, $filePath);
                    Log::model()->sysLog('message_notice',$ret);
                }
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $model->msg_type = 1;
        $model->push_type = 1;
        $this->render('create', array(
            'model' => $model,
            'bool' => TRUE,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['MessageNotice'])) {
            $arrData = $_POST['MessageNotice'];
            unset($arrData['channel']);
            $arrData['push_date'] = strtotime($arrData['push_date']);
            $arrData['update_time'] = time();
            if (!empty($_FILES['MessageNotice']['tmp_name']['msg_pic'])) {
                $tmpMsgPicName = $_FILES['MessageNotice']['tmp_name']['msg_pic'];
                $fileNameMsgPic = $_FILES['MessageNotice']['name']['msg_pic'];
                /* $fileSizeMsgPic = $_FILES['MessageNotice']['size']['msg_pic'];
                  if ($fileSizeMsgPic > 1024*32) {
                  Yii::app()->user->setFlash('error', '分享图标不能大于32K');
                  $this->redirect(array('update', 'id' => $model->id));
                  }

                 */
                $msgPicUrl = $this->uploadFile($tmpMsgPicName, $fileNameMsgPic);
                $arrData['msg_pic'] = $msgPicUrl;
            } else {
                $arrData['msg_pic'] = $model->msg_pic;
            }
            if ($_POST['MessageNotice']['push_type'] == 2) {
                $userfile = CUploadedFile::getInstanceByName('MessageNotice[user_file]');
                if ($userfile) {
                    $filePath = $this->_uploadFile($userfile, ['txt', 'csv']);
                    if ($filePath) {
                        $arrData['user_file'] = $filePath;
                    } else {
                        Yii::app()->user->setFlash('error', '导入openId失败');
                        $this->redirect(array('update', 'id' => $model->id));
                    }
                } else {
                    $arrData['user_file'] = $model->user_file;
                }
            }
            $model->attributes = $arrData;
            if ($model->save()) {
                $channelArr = $this->_addMsgNoticeChannel($model->id);
                // 调用接口 加入消息队列
                $res = $this->_addMessageQueue($model->id,$arrData,$channelArr);
                Log::model()->sysLog('message_notice',$res);
                if($_POST['MessageNotice']['push_type'] == 2) {
                    $res = $this->_addMessageUploadFile($model->id, $arrData['user_file']);
                    Log::model()->sysLog('message_notice',$res);
                }
                //判断返回结果 写入日志
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $channelUrl = [];
        if (is_array($model->channel)) {
            $channel = [];
            foreach ($model->channel as $result) {
                $channelUrl[$result['channel']] = $this->_getPushUrl($result['id']);
                $channel[] = $result['channel'];
            }
            $model->channel = $channel;
        }
        $model->push_date = date('Y-m-d H:i:s', $model->push_date);
        $pushType = $model->push_type;
        if ($pushType == 0) {
            $bool = false;
        }
        else {
            $bool = true;
        }
        $this->render('update', array(
            'model' => $model,
            'channelUrl' => $channelUrl,
            'bool' => $bool,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();
        $res = $this->_delMessageQueue($id);
        Log::model()->sysLog('message_notice',$res);
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new MessageNotice('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MessageNotice']))
            $model->attributes = $_GET['MessageNotice'];
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * 上传文件处理
     * @param $file
     * @param $type
     * @param $path_type 1.缓存文件 2.音频 3.图像
     * @return string
     */
    private function _uploadFile($file, $type = ['mpe', 'mp3'], $path_type = 1) {
        if (!in_array(strtolower($file->getExtensionName()), $type)) {
            return FALSE;
        }
        $dir = '/uploads/message/';
        switch ($path_type) {
            case 1:
                $dir .= 'cache/';
                break;
            case 2:
                $dir .= 'video/';
                break;
            case 3:
                $dir .= 'portrait/';
                break;
        }
        $path = dirname(Yii::app()->BasePath) . $dir;
        if (!is_dir($path)) {
            mkdir($path, '0755', true);
        }
        $file_name = date('YmdHis') . rand(1000, 9999) . '.' . $file->getExtensionName();
        $re = $file->saveAs($path . $file_name, true);
        if ($re) {
            return $dir . $file_name;
        } else {
            return FALSE;
        }
    }

    /**
     * 消息通知 对应推送平台列表 表t_message_notice_channel
     * @param $id
     */
    private function _addMsgNoticeChannel($id) {
        $this->_delMsgNoticeChannel($id);
        $channelArr = [];
        if (isset($_POST['MessageNotice']['channel'])) {
            foreach ($_POST['MessageNotice']['channel'] as $val) {
                if (isset($_POST['channelUrl' . $val])) {
                    $objChannel = new MessageNoticeChannel();
                    $objChannel->message_notice_id = $id;
                    $objChannel->push_url = $_POST['channelUrl' . $val];
                    $objChannel->channel = $val;
                    if ($val == 8) {
                        $pushId = $this->_getXgPushIdIos($_POST['MessageNotice']);
                    } elseif ($val == 9) {
                        $pushId = $this->_getXgPushIdAndroid($_POST['MessageNotice']);
                    } else {
//                        $pushId = date("ymd", time()) . time();
                        $pushId = $this->_getXgPushIdIos($_POST['MessageNotice']);
                    }
                    $objChannel->push_id = $pushId;
                    $now = time();
                    $objChannel->create_time = $now;
                    $objChannel->update_time = $now;
                    $objChannel->save();
                    $channelArr[$val]['url'] = $_POST['channelUrl' . $val];
                    $channelArr[$val]['push_id'] = intval($pushId);
                }
            }
            return $channelArr;
        }
    }

    /**
     * @param $post
     * @return bool
     */
    private function _getXgPushIdAndroid($post) {
        $message = [];
        $message['title'] = $post['title'];
        $message['content'] = $post['content'];
        $message['vibrate'] = 1;
        $message['message_type'] = 1;
        $message['message'] = json_encode($message);
        $message['environment'] = 0;
        $ret = $this->_xg_client('/v2/push/create_multipush', 'POST', $message);
        //Android pushId生成成功
        if ($ret['ret_code'] == 0) {
            $pushIdAndroid = $ret['result']['push_id'];
            return $pushIdAndroid;
        } else {
            return false;
        }
    }

    private function _getXgPushIdIos($post) {
        $apns = [];
        $apns['alert'] = ['title' => $post['title'], 'body' => $post['content']];
        $message = [];
        $message['aps'] = json_encode($apns);
        $message['environment'] = 2;
        $message['message_type'] = 0;
        $message['message'] = json_encode($apns);
        $ret = $this->_xg_client('/v2/push/create_multipush', 'POST', $message);
        //Android pushId生成成功
        if ($ret['ret_code'] == 0) {
            $pushIdIos = $ret['result']['push_id'];
            return $pushIdIos;
        } else {
            return false;
        }
    }

    private function _xg_client($url, $method, $api_param) {
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

    /**
     * 渠道删除
     * @param $id
     */
    private function _delMsgNoticeChannel($id) {
        MessageNoticeChannel::model()->deleteAllByAttributes(array('message_notice_id' => $id));
    }

    /**
     * 根据id获取推送链接地址
     * @param type $id
     * @return type
     */
    private function _getPushUrl($id) {
        $messageNoticeChannel = Yii::app()->db->createCommand()
                ->select('push_url')
                ->from('t_message_notice_channel')
                ->where('id=:id', array(':id' => $id))
                ->queryRow();
        return $messageNoticeChannel['push_url'];
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return MessageNotice the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = MessageNotice::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param MessageNotice $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'message-notice-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 上传图片到腾讯云
     * @param $tmpFileName
     * @param $fileName
     * @return bool|string
     */
    private function uploadFile($tmpFileName, $fileName) {
        //保存在数据库里的路径，/uploads开头
        $path = '/uploads/active/' . date("Ymd") . '/' . date('Hi');
        $extension = pathinfo($fileName);
        $extension = $extension['extension'];
        $fileName = md5(file_get_contents($tmpFileName) . rand(1000, 9999) . time());
        $fileName = $fileName . '.' . $extension; //文件名:  md5(文件内容).类型
        // 上传到腾讯云
        $url = CosUpload::upload($tmpFileName, $path . '/' . $fileName);
        if (!empty($url)) {
            return $url;
        } else {
            return false;
        }
    }

    /**
     * 新建编辑消息中心成功之后调用接口写入消息队列
     * @param $id 推送消息ID
     * @param $arrData 推送消息数据
     * @param $channelData 推送平台对应的pushid和url
     * @return mixed
     */
    private function _addMessageQueue($id,$arrData,$channelData){
        $apiUrl =Yii::app()->params->messageCenter['push'];
        if($arrData['push_type'] == 0) {
            $arrData['push_type'] = 3;
        }

        $sendData=[
            'id'=> intval($id),
            'send_time'=> intval($arrData['push_date']),
            'type'=> intval($arrData['msg_type']),
            'title' => $arrData['title'],
            'img_url' => $arrData['msg_pic'],
            'content' => $arrData['content'],
            'channel' => $channelData, //循环用户勾选的推送平台
            'push_user_type' => intval($arrData['push_type']),  // 需要转换为浩然定义的常量值
            'is_push' => intval($arrData['is_push']),
            'push_content' => $arrData['push_msg'],
            'task_id' => intval($arrData['task_id']),
        ];
        $res = Https::getPost($sendData, $apiUrl, true);
        return $res;
    }

    /**
     * 删除消息队列
     * @param $id
     * @return mixed
     */
    private function _delMessageQueue($id){
        $apiUrl =Yii::app()->params->messageCenter['delete'];
        $sendData = [
            'id' => $id,
        ];
        $sendData = json_encode($sendData);
        $res = https::getPost($sendData, $apiUrl, true);
        return $res;
    }

    /**
     * 消息队列中导入的文件
     * @param $id
     * @param $file
     * @return mixed
     */
    private function _addMessageUploadFile($id, $file){

        $basePath = Yii::app()->basePath;
        $apiUrl =Yii::app()->params->messageCenter['fileupload'];
        $wPath = $basePath.'/..'.$file;
        if (class_exists('\CURLFile',false)) {
            $cFile = new CURLFile($wPath);
        }
        else {
            $cFile = "@".$wPath;
        }
        $fields['file'] = $cFile; // 前面加@符表示上传图片
        $fields['pushid'] = $id;
        $res = https::getPost($fields, $apiUrl, false, false, false);
        return $res;
    }

}
