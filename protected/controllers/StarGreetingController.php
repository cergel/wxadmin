<?php

class StarGreetingController extends Controller
{
    use AlertMsg;
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    private $portrait_list = ['portrait1', 'portrait2', 'portrait3', 'portrait4'];
    private $voice_list = ['voice1', 'voice2', 'voice3', 'voice4'];
    private $tips_list = ['tips1', 'tips2', 'tips3', 'tips4'];
    private $jump_list = ['jump_url1', 'jump_url2', 'jump_url3', 'jump_url4'];
    private $start_time = null;
    private $end_time = null;

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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxUpload', 'preview', 'ajaxBgUpload', 'ediType'),
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
        $model = new StarGreeting;
        if (isset($_POST['StarGreeting'])) {
            //时间过滤
            $this->checkTime($model);
            //上传处理
            $portrait_list = $this->portrait_list;
            $voice_list = $this->voice_list;
            if (!isset($_FILES['StarGreeting']['name'])) {
                $this->json_alert(1, '请选择上传文件');
            }
            $file_list = array_keys($_FILES['StarGreeting']['name']);
            $bg_img_path = '';
            $img_type = ['png', 'jpg'];
            if (in_array('bg_img', $file_list)) {
                $bg_img = CUploadedFile::getInstanceByName('StarGreeting[bg_img]');
                $bg_img_re = $this->upload($bg_img, $img_type, 2);
                if ($bg_img_re['code'] != 0) {
                    return $this->json_alert('1', $bg_img_re['msg']);
                }
                $bg_img_path = $bg_img_re['data']['path_file_name'];
            }
            //count处理
            $type = $_POST['StarGreeting']['type'];
            $json_data = $this->create_data();
            $model->title = $_POST['StarGreeting']['title'];
            $model->type = $_POST['StarGreeting']['type'];
            $model->status = $_POST['StarGreeting']['status'];
            $model->channel_id = $_POST['StarGreeting']['channel_id'];
            $model->bg_img = $bg_img_path;
            $model->start_time = $this->start_time;
            $model->end_time = $this->end_time;
            $model->content = $json_data;
            $model->created = time();
            $model->updated = time();
            if ($id = $model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                $model->pull($model->attributes['id']);
                $this->json_alert(0, '更新成功');
            }
            $error = $model->getErrors();
            $this->json_alert(1, current(current($error)));
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $count = $model->content ? json_decode($model->content, true) : '';
        $count1 = [];
        $count2 = [];
        $time_list = ['morning' => 1, 'forenoon' => 2, 'afternoon' => 3, 'night' => 4];
        if ($model->type == 2 && !empty($count)) {
            $count1 = $count;
        } elseif ($model->type == 1 && !empty($count)) {
            $count2 = $count;
        }
        if (isset($_POST['StarGreeting'])) {
            $this->checkTime($model);
            $content = json_decode($model->content, true);
            //文件上传
            $json_data = [];
            $type = (int)$_POST['StarGreeting']['type'];
            if (!in_array($type, [1, 2])) {
                $this->json_alert('1', '问候类型错误');
            }
            $file_list = isset($_FILES['StarGreeting']) ? array_keys($_FILES['StarGreeting']['name']) : [];
            $img_type = ['png', 'jpg'];
            $bg_img_path = '';
            if (in_array('bg_img', $file_list)) {
                $bg_img = CUploadedFile::getInstanceByName('StarGreeting[bg_img]');
                $bg_img_re = $this->upload($bg_img, $img_type, 2);
                if ($bg_img_re['code'] != 0) {
                    return $this->json_alert('1', $bg_img_re['msg']);
                }
                $bg_img_path = $bg_img_re['data']['path_file_name'];
            }
            if ($model->type != $type) {
                $json_data = $this->create_data();
            } else {
                if ($type == 2) {
                    $tips = trim($_POST['StarGreeting']['tips1']);
                    if (empty($tips)) {
                        $this->json_alert(1, '提示文案不能为空');
                    } elseif (mb_strlen($tips, 'utf8') > 20) {
                        $this->json_alert(1, '提示文案不能超过20个字符');
                    }
                    $json_data['tips'] = $tips;
                    if (in_array('portrait1', $file_list)) {
                        $portrait = CUploadedFile::getInstanceByName('StarGreeting[portrait1]');
                        $portrait_re = $this->upload($portrait, $img_type, 2);
                        if ($portrait_re['code'] != 0) {
                            $this->json_alert($portrait_re['code'], $portrait_re['msg'], $portrait_re['data']);
                        }
                        $json_data['star_img'] = $portrait_re['data']['path_file_name'];
                    } else {
                        $json_data['star_img'] = $content['star_img'];
                    }
                    if (in_array('voice1', $file_list)) {
                        $voice = CUploadedFile::getInstanceByName('StarGreeting[voice1]');
                        $voice_re = $this->upload($voice, ['mpe', 'mp3'], 3);
                        if ($voice_re['code'] != 0) {
                            return $this->json_alert('1', $voice_re['msg']);
                        }
                        $json_data['voice_url'] = $voice_re['data']['path_file_name'];
                    } else {
                        $json_data['voice_url'] = $content['voice_url'];
                    }
                    if (isset($_POST['StarGreeting']['jump_url1'])) {
                        $json_data['jump_url'] = $_POST['StarGreeting']['jump_url1'];
                    }
                    $json_data = json_encode($json_data);
                } else {
                    $time_list = ['morning' => 1, 'forenoon' => 2, 'afternoon' => 3, 'night' => 4];
                    foreach ($count as $key => &$val) {
                        $portrait_key = 'portrait' . $time_list[$key];
                        $voice_key = 'voice' . $time_list[$key];
                        $tips_key = 'tips' . $time_list[$key];
                        $jump_key = 'jump_url' . $time_list[$key];
                        if (!empty($_POST['StarGreeting'][$tips_key])) {
                            $val['tips'] = $_POST['StarGreeting'][$tips_key];
                            $val['jump_url'] = $_POST['StarGreeting'][$jump_key];
                        }
                        if (in_array($portrait_key, $file_list)) {
                            $portrait = CUploadedFile::getInstanceByName("StarGreeting[$portrait_key]");
                            $portrait_re = $this->upload($portrait, $img_type, 2);
                            if ($portrait_re['code'] != 0) {
                                return $this->json_alert('1', $portrait_re['msg']);
                            }
                            $val['star_img'] = $portrait_re['data']['path_file_name'];
                        }

                        if (in_array($voice_key, $file_list)) {
                            $voice = CUploadedFile::getInstanceByName("StarGreeting[$voice_key]");
                            $voice_re = $this->upload($voice, ['mpe', 'mp3'], 2);
                            if ($voice_re['code'] != 0) {
                                return $this->json_alert('1', $voice_re['msg']);
                            }
                            $val['voice_url'] = $voice_re['data']['path_file_name'];
                        }
                        $val = empty($val) ? (object)$val : $val;
                    }
                    $this->check($count);
                    $json_data = json_encode($count);
                }
            }
            $model->title = $_POST['StarGreeting']['title'];
            $model->type = $_POST['StarGreeting']['type'];
            $model->channel_id = $_POST['StarGreeting']['channel_id'];
            $model->bg_img = $bg_img_path ? $bg_img_path : $model->bg_img;
            $model->status = $_POST['StarGreeting']['status'];
            $model->start_time = $this->start_time;
            $model->end_time = $this->end_time;
            $model->content = $json_data;
            $model->updated = time();
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                $model->pull($id);
                $this->json_alert(0, '更新成功');
            }
        }
        $this->render('update', array(
            'model' => $model,
            'count1' => $count1,
            'count2' => $count2,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        $model = new StarGreeting();
        $model->pull($id, 1);
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new StarGreeting('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StarGreeting']))
            $model->attributes = $_GET['StarGreeting'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StarGreeting the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = StarGreeting::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StarGreeting $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'starGreetings-greeting-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 预览
     */
    public function actionAjaxUpload()
    {
        //上传文件list
        $portrait_list = $this->portrait_list;
        $voice_list = $this->voice_list;
        $tips_list = $this->tips_list;
        $file_list = isset($_FILES['StarGreeting']['name']) ? array_keys($_FILES['StarGreeting']['name']) : [];
        $post_list = isset($_POST['StarGreeting']) ? array_keys($_POST['StarGreeting']) : [];
        //获取上传key
        $portrait = array_intersect($portrait_list, $file_list);
        $voice = array_intersect($voice_list, $file_list);
        $msg_list = array_intersect($post_list, $tips_list);
        $portrait_name = !empty($portrait) ? 'StarGreeting[' . current($portrait) . ']' : '';
        $voice_name = !empty($voice) ? 'StarGreeting[' . current($voice) . ']' : '';
        if (!empty($msg_list)) {
            $tips = current($msg_list);
            $tips = $_POST['StarGreeting'][$tips];
        } else {
            $tips = '请输入消息提醒!';
        }
        //缓存图片上传
        $portrait_path = '';
        if (!empty($portrait_name)) {
            $img_type = ['png'];
            $portrait = CUploadedFile::getInstanceByName($portrait_name);
            $portrait_re = $this->upload($portrait, $img_type);
            if ($portrait_re['code'] != 0) {
                $this->json_alert($portrait_re['code'], $portrait_re['msg'], $portrait_re['data']);
            }
            $portrait_path = $portrait_re['data']['path_file_name'];
        }
        //缓存语音上传
        $path_file_name = '';
        if (!empty($voice_name)) {
            $voice = CUploadedFile::getInstanceByName($voice_name);
            $voice_re = $this->upload($voice);
            if ($voice_re['code'] != 0) {
                $this->json_alert($voice_re['code'], $voice_re['msg'], $voice_re['data']);
            }
            $path_file_name = $voice_re['data']['path_file_name'];
        }
        $data = ['tips' => $tips, 'voice_path' => $path_file_name, 'portrait_path' => $portrait_path];
        $this->json_alert(0, '上传成功', $data);
    }

    /**
     * bg缓存上传
     */
    public function actionAjaxBgUpload()
    {
        if (!isset($_FILES['StarGreeting']['name']['bg_img'])) {
            $this->json_alert(1, '请选择背景图片');
        }
        $bg_img = CUploadedFile::getInstanceByName('StarGreeting[bg_img]');
        $bg_img_re = $this->upload($bg_img, ['jpg', 'png'], 1);
        if ($bg_img_re['code'] != 0) {
            $this->json_alert(1, '背景图片上传失败');
        }
        $this->json_alert(0, '上传成功', ['BGUrl' => $bg_img_re['data']['path_file_name']]);
    }

    /**
     * 上传文件处理
     * @param $voice
     * @param $type
     * @param $path_type 1.缓存文件 2.音频 3.图像
     * @return string
     */
    private function upload($voice, $type = ['mpe', 'mp3'], $path_type = 1)
    {
        if (!in_array(strtolower($voice->getExtensionName()), $type)) {
            return $this->alert_info('1', '请保证上传文件格式正确');
        }
        $dir = '/uploads/StarGreeting/';
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
        $file_name = date('YmdHis') . rand(1000, 9999) . '.' . $voice->getExtensionName();
        $re = $voice->saveAs($path . $file_name, true);
        return $this->alert_info('0', '上传成功', ['file_name' => $file_name, 'path_name' => $path, 'path_file_name' => Yii::app()->BaseUrl . $dir . $file_name]);
    }


    /**
     * 最终储蓄验证
     * @param $data
     */
    private function check($data)
    {
        $name_list = ['morning' => '早晨', 'forenoon' => '上午', 'afternoon' => '下午', 'night' => '晚上'];
        foreach ($data as $key => $val) {
            if (!is_array($val))
                continue;
            if (empty($val['star_img'])) {
                $this->json_alert(1, $name_list[$key] . '的明星图像不能为空');
            }
            if (empty($val['voice_url'])) {
                $this->json_alert(1, $name_list[$key] . '的语音音频不能为空');
            }
            $tips = trim($val['tips']);
            if (empty($tips)) {
                $this->json_alert(1, $name_list[$key] . '的提示文案不能为空');
            } elseif (mb_strlen($tips, 'utf8') > 20) {
                $this->json_alert(1, $name_list[$key] . '的提示文案不能超过20个字符');
            }
            if ($val['jump_url']) {
                //url验证
            }
        }
    }

    /**
     * 预览页面
     */
    public function actionPreview()
    {
        $img_url = isset($_GET['img_file_name']) ? $_GET['img_file_name'] : '';
        $tips = isset($_GET['tips']) ? $_GET['tips'] : '';
        $voice_path = isset($_GET['voice_path']) ? $_GET['voice_path'] : '';
        $bg_img = isset($_GET['bg_img']) ? $_GET['bg_img'] : '';
        $this->layout = false;
        $this->render('starGreetings', ['img_url' => $img_url, 'tips' => $tips, 'voice_path' => $voice_path, 'bg_img' => $bg_img]);
    }

    /**
     * 创建data
     */
    private function create_data()
    {
        //上传处理
        $portrait_list = $this->portrait_list;
        $voice_list = $this->voice_list;
        $jump_list = $this->jump_list;
        if (!isset($_FILES['StarGreeting']['name'])) {
            $this->json_alert(1, '请选择上传文件');
        }
        $file_list = array_keys($_FILES['StarGreeting']['name']);
        //count处理
        $type = $_POST['StarGreeting']['type'];
        $img_type = ['png', 'jpg'];
        $json_data = [];
        if ($type == 2) {
            if (!in_array('portrait1', $file_list)) {
                return $this->json_alert('1', '请选择明星图像');
            }
            $portrait = CUploadedFile::getInstanceByName('StarGreeting[portrait1]');
            $portrait_re = $this->upload($portrait, $img_type, 2);
            if ($portrait_re['code'] != 0) {
                return $this->json_alert('1', $portrait_re['msg']);
            }

            $voice_path = '';
            if (in_array('voice1', $file_list)) {
                $voice = CUploadedFile::getInstanceByName('StarGreeting[voice1]');
                $voice_re = $this->upload($voice, ['mpe', 'mp3'], 3);
                if ($voice_re['code'] != 0) {
                    return $this->json_alert('1', $voice_re['msg']);
                }
                $voice_path = $voice_re['data']['path_file_name'];
            }

            $portrait_path = $portrait_re['data']['path_file_name'];
            $voice_path = $voice_re['data']['path_file_name'];
            $tips = trim($_POST['StarGreeting']['tips1']);
            if (empty($tips)) {
                return $this->json_alert('1', '提示文案不能为空');
            } elseif (mb_strlen($tips, 'utf8') > 20) {
                $this->json_alert(1, '提示文案不能超过20个字符');
            }
            $jump_url = $_POST['StarGreeting']['jump_url1'];
            if (!empty($jump_list)) {
                //url验证
            }
            $json_data = json_encode(['star_img' => $portrait_path, 'voice_url' => $voice_path, 'tips' => $tips, 'jump_url' => $jump_url]);
        } else {
            $msg = '';
            $time_list = [1 => 'morning', 2 => 'forenoon', 3 => 'afternoon', 4 => 'night'];
            //上传处理map
            array_map(function ($file_name) use ($voice_list, $portrait_list, &$json_data, $img_type, &$msg, $time_list, $file_list) {
                $time_key = substr($file_name, -1);
                $key_name = substr($file_name, 0, -1);
                if (!is_numeric($time_key)) {
                    return false;
                }
                if ($key_name == 'portrait') {
                    $voice_check_key = 'voice' . $time_key;
                    if (!in_array($voice_check_key, $file_list)) {
                        $this->json_alert('1', '请确保文件上传文件齐全');
                    }
                    $tips_check_key = 'tips' . $time_key;
                    if (!isset($_POST['StarGreeting'][$tips_check_key])) {
                        $this->json_alert('1', '请确保文件上传文件齐全');
                    }
                }
                $key_name = ['portrait' => 'star_img', 'voice' => 'voice_url'];
                $key_name = $key_name[substr($file_name, 0, -1)];
                $upload = CUploadedFile::getInstanceByName("StarGreeting[{$file_name}]");
                if (in_array($file_name, $portrait_list)) {
                    $upload_re = $this->upload($upload, $img_type, 2);
                } else if (in_array($file_name, $voice_list)) {
                    $upload_re = $this->upload($upload, ['mpe', 'mp3'], 3);
                }
                $time_key = $time_list[$time_key];
                if ($upload_re['code'] != 0) {
                    $this->json_alert(1, $upload_re['msg']);
                }
                $json_data[$time_key][$key_name] = $upload_re['data']['path_file_name'];
            }, $file_list);
            //文案处理map
            array_map(function ($tips) use (&$json_data, $time_list, $jump_list) {
                $time_key = substr($tips, -1);
                $jump_key = 'jump_url' . $time_key;
                if (isset($json_data[$time_list[$time_key]]['star_img'])) {
                    $json_data[$time_list[$time_key]]['tips'] = $_POST['StarGreeting'][$tips];
                    $jump_url = isset($_POST['StarGreeting'][$jump_key]) ? $_POST['StarGreeting'][$jump_key] : '';
                    $json_data[$time_list[$time_key]]['jump_url'] = $jump_url;
                } else {
                    $json_data[$time_list[$time_key]] = (object)[];
                }
            }, $this->tips_list);
            $this->check($json_data);
            $json_data = json_encode($json_data);
        }
        return $json_data;
    }

    /**
     * 时间检测
     * @param $model
     */
    private function checkTime($model)
    {
        //时间过滤
        $start_time = strtotime($_POST['StarGreeting']['start_time']);
        $end_time = strtotime($_POST['StarGreeting']['end_time'] . ' 23:59:59');
        if ($start_time > $end_time) {
            $this->json_alert('1', '时间顺序有误');
        }
        $id = $model->id ? $model->id : 0;
        $re = $model->scheduleCheck($start_time, $end_time, $id);
        if ($re !== false) {
            $this->json_alert('1', '请确保生效时间非重复');
        }
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    /**
     * 修改上线状态
     */
    public function actionEdiType($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == 0) {
            $model->status = 1;
            $msg = '上线成功';
        } else {
            $model->status = 0;
            $msg = '下线成功';
        }
        if ($model->save()) {
            $model->pull($model->attributes['id']);
            Yii::app()->user->setFlash('success', $msg);
        }
        $model = new StarGreeting;
        Yii::app()->user->returnUrl = Yii::app()->getBaseUrl() . "/starGreeting/index";
        $this->redirect(Yii::app()->user->returnUrl, ['model' => $model]);
    }
}
