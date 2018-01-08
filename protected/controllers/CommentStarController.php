<?php
class CommentStarController extends Controller
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
				'actions'=>array('index','create','update','delete','deleteAll','statusAll','addMovieName'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * @tutorial 首页：查询页面
	 * @author liulong
	 */
	public function actionIndex()
	{
		$model=new CommentStar('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CommentStar'])){
			$model->attributes=$_GET['CommentStar'];
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}
	/**
	 * @tutorial add
	 * @author liulong
	 */
	public function actionCreate()
	{
		$model= new CommentStar();
		if(isset($_POST['CommentStar']))
		{
			$model->attributes=$_POST['CommentStar'];
			$photoImg=CUploadedFile::getInstance($model,'photo'); //获取表单名为filename的上传信息
			if ($photoImg) {
				$model->photo ='/uploads/comment_star/'.date('YmdHis').rand(1000,9999).'.'.$photoImg->getExtensionName();//获取文件名
			}
			$model->created = $model->updated =time();
			if($model->save()) {
				if($photoImg){
					$photoPath = '/uploads/comment_star/'.$model->id.'_'.date('YmdHis').'.'.$photoImg->getExtensionName();
					$model->photo = $photoPath;
					$targetDir = Yii::app()->params['AppnfsPath']['target_dir'];
					$photoImg->saveAs($targetDir  . $model->photo, true);
					$model->save();
				}
				//标签
				$this->saveCommentTag($model->id);
				$model->saveCache($model->id,1);
				Yii::app()->user->setFlash('success','添加');
				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	/**
	 * @tutorial 更新
	 * @author liulong
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['CommentStar']))
		{
			$photoImg=CUploadedFile::getInstance($model,'photo'); //获取表单名为filename的上传信息
			if ($photoImg) {
				$model->photo ='/uploads/comment_star/'.$model->id.'_'.date('YmdHis').'.'.$photoImg->getExtensionName();//获取文件名
			}
			unset($_POST['CommentStar']['photo']);
			$model->attributes=$_POST['CommentStar'];
			$model->updated =time();
			if($model->save()) {
				if($photoImg){
					$targetDir = Yii::app()->params['AppnfsPath']['target_dir'];
					$photoImg->saveAs($targetDir  . $model->photo, true);
				}
				//标签
				$this->saveCommentTag($model->id);
				$model->saveCache($model->id,1);
				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->id));
			}
		}
		$tag = [];
		if (is_array($model->tag)){
			foreach($model->tag as $result) {
				$tag[] = $result['t_id'];
			}
			$model->tag = $tag;
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}
	/**
	 * @tutorial 删除
	 * @param
	 * @author liulong
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$model->saveCache($model->id,0);
//		$model->delCache($model->id);
		$model->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/**
	 * 处理
	 * @param $id
	 */
	private function saveCommentTag($id)
	{
		$this->delCommentTag($id);
		//插入
		if(isset($_POST['CommentStar']['tag'])){
			$i=0;
			foreach($_POST['CommentStar']['tag'] as $val){
				if($i >= 4){
					break;
				}
				$i++;
				$arrInfo = [];
				$arrInfo['s_id'] = $id;
				$arrInfo['t_id'] = $val;
				$commentStarTagModel=new CommentStarTag();
				$commentStarTagModel->attributes = $arrInfo;
				$commentStarTagModel->save();
			}
		}
		return true;
	}
	/**
	 * 删除
	 * @param $id
	 * @return int
	 */
	private function delCommentTag($id)
	{
		//先删除
		return CommentStarTag::model()->deleteAllByAttributes(array(
			's_id'=>$id
		));
	}




	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Comment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CommentStar::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Comment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
