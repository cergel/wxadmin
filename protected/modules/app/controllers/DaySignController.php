<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2015/11/6
 * Time: 14:49
 */

class DaySignController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'getday'),
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
        $model = new DaySign();


        if (isset($_POST['DaySign'])) {

            //过验证用的
            if (empty($_POST['DaySign']['iID'])) {
                $_POST['DaySign']['iID'] = date("Ymd", time());
            }

            //验证图片后缀名与文件类型
            $validatedImgs = $model->validateImg($_FILES['DaySign']);

            //图片为空时unset掉以进行验证
            foreach($_FILES['DaySign']['name'] as $k => $v) {
                if (in_array($k,$validatedImgs)) {
                    $_POST['DaySign'][$k] = $k;
                } else {
                    unset($_POST['DaySign'][$k]);
                }
            }

            //生成对应图片对象
            $objImg = [];
            foreach ($validatedImgs as $k) {
                $objImg[$k] = CUploadedFile::getInstance($model, $k);
            }

            //验证推荐类型的正确性
            $iType = $_POST['DaySign']['iType'];
            $model->ValidatorList->add(
                CValidator::createValidator('required', $model, $model->getPartList($iType))
            );
            $model->attributes = $_POST['DaySign'];
            //var_dump($model->attributes);

            if ($model->save()) {
                //保存图像
                $model->saveImg($model,$objImg);

                $url =  $model->processImang($model->iBackground);
                $model->iBackground = empty($url)?$model->iBackground:$url;
                //文字信息写库
                $model->save();
                //更新缓存
               // $model->getMemcachePush();
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('index', 'id' => $model->iID));
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

        if (isset($_POST['DaySign'])) {
            //验证图片后缀名与文件类型
            $validatedImgs = $model->validateImg($_FILES['DaySign']);

            //图片为空时unset掉以进行验证
            foreach($_FILES['DaySign']['name'] as $k => $v) {
                if (in_array($k,$validatedImgs)) {
                    $_POST['DaySign'][$k] = $k;
                } else {
                    unset($_POST['DaySign'][$k]);
                }
            }

            //生成对应图片对象
            $objImg = [];
            foreach ($validatedImgs as $k) {
                $objImg[$k] = CUploadedFile::getInstance($model, $k);
            }

            //验证推荐类型的正确性
            $iType = $_POST['DaySign']['iType'];
            $model->ValidatorList->add(
                CValidator::createValidator('required', $model, $model->getPartList($iType))
            );
            $model->attributes = $_POST['DaySign'];

            if ($model->save()) {
                //保存图像
                $model->saveImg($model,$objImg);
                $url =  $model->processImang($model->iBackground);
                $model->iBackground = empty($url)?$model->iBackground:$url;
                //文字信息写库
                $model->save();
                //更新缓存
               // $model->getMemcachePush();
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->iID));
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
    public function actionDelete()
    {
        $id = intval($_GET['iID']);
        $this->loadModel($id)->delete();
        $this->redirect('/app/DaySign/index');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new DaySign();
        $list = $model->getAll();
        $this->render('index', array(
            'model' => $model,
            'list' => $list,
        ));
    }

    /**
     * 获取当天主推
     */
    public function actionGetday()
    {
        $return = array('ret' => 0, 'sub' => 0, 'msg' => 'Success');
        $iID = $_POST['iID'];
        $model = new DaySign();
        $list = $model->getDay($iID);
        if(!empty($list['iBackground'])){
            $list['iBackground'] = strstr($list['iBackground'],'http')?$list['iBackground']:'/uploads/app_daySign/'.$list['iBackground'];
        }
        if (!empty($list)) {
            $return['data'] = $list;
        } else {
            $return['empty'] = 1;
        }
        echo json_encode($return);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DaySign the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = DaySign::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DaySign $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'app-day-sign-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}
