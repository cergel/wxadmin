<?php

class CommentTagController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete'),
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
        $model = new CommentTag;
        if (isset($_POST['CommentTag'])) {
            $tag_content = preg_replace("/\s(?=\s)/", "\\1", trim($_POST['CommentTag']['tag_content']));
            if (empty($tag_content)) {
                $this->json_alert('1', '映射关键字不能为空');
            }
            $tag_content_array = array_unique(explode(' ', $tag_content));
            sort($tag_content_array);
            $new_tag_content = implode('、', $tag_content_array);
            $model->attributes = $_POST['CommentTag'];
            $model->tag_content = $new_tag_content;
            $model->created = time();
            $model->updated = time();
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '创建成功');
                $model->saveCommentTagList($model->id, 1);
                $this->json_alert(0, '创建成功');
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
        $old_tag_content = $model->tag_content;
        $model->tag_content = str_replace('、', " ", $old_tag_content);
        if (isset($_POST['CommentTag'])) {
            $tag_content = preg_replace("/\s(?=\s)/", "\\1", trim($_POST['CommentTag']['tag_content']));
            if (empty($tag_content)) {
                $this->json_alert('1', '映射关键字不能为空');
            }
            $tag_content_array = array_unique(explode(' ', $tag_content));
            sort($tag_content_array);
            $new_tag_content = implode('、', $tag_content_array);
            if ($old_tag_content == $new_tag_content && $model->tag_name == $_POST['CommentTag']['tag_name'] && $_POST['CommentTag']['comment_type'] == $model->comment_type) {
                $this->json_alert('1', '没有要修改的内容!');
            }
            $model->attributes = $_POST['CommentTag'];
            $model->tag_content = $new_tag_content;
            $model->updated = time();
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                if ($old_tag_content != $new_tag_content) {
                    $model->saveCommentTagList($id, 2);
                }
                $this->json_alert(0, '更新成功');
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
        $this->loadModel($id)->delete();
        $model = new CommentTag;
        $model->saveCommentTagList($id, 3);
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new CommentTag('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CommentTag']))
            $model->attributes = $_GET['CommentTag'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CommentTag the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = CommentTag::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CommentTag $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-tag-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
