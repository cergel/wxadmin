<?php

class DiscoverymoduleController extends Controller
{
	// Uncomment the following methods and override them if needed
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

	public function actionIndex()
	{
		$model=new DiscoveryModule('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Version']))
			$model->attributes=$_GET['Version'];
		
		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model=new DiscoveryModule;
		if(isset($_POST['DiscoveryModule']))
		{
			//处理换换参数转换映射model的验证规则
			//处理图片上传

			$Pic=CUploadedFile::getInstance($model,'Pic');
			if($_FILES['DiscoveryModule']['error']['Pic'] == 0 && strpos($Pic->getType(),"image")=== 0){
				$f_name = uniqid(date('d_').mt_rand(10,99)).'.'.$Pic->getExtensionName();
				$uploadDir = Yii::app()->params['app_module']['target_dir'].'/';
				if (!file_exists($uploadDir))
					mkdir($uploadDir, 0777, true);
				$Pic->saveAs($uploadDir . '/'.$f_name, true);
				$_POST['DiscoveryModule']['Pic']= $f_name;
			} else {
				$_POST['DiscoveryModule']['Pic']="";
			}


			$model->attributes=$_POST['DiscoveryModule'];
			if($model->validate())
			{
				$model->Created_time = date('Y-m-d H:i:s');
				$model->Updated_time = date('Y-m-d H:i:s');
				if($model->save()){
					Yii::app()->user->setFlash('success','创建成功');
				} else{
					Yii::app()->user->setFlash('error','创建失败');
				}

				$this->redirect(array('index'));
				return;
			}
		}
		$this->render('create',array('model'=>$model));
	}
	
	public function actionDelete($id){
		$model=new DiscoveryModule();
		$model = $model->find('Module_Id=:id', array(':id'=>$id));
		$model->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
	
	
	public function actionUpdate($id)
	{
		$model=new DiscoveryModule();
		$model = $model->find('Module_Id=:id', array(':id'=>$id));
			if(isset($_POST['DiscoveryModule']))
			{
				//处理图片上传

				$Pic=CUploadedFile::getInstance($model,'Pic');
				if($_FILES['DiscoveryModule']['error']['Pic'] == 0 && strpos($Pic->getType(),"image")=== 0){
					$f_name = uniqid(date('d_').mt_rand(10,99)).'.'.$Pic->getExtensionName();
					$uploadDir = Yii::app()->params['app_module']['target_dir'].'/';
					if (!file_exists($uploadDir))
						mkdir($uploadDir, 0777, true);
					$Pic->saveAs($uploadDir . '/'.$f_name, true);
					$_POST['DiscoveryModule']['Pic']= $f_name;
					$tmp_Video_Pic =Yii::app()->params['app_module']['target_dir']."/{$model->Pic}";
				} else {
					$_POST['DiscoveryModule']['Pic']= $model->Pic;
				}
		
				$model->attributes=$_POST['DiscoveryModule'];
				if($model->validate())
				{
					//编辑删除之前的临时图片
					if(isset($tmp_Video_Pic) AND is_file($tmp_Video_Pic))
						unlink($tmp_Video_Pic);
					
					$model->Updated_time = date('Y-m-d H:i:s');
					if($model->save()){
						Yii::app()->user->setFlash('success','修改成功');
					} else{
						Yii::app()->user->setFlash('error','创建失败');
					}
					$this->redirect(array('index'));
					return;
				}
			}
		$this->render('update',array(
			'model'=>$model,
		));
	}
}