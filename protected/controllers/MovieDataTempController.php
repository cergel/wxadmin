<?php
/**
 * 
 * @author liulong
 *
 */
class MovieDataTempController extends Controller
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
	 * 列表、查询功能
	 * 
	 */
	public function actionIndex()
	{
		$model=new MovieDataTemp('search');
		$model->unsetAttributes();  
		if(isset($_GET['MovieDataTemp']))
			$model->attributes=$_GET['MovieDataTemp'];
		else 
			$model->status = '1';
		$this->render('index',array(
				'model'=>$model,
		));
	}
	/**
	 * @tutorial 编辑影片信息,并且保存到临时库表内
	 * @param unknown $id
	 * 好操蛋的需求啊，以后的维护人，你们倒霉吧，代码很烂，忍者吧
	 * 这个是直接将信息覆盖到主库的，请慎重.
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['MovieDataTemp']))
		{
			$_POST['MovieDataTemp']['tags'] = !empty($_POST['MovieDataTemp']['tags'])?implode('/', $_POST['MovieDataTemp']['tags']):'';
			$_POST['MovieDataTemp']['version'] = !empty($_POST['MovieDataTemp']['version'])?implode('/', $_POST['MovieDataTemp']['version']):'';
			$_POST['MovieDataTemp']['color'] = !empty($_POST['MovieDataTemp']['color'])?implode('/', $_POST['MovieDataTemp']['color']):'';
			$movieId = '';
			if (empty($model->movie_id)){
				$movie = MovieData::model()->getOne();
				$movieId = empty($movie)?'':$movie['movie_id']+=1;
				if (empty($movieId))
					exit('error: The movie_id is null ') ;
			}else 
				$movieId = $model->movie_id;
			$model->attributes = $_POST['MovieDataTemp'];
			//判断数据,看是否进行更新主库
			if ($_POST['MovieDataTemp']['status'] == 3){
				$info = $_POST['MovieDataTemp'];
				$info['movie_id'] = !empty($info['movie_id'])?$info['movie_id']:$movieId;
				$movie = MovieData::model()->getOne($info['movie_id']);
				$info['id'] = $movie?$movie['id']:'';
				$info['first_time'] = strtotime($info['first_show']);
				unset($info['movie_no']);
				if (empty($movieId))
					unset($info['movie_id']);
				else 
					$info['movie_id'] = $movieId;
				if (!empty($info['id'])){
					unset($info['status']);
					$movieDataModel=MovieData::model()->findByPk($info['id']);
					$movieDataModel->attributes = $info;
				}else {
					unset($info['id']);
					$info['status'] = 1;
					$movieDataModel = new MovieData();
					$movieDataModel->setAttributes($info);
				}
				$movieDataModel->save();
				//灵思抓取的单独处理一下
				if ($model->source_type == '2' && !empty($model->movie_no)){
					$arrMovieAllPosterInfo = MoviePosterTemp::model()->getMovieAllPoster($model->movie_no,$model->source_type);
					foreach ($arrMovieAllPosterInfo as $poster){
						$objMovie = MoviePosterTemp::model()->findByPk($poster['id']);
						$objMovie->movie_id = $movieDataModel->movie_id;
						$objMovie->save();
					}
				}
				$model->movie_id = $movieDataModel->movie_id;
				$model->movie_no = $model->movie_no?$model->movie_no:$movieDataModel->id;
			}
			$model->audit_id = Yii::app()->getUser()->getId();
			$model->audit_time = time();
			if($model->save()) {
				Yii::app()->user->setFlash('success','处理成功');
				$this->redirect(array('update','id'=>$model->id));
			}
		}
		$this->render('update',array(
				'model'=>$model,
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
		$movieDataModel=MovieDataTemp::model()->findByPk($id);
		if($movieDataModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $movieDataModel;
	}
	########################   以下暂时不用      ###########################

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
