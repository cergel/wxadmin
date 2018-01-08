<?php

class ShowCommentReplyController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'getDetail','editStatusType','statusAll'),
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
        $model = new ShowCommentReply;

        if (isset($_POST['ShowCommentReply'])) {
            $model->attributes = $_POST['ShowCommentReply'];
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

        if (isset($_POST['ShowCommentReply'])) {
            $model->attributes = $_POST['ShowCommentReply'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->id));
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
        $model = new ShowCommentReply('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ShowCommentReply']))
            $model->attributes = $_GET['ShowCommentReply'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ShowCommentReply the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ShowCommentReply::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ShowCommentReply $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'show-comment-reply-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetDetail()
    {
        $id = $_POST['id'];
        $commentDetail = ShowComment::model()->findByPk($id);
        if (empty($commentDetail)) {
            $this->json_alert('1', '未找到详情');
        }
        $data = ['typeName' => $commentDetail->type_name,
            'projectName' => $commentDetail->project_name,
            'content' => $commentDetail->content,
            'favorCount' => $commentDetail->favor_count,
            'score' => $commentDetail->score,
        ];
        $this->json_alert(0, '', $data);
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
        if ($model->save()) {
            $model->push($id, 'show-manage-reply', ['channelId' => $model->channelId, 'status' => $model->status_type]);
            $this->json_alert(0, '修改成功');
        }
        $this->json_alert(1, current($model->errors)[0]);
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
