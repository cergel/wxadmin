<?php

class CinemaHallFeatureController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxGetInfo', 'ajaxCreate', 'ajaxUpdate', 'ajaxPull'),
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
        $model = new CinemaHallFeature('create');
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new CinemaHallFeature('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CinemaHallFeature']))
            $model->attributes = $_GET['CinemaHallFeature'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CinemaHallFeature the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = CinemaHallFeature::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CinemaHallFeature $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cinema-hall-feature-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAjaxGetInfo()
    {
        if (!isset($_POST['cinema_no']) || !isset($_POST['hall_no'])) {
            $this->alert_info(1, '参数错误');
        }
        $cinema_no = trim($_POST['cinema_no']);
        $hall_no = trim($_POST['hall_no']);
        $connection = Yii::app()->db_opensystem;
        $sql = 'SELECT * FROM open_base_cinema_hall_feature WHERE `Status`=1 AND `BaseCinemaNo` = :cinema_no AND `HallNo` = :hall_no';
        $command = $connection->createCommand($sql);
        $command->bindParam(":cinema_no", $cinema_no);
        $command->bindParam(":hall_no", $hall_no);
        $base_info = $command->queryRow();
        if (empty($base_info)) {
            $this->alert_info(1, '未找到相关数据');
        }
        $base_info['zan'] = 0;
        $base_info['step'] = 0;
        $base_info['content'] = 0;
        $this->alert_info(0, '操作成功', $base_info);
    }

    /**
     * 异步插入
     */
    public function actionAjaxCreate()
    {
        if (isset($_POST['CinemaHallFeature'])) {
            $model = new CinemaHallFeature('create');
            $data = $_POST['CinemaHallFeature'];
            $data['created'] = time();
            $data['updated'] = time();
            $model->attributes = $data;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '创建成功');
                $this->push_api($data['cinema_no'], $data['hall_no'], $data['specific_description']);
                $this->alert_info(0, '创建成功');
            }
            $this->alert_info(1, current($model->errors)[0]);
        }
        $this->alert_info('1', '参数错误');

    }

    /**
     * 异步更新
     */
    public function actionAjaxUpdate()
    {
        if (isset($_POST['CinemaHallFeature'])) {
            $model = $this->loadModel($_POST['CinemaHallFeature']['id']);
            $data = $_POST['CinemaHallFeature'];
            unset($data['CinemaHallFeature']['id']);
            $data['updated'] = time();
            $model->attributes = $data;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                $this->push_api($data['cinema_no'], $data['hall_no'], $data['specific_description']);
                $this->alert_info(0, '更新成功');
            }
            $this->alert_info(1, current($model->errors)[0]);
        }
        $this->alert_info('1', '参数错误');

    }

    /**
     * 异步拉取影院信息
     */
    public function actionAjaxPull()
    {
        $model = new CinemaHallFeature;
        $res = $model->get_pull_ids();
        if (empty($res)) {
            $this->alert_info(1, '暂无可更新');
        }
        $connection = Yii::app()->db_opensystem;
        foreach ($res as $val) {
            $sql = 'SELECT * FROM open_base_cinema_hall_feature WHERE `Status`=1 AND `BaseCinemaNo` = :cinema_no AND `HallNo` = :hall_no';
            $command = $connection->createCommand($sql);
            $command->bindParam(":cinema_no", $val['cinema_no']);
            $command->bindParam(":hall_no", $val['hall_no']);
            $base_info = $command->queryRow();
            if (empty($base_info)) {
                continue;
            }
            $model = $this->loadModel($val['id']);
            $model->updated = time();
            $model->cinema_name = $base_info['BaseCinemaName'];
            $model->hall_name = $base_info['HallName'];
            $model->feature_ext = $base_info['FeatureText'];
            $model->feature_type = $base_info['FeatureType'];
            $model->save();
        }
        $this->alert_info(0, '拉取成功');
    }

    /**
     * 输出json
     * @param $code
     * @param string $msg
     * @param array $data
     */
    private function alert_info($code, $msg = '', $data = [])
    {
        echo json_encode(['code' => (int)$code, 'msg' => $msg, 'data' => $data]);
        exit();
    }

    public function push_api($cinemas_id, $halls_id, $des = '')
    {
        if (empty($cinemas_id) || empty($halls_id)) {
            return false;
        }
        $url = Yii::app()->params['comment']['cinema_hall_feature'] . "/$cinemas_id/halls/$halls_id/des";
        $post_date = ['des' => $des];
        Https::getPost($post_date, $url);
    }
}
