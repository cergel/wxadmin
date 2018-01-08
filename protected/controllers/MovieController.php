<?php
class MovieController extends Controller
{
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
				'actions'=>array('index','create','update','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Movie;
        if(isset($_POST['Movie']))
        {
            $model->attributes = $_POST['Movie'];
            $commentModel = new Comment();
            $movie = $commentModel->getFilmName($model->id,'array');
            $model->movieName =$movie['name'];
            $model->movieDate = $movie['date'];
            if($model->save()) {
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('index'));
            }
        } else {
            // default value
            $model->baseScore = 80;
            $model->baseScoreCount = 100;
        }

        $this->render('create',array(
            'model'=>$model
        ));
    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Movie']))
		{
			$model->attributes=$_POST['Movie'];
			$commentModel = new Comment();
			$movie = $commentModel->getFilmName($model->id,'array');
            $model->movieName =$movie['name'];
            $model->movieDate = $movie['date'];
			if($model->save()) {
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Movie('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Movie']))
			$model->attributes=$_GET['Movie'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Movie the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Movie::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Movie $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='movie-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
