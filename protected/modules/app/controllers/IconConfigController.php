<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/11
 * Time: 15:36
 */
class IconConfigController extends Controller{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/main';

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
                'actions'=>array('index','create','update','delete','ajaxUpload','_status_update'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    /**
     * 图片异步上传接口
     */
    public function actionAjaxUpload(){
        $tmpFileName=$_FILES['UpLoadFile']['tmp_name'];
        $fileName=$_FILES['UpLoadFile']['name'];
        $r=$this->uploadFile($tmpFileName, $fileName);
        if($r){
            $arrReturn['success']=1;
            $arrReturn['path']=$r;
        }else{
            $arrReturn['success']=0;
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
        $Path = '/uploads/iconConfig/' . date("Ymd") . '/' . date('H');
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
     * ajax上下线
     */
    public function action_status_update($id){
        $model=$this->loadModel($id);
        if ($model->status == '1')
            $model->status = '0';
        else
            $model->status = '1';
        $model->save();
        echo $model->status;
    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new IconConfig();
        if(isset($_POST['IconConfig'])){
            //验证推荐类型的正确性
            $type = $_POST['IconConfig']['type'];
            $model->ValidatorList->add(
                CValidator::createValidator('required', $model, $model->getPartList($type))
            );
            $model->attributes=$_POST['IconConfig'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('index'));
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
        $arrPlatform = explode(',', $model->platform);
        $model->platform = $arrPlatform[0];
        if(isset($_POST['IconConfig'])){
            //验证推荐类型的正确性
            $type = $_POST['IconConfig']['type'];
            $model->ValidatorList->add(
                CValidator::createValidator('required', $model, $model->getPartList($type))
            );
            $model->attributes=$_POST['IconConfig'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update','id' => $model->id));
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
        $id = intval($_GET['id']);
        $this->loadModel($id)->delete();
        $this->redirect('/app/IconConfig/index');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model=new IconConfig('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['IconConfig'])){
            $model->attributes=$_GET['IconConfig'];
        }
       $this->render('index',array(
           'model'=>$model,
        ));
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DiscoveryBanner the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=IconConfig::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DiscoveryBanner $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='icon-config-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}