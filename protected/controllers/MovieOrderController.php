<?php
class MovieOrderController extends Controller
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
				'actions'=>array('index','create','update','delete','getMovieNameByMovieId','checkTime','saveCache'),
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
		$this->isUser();
		$model=new MovieOrder;
		if(isset($_POST['MovieOrder']))
		{
			$model->attributes=$_POST['MovieOrder'];
            $model->created = time();
            $model->updated = time();
            $model->start_time = strtotime($_POST['MovieOrder']['start_time']);
            $model->end_time = strtotime($_POST['MovieOrder']['end_time']);
            $model->status = $_POST['MovieOrder']['status'];
            $model->content = $_POST['MovieOrder']['content'];
			if($model->save()) {
                $model->saveRedis();//将结果保存到redis
				Yii::app()->user->setFlash('success','创建成功');
				$this->redirect(array('update','id'=>$model->id));
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
		$this->isUser();
		$model=$this->loadModel($id);
        $model->start_time = date('Y-m-d H:i:s',$model->start_time);
        $model->end_time = date('Y-m-d H:i:s',$model->end_time);
		if(isset($_POST['MovieOrder']))
		{

			$model->attributes=$_POST['MovieOrder'];
            $model->updated = time();
            $model->start_time = strtotime($_POST['MovieOrder']['start_time']);
            $model->end_time = strtotime($_POST['MovieOrder']['end_time']);
            $model->status = $_POST['MovieOrder']['status'];
            $model->content = $_POST['MovieOrder']['content'];
			if($model->save()) {
                $model->saveRedis();//将结果保存到redis
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->isUser();
	    $model=$this->loadModel($id);
		$this->loadModel($id)->delete();
		$model->saveRedis();//将结果保存到redis
		// if AJAX request (triggered by deletion via admin g rid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->isUser();
		$model=new MovieOrder('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MovieOrder']))
			$model->attributes=$_GET['MovieOrder'];

		$iNum = $model->getValidNum();
		$this->render('index',array(
			'model'=>$model,'num'=>$iNum,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return MovieOrder the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=MovieOrder::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param MovieOrder $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='movie-order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	/**
	 * ajax接口，通过movieId获取影片信息
	 */
    public function actionGetMovieNameByMovieId(){
        $movieId = $_POST['movieId'];
		$url = Yii::app()->params['movie']['getMovieInfo'];
		$sendData=[
			'movieId'=>$movieId,
			'from'=>7000100003,
			'channelId'=>8,
		];
        $strJson = Https::getPost($sendData,$url);
        $obj = json_decode($strJson);
        if($obj->ret==0 && $obj->sub==0){
            if(!empty($obj->data->MovieNameChs)){
                $jsonOut = [
                    'succ'=>1,
                    'msg'=>$obj->data->MovieNameChs,
                ];
            }else{
                $jsonOut = [
                    'succ'=>0,
                    'msg'=>'无此id对应的影片',
                ];
            }
        }else{
            $jsonOut = [
                'succ'=>0,
                'msg'=>'媒资库请求失败请重试',
            ];
        }
        echo json_encode($jsonOut);
    }


	public  function isUser()
	{
		if(!in_array(Yii::app()->getUser()->getId(),[31,1])){
			$this->redirect('/');
		}
	}


	public function actionSaveCache()
	{
		$num = !empty($_POST['num'])?$_POST['num']:10;
		$num = intval($num);
		if(!is_numeric($num)){
			exit("请输入数字");
		}
		$model=new MovieOrder;
		$model->getValidNum($num);
		exit("修改成功");

	}


    /**
	 *
	 * ajax接口
	*/


    public function actionCheckTime(){
        $movieId = $_POST['movieId'];
        $iStartTime = strtotime($_POST['startTime']);
        $iEndTime = strtotime($_POST['endTime']);
        $status = $_POST['status'];
        $pos = $_POST['pos'];
        $modelId = $_POST['modelId'];
        if ($iStartTime > $iEndTime){
            $return = ['succ'=>0,'msg'=>'开始时间不能大于结束时间'];
        }elseif($status==1){

            $tail = '';
            if(!empty($modelId)){
                $tail = " and id!=".$modelId;
            }
            $sql = "select * from t_movie_order where status =$status and pos = $pos AND ((start_time < $iStartTime AND $iStartTime < end_time) OR (start_time < $iEndTime AND $iEndTime < end_time) OR (start_time >= $iStartTime AND $iEndTime >= end_time))" .$tail;
           // echo $sql;exit;
            $res = MovieOrder::model()->findBySql($sql);
            if (empty($res))
                $return = ['succ'=>1,'msg'=>'成功'];
            else $return = ['succ'=>0,'msg'=>'时间有重叠，请检查'];
        }else{
            $return = ['succ'=>1,'msg'=>'成功'];
        }
        echo json_encode($return);
    }
}
