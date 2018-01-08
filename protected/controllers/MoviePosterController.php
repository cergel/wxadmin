<?php
/**
 * 
 * @author liulong
 *
 */
class MoviePosterController extends Controller
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
				'actions'=>array('index','create','update','delete','deleteAll'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * 主库数据列表
	 */
	public function actionIndex()
	{
		$moviePosterModel=new MoviePoster('search');
		$moviePosterModel->unsetAttributes();  // clear any default values
		if(isset($_GET['Movie']))
			$moviePosterModel->attributes=$_GET['MovieData'];
	
		$this->render('index',array(
				'model'=>$moviePosterModel,
		));
	}
	/**
	 * @tutorial 上传、新增（必须基于影片） 可以批量上传图片
	 * @author liulong
	 */
	public function actionCreate($id='')
	{
		if (!MovieData::model()->getOne($id)){
			Yii::app()->user->setFlash('error','不存在的影片信息');
			$this->redirect(array('index'));
		}else {
		$model = new MoviePoster();
		if(isset($_POST['MoviePoster']))
		{
			$error = '';
			$arrUrl = [];
			//判断并且组装图片数组
			if (!empty($_FILES['url']['tmp_name']))
			foreach ($_FILES['url']['tmp_name'] as $key=>$val){
				if (!empty($_FILES['url']['error'][$key]) || empty($_FILES['url']['name'][$key])){
					$error.=!empty($_FILES['url']['name'][$key])?$_FILES['url']['name'][$key].' ':'';
				}else {
					$arrUrl[$key]['name'] = $_FILES['url']['name'][$key];
					$arrUrl[$key]['type'] = $_FILES['url']['type'][$key];
					$arrUrl[$key]['tmp_name'] = $_FILES['url']['tmp_name'][$key];
					$arrUrl[$key]['size'] = $_FILES['url']['size'][$key];
					$arrUrl[$key]['error'] = $_FILES['url']['error'][$key];
				}
			}
			//判断数据
			if (empty($_POST['MoviePoster']['movie_id']) || empty($_POST['MoviePoster']['poster_type']) || !$arrUrl || !is_array($arrUrl)){
				Yii::app()->user->setFlash('error','请将必填项填写完成');
				$this->redirect(array('create','id'=>$id));
			}else {
				$info = $_POST['MoviePoster'];
				$info['status'] = 1;
				$info['source_type'] = 1;
				$info['editer_id'] =Yii::app()->getUser()->getId();;
				$info['edit_time'] =time();
				//遍历图片，移动图片并插入db
				foreach ($arrUrl as $keyImg => $arrImg){
					$extensionName = explode('.', $arrImg['name']);
					$info['url']= date('YmdHis').$keyImg.rand(10000,99999).'.'.$extensionName[count($extensionName)-1];
					if (UploadFiles::moveFile($arrImg['tmp_name'], MoviePoster::model()->getBasePosterPath($info,true),true)){
						$moviePosterTemp = new MoviePosterTemp();
						$moviePosterTemp->setAttributes($info);
						if (!$moviePosterTemp->save()) 
							$error .= $arrImg['name'].' ';
					}else $error .= $arrImg['name'];
				}
				if (!$error){
					Yii::app()->user->setFlash('success','创建成功');
					$this->redirect(array('/movieData/index'));
				}else {
					Yii::app()->user->setFlash('error','部分创建失败,图片名称：'.$error);
					$this->redirect(array('create','id'=>$id));
				}
			}
		} else {
			$model->movie_id = $id;
		}
		$this->render('create',array(
				'model'=>$model
		));
		}
	}
	/**
	 * @tutorial 删除
	 * @param int $id
	 */
	public function actionDelete($id)
	{
		$this->deleteImg($id);
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	/**
	 * @tutorial 批量删除功能
	 * @author liulong
	 */
	public function actionDeleteAll()
	{
		if (isset($_POST['posterId']) && is_array($_POST['posterId']) && !empty($_POST['movie_id'])){
			foreach ($_POST['posterId'] as $key){
				$this->deleteImg($key,$_POST['movie_id']);
			}
		}elseif ($_POST['rootid'] && !empty($_POST['movie_id'])) {
			$this->deleteImg($_POST['rootid'],$_POST['movie_id']);
		}else {
			echo 'error';
		}
		echo "ok";exit;
	}
	/**
	 * @author 删除(现在是直接删除)
	 * @param unknown $id
	 */
	protected function deleteImg($id='',$movie_id='')
	{
// 		$moedl = $this->loadModel($id);
		if (!empty($id) && !empty($movie_id)){
			//获取其裁剪生成的图片
			$info = MoviePoster::model()->getChildPoster($id,$movie_id);
			foreach ($info as $poster){
				//删除图片
				UploadFiles::unlinkFile(MoviePoster::model()->getBasePosterPath($poster,true));
				MoviePoster::model()->findByPk($poster['id'])->delete();
			}
			//删除原图
			UploadFiles::unlinkFile(MoviePoster::model()->getBasePosterPath(MoviePosterTemp::model()->findByPk($id),true));
			MoviePosterTemp::model()->findByPk($id)->delete();
			return TRUE;
		}else return FALSE;
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
