<?php

/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 18:19
 */
class ApplyActiveController extends Controller
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
            array(
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('update', 'delete', 'create', 'index', 'ajaxUpload'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 图片异步上传接口
     */
    public function actionAjaxUpload()
    {
        $tmpFileName = $_FILES['UpLoadFile']['tmp_name'];
        $fileName = $_FILES['UpLoadFile']['name'];
        $r = $this->uploadFile($tmpFileName, $fileName);
        if ($r) {
            $arrReturn['success'] = 1;
            $arrReturn['path'] = $r;
        } else {
            $arrReturn['success'] = 0;
        }
        echo json_encode($arrReturn);
    }
//对上传的文件进行处理
    /**
     * @param $tmpFileName $_FILES中的tmp_name
     * @param $fileName $_FILES中的name
     * @return string
     */
    private function uploadFile($tmpFileName, $fileName)
    {
        //保存在数据库里的路径，/uploads开头
        $Path = '/uploads/applyActive/' . date("Ymd") . '/' . date('H');
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
     * 新增活动
     */
    public function actionCreate()
    {
        $model = new ApplyActive();
        if (isset($_POST['ApplyActive'])) {
            $platform = $_POST['ApplyActive']['platform'];
            $share = $_POST['ApplyActive']['share'];
            $model->attributes = $_POST['ApplyActive'];
            if ($model->is_form == 0) {
                $model->is_remark = 0;
                $model->remark = '';
            } else {
                if ($model->is_remark == 0) {
                    $model->remark = '';
                }
            }
            if ($model->save()) {
                //处理关联渠道
                if (!empty($platform))
                    ApplyActivePlatform::model()->deleteAllByAttributes(array('a_id' => $model->id));
                foreach ($platform as $platformId) {
                    if (empty($platformId)) continue;
                    $rac = new ApplyActivePlatform();
                    $rac->a_id = $model->id;
                    $rac->platform = $platformId;
                    $rac->save();
                }
                //处理关联平台
                if (!empty($share)) {
                    ApplyActiveShare::model()->deleteAllByAttributes(array('a_id' => $model->id));
                    foreach ($share as $shareId) {
                        if (empty($shareId)) continue;
                        $ral = new ApplyActiveShare();
                        $ral->a_id = $model->id;
                        $ral->share = $shareId;
                        $ral->save();
                    }
                }
                ApplyActive::model()->saveCache();
                $model->makeFile();
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('index'));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $arrPlatform = [];
        if (is_array($model->platform)) {
            foreach ($model->platform as $platform) {
                $arrPlatform[] = $platform['platform'];
            }
//            if (count($arrPlatform) == count($model->getPlatformKey()) - 1)
//                $arrPlatform[] = '0';
        }
        $model->platform = $arrPlatform;
        $arrShare = [];
        if (is_array($model->share)) {
            foreach ($model->share as $share) {
                $arrShare[] = $share['share'];
            }
//            if (count($arrShare) == count($model->getShareKey()) - 1)
//                $arrShare[] = '0';
        }
        $model->share = $arrShare;
        if (isset($model->price)) {
            $model->price = json_decode($model->price);
        }
        if (isset($_POST['ApplyActive'])) {
            $platform = $_POST['ApplyActive']['platform'];
            $share = $_POST['ApplyActive']['share'];
            $model->attributes = $_POST['ApplyActive'];
            if ($model->is_form == 0) {
                $model->is_remark = 0;
                $model->remark = '';
            } else {
                if ($model->is_remark == 0) {
                    $model->remark = '';
                }
            }
            if ($model->save()) {
                //处理关联渠道
                ApplyActivePlatform::model()->deleteAllByAttributes(array('a_id' => $model->id));
                if (!empty($platform)) {
                    foreach ($platform as $platformId) {
                        if (empty($platformId)) continue;
                        $rac = new ApplyActivePlatform();
                        $rac->a_id = $model->id;
                        $rac->platform = $platformId;
                        $rac->save();
                    }
                }
                //处理关联分享平台
                ApplyActiveShare::model()->deleteAllByAttributes(array('a_id' => $model->id));
                if (!empty($share)) {
                    foreach ($share as $shareId) {
                        if (empty($shareId)) continue;
                        $ral = new ApplyActiveShare();
                        $ral->a_id = $model->id;
                        $ral->share = $shareId;
                        $ral->save();
                    }
                }
                ApplyActive::model()->saveCache();
                $model->makeFile();
                $arrCdn = $model->platform;
                //写入CDN待刷新队列
                if(!empty($arrCdn)){
                    $arrCdn[] = '0';
                    $arrCdnPush = [];
                    foreach($arrCdn as &$val){
                        $arrCdnPush[] = Yii::app()->params['apply_active']['final_url'] . '/' . $model->id.'/'.$val.'/index.html';
                        $arrCdnPush[] = Yii::app()->params['apply_active']['final_url'] . '/' . $model->id.'/'.$val.'/share.html';
                    }
                    FlushCdn::setUrlToRedis($arrCdnPush);
                }
//                FlushCdn::setUrlToRedis(Yii::app()->params['apply_active']['final_url'] . '/' . $model->id, Yii::app()->params['apply_active']['local_dir'] . '/' . $model->id);
                Yii::app()->user->setFlash('success', '修改成功');
                $this->redirect(array('update', 'id' => $id));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionIndex()
    {
        $model = new ApplyActive('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ApplyActive']))
            $model->attributes = $_GET['ApplyActive'];

        $this->render('index', array(
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
        $model = $this->loadModel($id);
        $model->delete();
        ApplyActive::model()->saveCache();
        // if AJAX request (triggered by deletion via admin g rid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ApplyActive the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ApplyActive::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ApplyActive $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'apply-active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}