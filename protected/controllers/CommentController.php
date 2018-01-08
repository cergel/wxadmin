<?php
class CommentController extends Controller
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
				'actions'=>array('index','update','delete','deleteAll','statusAll','addMovieName'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * @tutorial 更新
	 * @author liulong
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if($model->status == 2){
			echo '该条已删除，请不要继续操作';exit;
		}
		$commentTag = CommentTags::model()->getCommentTags($id,true);
		$arrTags = CommentTag::model()->getTagList();
		if(isset($_POST['Comment']))
		{
			if(isset($_POST['Comment']['created']))
				unset($_POST['Comment']['created']);
			$model->attributes=$_POST['Comment'];
			if($model->save()) {
				//这里没判断失败的情况
				$arrData = ['channelId'=>$model->channelId,'commentId'=>$model->id,'fromId'=>'123456789','content'=>$model->content,'baseFavorCount'=>$model->baseFavorCount];
				$arrData = Https::getPost($arrData,Yii::app()->params['comment']['comment_save']);
				$arrData = json_decode($arrData,true);

				$this->saveCommentTag($id,$commentTag);//标签修改

				Yii::app()->user->setFlash('success','更新成功');
				$this->redirect(array('update','id'=>$model->id));
			}
		}
		$model->tag = $commentTag;
		$model->created = date('Ymd H:i:s',$model->created);
		$this->render('update',array(
			'model'=>$model,'tag'=>$arrTags,
		));
	}

	/**
	 * 标签修改
	 * @param $commentId
	 * @param $arrOldTag
	 */
	private function saveCommentTag($commentId,$arrOldTag)
	{
		if(!empty($arrOldTag))
			sort($arrOldTag);
		$arrTag = isset($_POST['Comment']['tag'])?$_POST['Comment']['tag']:[];
		if(!empty($arrTag))
			sort($arrTag);
		if(!empty($arrOldTag) && empty($arrTag)){//原tag存在，新tag不存在
			CommentTags::model()->deleteAllByAttributes(['commentId'=>$commentId]);//删除原标签
			//写入队列
			CommentTag::model()->saveCommentTagList($commentId,4);
		}elseif(!empty($arrTag) && md5(json_encode($arrOldTag)) != md5(json_encode($arrTag)) ){//原tag存在，新tag也存在，但不一样
			CommentTags::model()->deleteAllByAttributes(['commentId'=>$commentId]);//删除原标签
			foreach ($arrTag as $val) {
				$cnc = new CommentTags();
				$cnc->commentId = $commentId;
				$cnc->tagId = $val;
				$cnc->created = time();
				$cnc->save();
			}
			//写入队列
			CommentTag::model()->saveCommentTagList($commentId,4);
		}

	}
	
	/**
	 * @tutorial 批量拉取电影信息来进行更新评论内的电影名称
	 * @todo 其实最好是做个对比，但比较消耗资源，根据宋旎的协商结果，先这样写吧
	 * @author liulong
	 */
	public function actionAddMovieName()
	{
		if ($_POST['type'] == 'addMovieName'){
			$data = Comment::model()->getAllFilmName(10,1,[]);
			$data = Comment::model()->getAllFilmName(10,2,$data);
			foreach ($data as  $key=>$val)
			{
				Comment::model()->updateMovieName($key,$val);
			}
		}
		echo 'ok';exit;
	}

	/**
	 * @tutorial 删除评论：（修改标记为）
	 * @param 
	 * @author liulong
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		if($model->status == 2){
			echo '该条已删除，请不要继续操作';exit;
		}
		$this->loadModel($id)->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/**
	 * @tutorial 批量删除功能
	 * @author liulong
	 */
	public function actionDeleteAll()
	{
		if ($_POST['commentId']){
			foreach ($_POST['commentId'] as $key){
				$model = $this->loadModel($key);
				if($model->status == 2){
					continue;
				}
				$this->loadModel($key)->delete();
			}
		}
		echo "ok";exit;
	}
	/**
	 * @tutorial 批量审核通过
	 * @author liulong
	 */
	public function actionStatusAll()
	{
		if ($_POST['commentId']){
			foreach ($_POST['commentId'] as $key){
				$model = $this->loadModel($key);
				if($model->status == 2){
					continue;
				}
				$this->loadModel($key)->checkstatus();
			}
		}
		echo "ok";exit;
	}

	/**
	 * @tutorial 首页：查询页面
	 * @author liulong
	 */
	public function actionIndex()
	{
		$model=new Comment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Comment'])){
			$model->attributes=$_GET['Comment'];
		}else {
			$model->checkstatus = 0;
		}
		$this->render('index',array(
			'model'=>$model,
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
		$model=Comment::model()->findByPk($id);
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
