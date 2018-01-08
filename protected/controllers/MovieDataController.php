<?php
/**
 * 
 * @author liulong
 *
 */
class MovieDataController extends Controller
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
	 * 主库列表
	 */
	public function actionIndex()
	{
		
		//print_r(Yii::app()->getUser()->getId());exit;
		$movieDataModel=new MovieData('search');
		$movieDataModel->unsetAttributes();  // clear any default values
		if(isset($_GET['Movie']))
			$movieDataModel->attributes=$_GET['MovieData'];
	
		$this->render('index',array(
				'model'=>$movieDataModel,
		));
	}
	/**
	 * @tutorial 编辑影片信息,并且保存到临时库表内
	 * @param unknown $id
	 * 好操蛋的需求啊，以后的维护人，你们倒霉吧，代码很烂，忍者吧
	 */
	public function actionUpdate($id)
	{
		$movieDataModel=$this->loadModel($id);
		if(isset($_POST['MovieData']))
		{
			$_POST['MovieData']['tags'] = !empty($_POST['MovieData']['tags'])?implode('/', $_POST['MovieData']['tags']):'';
			$_POST['MovieData']['version'] = !empty($_POST['MovieData']['version'])?implode('/', $_POST['MovieData']['version']):'';
			$_POST['MovieData']['color'] = !empty($_POST['MovieData']['color'])?implode('/', $_POST['MovieData']['color']):'';
			$model = new MovieDataTemp();
			$info = $_POST['MovieData'];
			//$info['movie_no'] = $info['id'];
			$info['id'] = '';
			unset($info['id']);
			$info['status'] = 1;
			$info['editer_id'] = Yii::app()->getUser()->getId();
			$info['first_time'] = strtotime($info['first_show']);
			$info['first_show'] =  date('Y-m-d',$info['first_time']);
			$info['edit_time'] = time();
			$model->setAttributes($info);
			if($model->save()) {
				Yii::app()->user->setFlash('success','修改成功请等待审核生效');
			}else {
				Yii::app()->user->setFlash('error','修改失败，请重新保存');
			}
			$this->redirect(array('update','id'=>$movieDataModel->id));
		}
		$arrMoviePoster = MoviePoster::model()->getMovieImg($movieDataModel->movie_id);
		$this->render('update',array(
				'model'=>$movieDataModel,
				'moviePoster'=>$arrMoviePoster,
		));
	}
	
	/**
	 * 实例化对象
	 * @param unknown $id
	 * @throws CHttpException
	 * @return static
	 */
	public function loadModel($id)
	{
		$movieDataModel=MovieData::model()->findByPk($id);
		if($movieDataModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $movieDataModel;
	}

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new MovieDataTemp();
        if(isset($_POST['MovieDataTemp']))
        {
        	$_POST['MovieDataTemp']['tags'] = !empty($_POST['MovieDataTemp']['tags'])?implode('/', $_POST['MovieDataTemp']['tags']):'';
        	$_POST['MovieDataTemp']['version'] = !empty($_POST['MovieDataTemp']['version'])?implode('/', $_POST['MovieDataTemp']['version']):'';
        	$_POST['MovieDataTemp']['color'] = !empty($_POST['MovieDataTemp']['color'])?implode('/', $_POST['MovieDataTemp']['color']):'';
        	$info = $_POST['MovieDataTemp'];
        	unset($info['id']);
        	$info['status'] = 1;
        	$info['editer_id'] = Yii::app()->getUser()->getId();
        	$info['first_time'] = strtotime($info['first_show']);
        	$info['first_show'] =  date('Y-m-d',$info['first_time']);
        	$info['edit_time'] = time();
        	$model->setAttributes($info);
            if($model->save()) {
                Yii::app()->user->setFlash('success','创建成功');
                $this->redirect(array('index'));
            }
        }

        $this->render('create',array(
            'model'=>$model
        ));
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
