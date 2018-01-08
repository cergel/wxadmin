<?php

class ShowCommentController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'showPhone', 'upload', 'editStatusType', 'statusAll'),
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
        $model = new ShowComment;

        if (isset($_POST['ShowComment'])) {
            $model->attributes = $_POST['ShowComment'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->id));
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

        if (isset($_POST['ShowComment'])) {
            if (ShowComment::model()->updateByPk($id, ['content' => $_POST['ShowComment']['content'], 'base_favor_count' => $_POST['ShowComment']['base_favor_count']])) {
                Yii::app()->user->setFlash('success', '更新成功');
                $model->push($id, 'show-edit-comment', ['channelId' => $model->channelId]);
                $this->json_alert(0, '更新成功');
            }
            $this->json_alert(1, current($model->errors)[0]);
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
        $model = new ShowComment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ShowComment']))
            $model->attributes = $_GET['ShowComment'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShowComment the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ShowComment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShowComment $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'show-comment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 修改评论状态
     */
    public function actionEditStatusType()
    {
        $id = $_POST['id'];
        $type = $_POST['type'];
        if (empty($id) || empty($type)) {
            $this->json_alert(1, '参数错误');
        }
        $model = $this->loadModel($id);
        $model->status_type = $type - 1;
        if (ShowComment::model()->updateByPk($id, ['status_type' => $type - 1])) {
            $model->push($id, 'show-manage-comment', ['channelId' => $model->channelId, 'status' => $type - 1]);
            $this->json_alert(0, '修改成功');
        }
        $this->json_alert(1, current($model->errors)[0]);
    }

    /**
     * 获取手机号
     */
    public function actionShowPhone()
    {
        $openId = $_POST['openId'];
        if (!$openId) {
            $this->json_alert('1', 'openId为空');
        }
        $res = UCenter::getUserInfoByUcid($openId);
        if (!$res) {
            $this->json_alert(1, '请求失败');
        }
        $this->json_alert(0, '', $res['mobileNo']);
    }

    public function actionUpload()
    {
        $model = new ShowComment('search');
        $model->unsetAttributes();
        if (isset($_GET['ShowComment'])) {
            $model->attributes = $_GET['ShowComment'];
        }
        $dataProvider = $model->search(5000);
        $data = $dataProvider->getData();
        set_time_limit(0);
        // 获取搜索条件
        header('Content-type:text/csv');
        header('Content-Disposition: attachment;filename="评论_' . date('Y-m-d') . '.csv"');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        $head = array('评论id', '评论内容', '评分', '点赞数', '回复数', '项目名称', '类型', '来源', '更新时间', 'openid', '是否包含敏感词', '审核状态');
        $head_str = implode(',', $head);
        $head_str = mb_convert_encoding($head_str, 'gbk', 'utf-8');
        fwrite($fp, $head_str . PHP_EOL);
        foreach ($data as $value) {
            $channelId = ['' => '全部', '3' => '微信', '8' => 'IOS', '9' => '安卓', '28' => '手q'];
            $line =
                [
                    $value->id,
                    $value->content,
                    $value->score,
                    $value->favor_count,
                    $value->reply_count,
                    $value->project_name,
                    $value->type_name,
                    isset($channelId[$value->channelId]) ? $channelId[$value->channelId] : '其他',
                    date('Y-m-d H:i:s', $value->updated),
                    $value->openId,
                    $value->checkstatus == 1 ? "含有敏感词" : "不含敏感词",
                    $value->status_type == 1 ? "正常" : "待审核",
                ];
            if (!empty($line)) {
                $line_str = implode(',', $line);
                $line_str = mb_convert_encoding($line_str, 'gbk', 'utf-8');
                fwrite($fp, $line_str . PHP_EOL);
            }
        }
        fclose($fp);
        exit();
    }

    /**
     * @tutorial 批量审核通过
     */
    public function actionStatusAll()
    {
        if ($_POST['commentId']) {
            foreach ($_POST['commentId'] as $key) {
                $this->loadModel($key)->status_type($_POST['type']);
            }
        }
        echo "ok";
        exit;
    }

}
