<?php
/**
 * 
 * @author liulong
 *
 */
class MoviePosterTempController extends Controller
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
			'postOnly+delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('index','update','delete','deleteAll','updateAll'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 *@tutorial 待审核库数据列表
	 */
	public function actionIndex()
	{
		$moviePosterTempModel=new MoviePosterTemp('search');
		$moviePosterTempModel->unsetAttributes();  // clear any default values
		if(isset($_GET['MoviePosterTemp']))
			$moviePosterTempModel->attributes=$_GET['MoviePosterTemp'];
		else 
			$moviePosterTempModel->status = 1;
		$this->render('index',array(
				'model'=>$moviePosterTempModel,
		));
	}
	/**
	 * @tutorial 删除图片：同时删除原图
	 * @param
	 * @author liulong
	 */
	public function actionDelete($id)
	{
		if ($this->deleteImg($id)){
			$this->loadModel($id)->delete();
		}
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/**
	 * @tutorial 批量删除图片:同时删除原图
	 * @author liulong
	 */
	public function actionDeleteAll()
	{
		if ($_POST['moviePostTemp']){
			foreach ($_POST['moviePostTemp'] as $key){
				if ($this->deleteImg($key)){
					UploadFiles::unlinkFile(MoviePoster::model()->getBasePosterPath($this->loadModel($key),TRUE));
					$this->loadModel($key)->delete();
				}
			}
		}
		echo "ok";exit;
	}
	/**
	 * @tutorial 删除图片:物理删除:已通过审核的不能删
	 * @author liulong
	 */
	private function deleteImg($id)
	{
		$model = $this->loadModel($id);
		return $model->status !='3'? UploadFiles::unlinkFile(MoviePoster::model()->getBasePosterPath($model,TRUE)):false;
	}
	/**
	 * @tutorial 单个审核：：可以修改类型
	 * @param unknown $id
	 * @author liulong
	 */
	public function actionUpdate($id='')
	{
		
		$model = $this->loadModel($id);
		if(isset($_POST['MoviePosterTemp']))
		{
			$imgPath = MoviePoster::model()->getBasePosterPath($this->loadModel($id),TRUE);
			$model->attributes = $_POST['MoviePosterTemp'];
// 			echo MoviePoster::model()->getBasePosterPath($model,true);exit;
			$model->save();
			if (empty($model->movie_id) || !MovieData::model()->getOne($model->movie_id)){
				Yii::app()->user->setFlash('error','movie_id不能为空或movie_id不存在,请先审核影片');
			}else {
// 			if ($_POST['MoviePosterTemp']['poster_type'] != $model->poster_type  || $_POST['MoviePosterTemp']['movie_id'] != $model->movie_id){
// 				//图片转移 原路径
// 				$imgPath = MoviePoster::model()->getBasePosterPath($this->loadModel($id),TRUE);
// 				$model->poster_type = $_POST['MoviePosterTemp']['poster_type'];
// 				$model->movie_id = !empty($_POST['MoviePosterTemp']['movie_id'])?$_POST['MoviePosterTemp']['movie_id']:'';
// 				$model->save();
// 				$model = $this->loadModel($id);
// 				//新图片地址
// 				UploadFiles::moveFile($imgPath, MoviePoster::model()->getBasePosterPath($model,false).'/'.$model->url);
// 			}
// 			MoviePosterTemp::model()->makeImg($id);
				UploadFiles::moveFile($imgPath, MoviePoster::model()->getBasePosterPath($model,true));
				if (MoviePosterTemp::model()->makeImg($id)){
					Yii::app()->user->setFlash('success','审核成功');
				}else {
					Yii::app()->user->setFlash('error','审核失败');
				}
			}
			$this->redirect(array('update','id'=>$model->id));
// 			if($model->save()) {
// 				
// 			}
		}else {
			$model->url = MoviePoster::model()->getLocalPostPath($model,TRUE);
		}
	
		$this->render('update',array(
				'model'=>$model,
		));
	}
	/**
	 * @tutorial 批量审核
	 * @author liulong
	 */
	public function actionUpdateAll()
	{
		$res = "ok";
		if ($_POST['moviePostTemp']){
			foreach ($_POST['moviePostTemp'] as $key){
				$model = $this->loadModel($key);
				if (empty($model->movie_id) || !MovieData::model()->getOne($model->movie_id)){
					$res = "error";
					continue;
				}
				else
					MoviePosterTemp::model()->makeImg($key);
			}
		}
		echo $res;exit;
	}
	
	/**
	 * 实例化对象
	 * @param unknown $id
	 * @throws CHttpException
	 * @return static
	 */
	public function loadModel($id)
	{
		$movieDataModel=MoviePosterTemp::model()->findByPk($id);
		if($movieDataModel===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $movieDataModel;
	}
	########################   以下暂时不用      ###########################


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Movie the loaded model
	 * @throws CHttpException
	 */
	

	/**
	 * Performs the AJAX validation.
	 * @param Movie $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='movie-from')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
