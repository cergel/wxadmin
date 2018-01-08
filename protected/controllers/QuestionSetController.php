<?php
class QuestionSetController extends Controller
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
		$model=new QuestionSet;
		if(isset($_POST['QuestionSet']))
		{
            $err=$this->checkField();
            if($err!==false){
                Yii::app()->user->setFlash('error',$err);
            }else{
                //保存题集信息
                $model = $this->saveQuestionSet($model);
                if($model->save()) {
                    $this->saveQuestionSetQuestions($model->id);
                    Yii::app()->user->setFlash('success','创建成功');
                    $this->redirect(array('update','id'=>$model->id));
                }
            }
		}

		$this->render('create',array(
			'model'=>$model,
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
        $modelQuestions = QuestionSetQuestions::model()->findAllByAttributes(array('qs_id'=>$model->id));
		if(isset($_POST['QuestionSet']))
		{
            $err=$this->checkField();
            if($err!==false){
                Yii::app()->user->setFlash('error',$err);
            }else{
                $this->saveQuestionSet($model);
                if($model->save()) {
                    $this->saveQuestionSetQuestions($model->id);
                    $model->saveFileInfo($model->id);//反向更新
                    Yii::app()->user->setFlash('success','更新成功');
                    $this->redirect(array('update','id'=>$model->id));
                }
            }
		}

		$this->render('update',array(
			'model'=>$model,
            'modelQuestions'=>$modelQuestions
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
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new QuestionSet('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['QuestionSet']))
			$model->attributes=$_GET['QuestionSet'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return QuestionSet the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=QuestionSet::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param QuestionSet $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='question-set-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    //更新题集信息model
    protected function saveQuestionSet($model,$updateTime = ''){
        if(empty($model->pic)){
            $model->pic = '';
        }
        if(!empty($_FILES['uploadPic']['name'])){
            $path = "/uploads/questionSet";
            $model->pic = $this->commonUploadFile($path, $_FILES['uploadPic']['tmp_name'], $_FILES['uploadPic']['name']);
        }
        $model->name = $_POST['QuestionSet']['name'];
        $model->num = $_POST['QuestionSet']['num'];
        $time = time();
        $model->create_time = $time;
        //更新时间的判断
        if(empty($updateTime)){
            $model->update_time = $time;
        }else{
            $model->update_time = $updateTime;
        }
        return $model;
    }

    //插入题集中题目的信息
    protected function saveQuestionSetQuestions($qsId){
        $time = time();
        //先删除以前全部题集中的问题
        QuestionSetQuestions::model()->deleteAllByAttributes(array("qs_id"=>$qsId));

        //再重新插入问题
        foreach($_POST['QuestionSet']['title'] as $key => $title){
            $tmpNumber = $key+1;
            $model = new QuestionSetQuestions();
            $model->qs_id = $qsId;
            $model->title = $title;
            $model->option1 = $_POST['QuestionSet']['question_'.$tmpNumber][0];
            $model->option2 = $_POST['QuestionSet']['question_'.$tmpNumber][1];
            if(!empty($_POST['QuestionSet']['question_'.$tmpNumber][2])){
                $model->option3 = $_POST['QuestionSet']['question_'.$tmpNumber][2];
            }
            $model->true_radio = $_POST['QuestionSet']['radio_'.$tmpNumber];
            $model->create_time = $time;
            $model->update_time = $time;
            $model->save();
        }
    }

    //校验字段信息
    protected function checkField(){
        $return = false;
        if(empty($_POST['QuestionSet']['name'])){
            $return = '请填写题集名称';
        }
        foreach($_POST['QuestionSet']['title'] as $k => $v){
            $tmp = $k+1;
            //检测每个question必须有对应的radio
            if(empty($_POST['QuestionSet']['radio_'.$tmp])){
                return '每个问题必须有一个正确答案';
            }
            //检测标题为必填
            if(empty($_POST['QuestionSet']['title'][$k])){
                return '问题标题为必填';
            }
            //检测前两个选项为必填
            if(empty($_POST['QuestionSet']['question_'.$tmp][0]) || empty($_POST['QuestionSet']['question_'.$tmp][1])){
                return '问题前两个选项为必填';
            }
            //检测radio必填
            if(empty($_POST['QuestionSet']['radio_'.$tmp])){
                return '问题的正确选项为必填';
            }
            //检测第三个问题如果为空，不允许选radio
            if(empty($_POST['QuestionSet']['question_'.$tmp][2])){
                if($_POST['QuestionSet']['radio_'.$tmp]==3){
                    return '请勿将空问题设为正确答案';
                }
            }
        }
        return $return;
    }
}
