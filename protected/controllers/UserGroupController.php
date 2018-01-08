<?php

class UserGroupController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'modulesList'),
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
        $model = new UserGroup;

        if (isset($_POST['UserGroup'])) {
            $authList = $_POST['UserGroup']['authList'];
            if (empty($authList)) {
                $this->json_alert(1, '请选择授权列表');
            }
            $model->attributes = $_POST['UserGroup'];
            $model->created = time();
            $model->authList = addslashes($authList);
            $model->updated = time();
            $model->createUser = Yii::app()->getUser()->getId();
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '创建成功');
                $this->json_alert(0, '创建成功');
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
        $model->authList = stripslashes($model->authList);
        if (isset($_POST['UserGroup'])) {
            $model->attributes = $_POST['UserGroup'];
            $model->authList = addslashes($_POST['UserGroup']['authList']);
            $model->updated = time();
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
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

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new UserGroup('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserGroup']))
            $model->attributes = $_GET['UserGroup'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return UserGroup the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = UserGroup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param UserGroup $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-group-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionModulesList()
    {
        $config = require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'homePageModular.php');
        $treeMap = $config['treeMap'];
        $MapDetail = $config['detail'];
        $authList = isset($_POST['authList']) && !empty($_POST['authList']) ? $_POST['authList'] : [];
        $html = '';
        foreach ($treeMap as $key => $map) {
            if (!isset($MapDetail[$key])) {
                continue;
            } else {
                $detail = $MapDetail[$key];
            }
            $ModuleName = $detail['name'];
            $ModuleId = $key == '/' ? 'quanzhan' : $key;
            $html .= '<div class="panel-group">
                        <label>
                            <h4 class="font-bold">
                                <input id="' . $ModuleId . '" onchange="MainChange(this)" type="checkbox" modular="' . $ModuleId . '" value="' . $key . '">&nbsp;&nbsp;' . $ModuleName . '
                            </h4>
                        </label>
                        <div id="modular' . $ModuleId . '">';
            $arrayiter = new RecursiveArrayIterator($map);
            $iteriter = new RecursiveIteratorIterator($arrayiter);
            foreach ($iteriter as $value) {
                if (!isset($MapDetail[$value])) {
                    continue;
                } else {
                    $valueDetail = $MapDetail[$value];
                }
                $url = isset($valueDetail['urlC']) && !empty($valueDetail['urlC']) ? $valueDetail['urlC'] : $this->createUrl($valueDetail['url']);
                $isChecked = in_array($value, $authList) ? 'Checked="true"' : '';
                $html .= '<label>
                            <input ' . $isChecked . ' name="keyMap[]" type="checkbox" value="' . $value . '" onchange="ModularChange(this)">
                            <a type="button" class="btn btn-w-m btn-link">' . $valueDetail['name'] . '</a>
                          </label>';
            }
            $html .= '</div></div>';
        }
        echo $html;
    }
}
