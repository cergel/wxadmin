<?php

class VoiceCommentController extends Controller
{
    use AlertMsg;
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
                'actions' => array('index', 'create', 'update', 'doDelete'),
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
        $model = new VoiceComment('create');
        if (isset($_POST['VoiceComment'])) {
            $voice = CUploadedFile::getInstanceByName('VoiceComment[voice]');
            $file_name = '';
            if ($voice) {
                $file_re = $this->upload($voice);
                if ($file_re['code'] != 0) {
                    $this->json_alert($file_re['code'], $file_re['msg']);
                }
                $file_name = $file_re['data'];
                $model->voice = $file_name;
            }
            $model_id = $_POST['VoiceComment']['movie_id'];
            $actor_id = $_POST['VoiceComment']['actor_id'];
            $times = (int)$_POST['VoiceComment']['times'];
            if (!empty($file_name) && ($times <= 0 || $times > 60)) {
                $this->json_alert(1, '音频时长请设置在1S-60S');
            }
            $clicks = $_POST['VoiceComment']['base_clicks'];
            $favors = $_POST['VoiceComment']['base_favor'];
            $order = $_POST['VoiceComment']['order'] ? $_POST['VoiceComment']['order'] : 0;
            $status = $_POST['VoiceComment']['status'] ? $_POST['VoiceComment']['status'] : 0;
            $model->_attributes = $_POST['VoiceComment'];
            if ($model->validate()) {
                $re = $model->apiAddVoiceComments($model_id, $actor_id, $file_name, $times, $clicks, $favors, $order, $status, $model->tips, $id = null);
                if (isset($re['code']) && $re['code'] == 0) {
                    Yii::app()->user->setFlash('success', '创建成功');
                    $this->json_alert(0, '创建成功');
                } else {
                    $this->json_alert(1, $re['msg']);
                }
                // $this->redirect(array('update', 'id' => $model->id));
            } else {
                $error = $model->getErrors();
                $this->json_alert(1, current(current($error)));
            }
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
        $model->fileName = '';
        $fileName_arr = !empty($model->voice_url) ? explode('_UName_', basename($model->voice_url)) : [];
        $BfileName_arr = !empty($model->voice_url) ? explode('_BName_', basename($model->voice_url)) : [];
        if (!empty($fileName_arr) && count($fileName_arr) >= 2) {
            $fileName = $fileName_arr[1];
            $model->fileName = $fileName;
        } else if (!empty($BfileName_arr) && count($BfileName_arr) >= 2) {
            $fileName = $BfileName_arr[1];
            $name = explode('.', $fileName);
            $model->fileName = base64_decode($name[0]) . '.' . $name[1];
        } else {
            $model->fileName = basename($model->voice_url);
        }
        if (isset($_POST['VoiceComment'])) {
            $voice = CUploadedFile::getInstanceByName('VoiceComment[voice]');
            $file_name = '';
            if ($voice) {
                $file_re = $this->upload($voice);
                if ($file_re['code'] != 0) {
                    $this->json_alert($file_re['code'], $file_re['msg']);
                }
                $file_name = $file_re['data'];
            }
            $movie_id = $_POST['VoiceComment']['movie_id'];
            $actor_id = $_POST['VoiceComment']['actor_id'];
            $voice_url = !empty($file_name) ? $file_name : $model->voice_url;
            $times = 0;
            if (isset($_POST['VoiceComment']['voiceDel']) && $_POST['VoiceComment']['voiceDel'] == 1) {
                $voice_url = '';
                $times = 0;
            }
            if ($voice_url)
                $times = (int)$_POST['VoiceComment']['times'];
            if (!empty($voice_url) && ($times <= 0 || $times > 60)) {
                $this->json_alert(1, '音频时长请设置在1S-60S');
            }
            $base_clicks = $_POST['VoiceComment']['base_clicks'] ? $_POST['VoiceComment']['base_clicks'] : $model->base_clicks;
            $base_favors = $_POST['VoiceComment']['base_favor'] ? $_POST['VoiceComment']['base_favor'] : $model->base_favor;
            $order = $_POST['VoiceComment']['order'] ? $_POST['VoiceComment']['order'] : $model->order;
            $status = $_POST['VoiceComment']['status'];
            $tips = $_POST['VoiceComment']['tips'];
            $model->_attributes = $_POST['VoiceComment'];
            if ($model->validate()) {
                $re = $model->apiAddVoiceComments($movie_id, $actor_id, $voice_url, $times, $base_clicks, $base_favors, $order, $status, $tips, $id);
                if (isset($re['code']) && $re['code'] == 0) {
                    Yii::app()->user->setFlash('success', '更新成功');
                    $this->json_alert(0, '更新成功');
                    //$this->redirect(array('/voiceComment/index'));
                } else {
                    $this->json_alert(0, $re['msg']);
                }
            } else {
                $error = $model->getErrors();
                $this->json_alert(1, current(current($error)));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = new VoiceComment();
        // $model->apiDelVoiceComments($id);
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new VoiceComment('search');
        $model->unsetAttributes();
        if (isset($_GET['VoiceComment']))
            $model->attributes = $_GET['VoiceComment'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return VoiceComment the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = VoiceComment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param VoiceComment $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'voice-comment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function upload($voice)
    {
        if (!in_array(strtolower($voice->getExtensionName()), ['mpe', 'mp3'])) {
            Yii::app()->user->setFlash('error', '请确保音频文件格式正确');
            return $this->alert_info(1, '请确保音频文件格式正确');
        }
        $dir = '/uploads/comment_star_voice/';
        $path = dirname(Yii::app()->BasePath) . $dir;
        $name = $voice->getName();
        $name = explode('.', $name);
        $baseName = base64_encode($name[0]);
        if (!is_dir($path)) {
            mkdir($path, '0755', true);
        }
        $file_name = date('YmdHis') . rand(1000, 9999) . '_BName_' . $baseName . '.' . $voice->getExtensionName();
        $voice->saveAs($path . $file_name, true);
        return $this->alert_info(0, '', $dir . $file_name);
    }

    public function actionDoDelete($id)
    {
        $model = $this->loadModel($id);
        $movie_id = $model->movie_id;
        $actor_id = $model->actor_id;
        $times = $model->times;
        $clicks = $model->base_clicks;
        $voice_url = $model->voice_url;
        $favors = $model->base_favor;
        $order = $model->order;
        $tips = $model->tips;
        $re = $model->apiAddVoiceComments($movie_id, $actor_id, $voice_url, $times, $clicks, $favors, $order, 0, $tips, $id);
        if ($re['code'] != 0) {
            Yii::app()->user->setFlash('success', '删除失败');
        } else {
            $this->loadModel($id)->delete();
            Yii::app()->user->setFlash('success', '删除成功');
        }
        $this->redirect(array('/voiceComment/index', 'id' => 1));
    }
}