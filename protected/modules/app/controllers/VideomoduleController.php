<?php

class VideomoduleController extends Controller
{
	public $layout='//layouts/main';


	public function actionIndex(){
		
		$model=new VideoModule('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['VideoModule']))
			$model->attributes=$_GET['VideoModule'];

		$this->render('index',array(
			'model'=>$model,
		));
		
	}
	
	public function actionUpdate($id)
	{
		$model=new VideoModule();
		$model = $model->find('Video_Module_Id=:id', array(':id'=>$id));
			if(isset($_POST['VideoModule']))
			{
				//处理图片上传
				$Pic1=CUploadedFile::getInstance($model,'Video1_Pic');
				if($_FILES['VideoModule']['error']['Video1_Pic'] == 0 && strpos($Pic1->getType(),"image")=== 0){
					$f_name = uniqid(date('d_').mt_rand(10,99)).'.'.$Pic1->getExtensionName();
					$uploadDir = Yii::app()->params['app_module']['target_dir'].'/';
					if (!file_exists($uploadDir))
						mkdir($uploadDir, 0777, true);
					$Pic1->saveAs($uploadDir . '/'.$f_name, true);
					$_POST['VideoModule']['Video1_Pic']=$f_name;
					$tmp_Video1_Pic =Yii::app()->params['app_module']['target_dir']."/{$model->Video1_Pic}";
				} else {
					$_POST['VideoModule']['Video1_Pic']= $model->Video1_Pic;
				}
				
				
				$Pic2=CUploadedFile::getInstance($model,'Video2_Pic');
				if($_FILES['VideoModule']['error']['Video2_Pic'] == 0 && strpos($Pic2->getType(),"image")=== 0){
					$f_name = uniqid(date('d_').mt_rand(10,99)).'.'.$Pic2->getExtensionName();
					$uploadDir = Yii::app()->params['app_module']['target_dir'].'/';
					if (!file_exists($uploadDir))
						mkdir($uploadDir, 0777, true);
					$Pic2->saveAs($uploadDir . '/' . $f_name, true);
					$_POST['VideoModule']['Video2_Pic'] = $f_name;
					$tmp_Video2_Pic =Yii::app()->params['app_module']['target_dir']."/{$model->Video2_Pic}";
				}else{
					$_POST['VideoModule']['Video2_Pic']= $model->Video2_Pic;
				}
				$model->attributes=$_POST['VideoModule'];
				if($model->validate())
				{
					//编辑删除之前的临时图片
					if(isset($tmp_Video1_Pic) AND is_file($tmp_Video1_Pic))
						unlink($tmp_Video1_Pic);
					if(isset($tmp_Video2_Pic) AND is_file($tmp_Video2_Pic))
						unlink($tmp_Video2_Pic);
					
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
	
	public function actionDelete($id){
		$model=new VideoModule();
		$model = $model->find('Video_Module_Id=:id', array(':id'=>$id));
		$model->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
	
	

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
				'actions'=>array('index','create','SearchMoive','update','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate()
	{
		$model=new VideoModule;
		if(isset($_POST['VideoModule']))
		{
			//处理换换参数转换映射model的验证规则
			//处理图片上传
			$Pic1=CUploadedFile::getInstance($model,'Video1_Pic');
			if($_FILES['VideoModule']['error']['Video1_Pic'] == 0 && strpos($Pic1->getType(),"image")=== 0){
				$f_name = uniqid(date('d_').mt_rand(10,99)).'.'.$Pic1->getExtensionName();
				$uploadDir = Yii::app()->params['app_module']['target_dir'].'/';
				if (!file_exists($uploadDir))
					mkdir($uploadDir, 0777, true);
				$Pic1->saveAs($uploadDir . '/'.$f_name, true);
				$_POST['VideoModule']['Video1_Pic']=  $f_name;
			} else {
				$_POST['VideoModule']['Video1_Pic']="";
			}

			$Pic2=CUploadedFile::getInstance($model,'Video2_Pic');
			if($_FILES['VideoModule']['error']['Video2_Pic'] == 0 && strpos($Pic2->getType(),"image")=== 0){
				$f_name = uniqid(date('d_').mt_rand(10,99)).'.'.$Pic2->getExtensionName();
				$uploadDir = Yii::app()->params['app_module']['target_dir'].'/';
				if (!file_exists($uploadDir))
					mkdir($uploadDir, 0777, true);
				$Pic2->saveAs($uploadDir . '/' . $f_name, true);
				$_POST['VideoModule']['Video2_Pic']= $f_name;
			} else {
				$_POST['VideoModule']['Video2_Pic']="";
			}

			$model->attributes=$_POST['VideoModule'];
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

	/**
	 * 调用API获取电影信息以及预告片列表(临时方法)
	 */
	public function actionSearchMoive(){
		$moive_id =(int)$_GET['MoiveId'];
		$arrData = [
			"movieId"=>$moive_id,
			"cityId"=>"10",
			"appkey"=>"10",
			"t"=>time(),
			"v"=>"2016061401",
		];
		ksort($arrData);
		$strKey = urldecode(http_build_query($arrData));
		$arrData['sign'] = strtoupper(md5("jsIa9jL10Vxa9HMlEb9E4Fa15f".$strKey));
		$content = http_build_query($arrData);
		$content_length = strlen($content);
		$options = [
			'http' => [
				'method' => 'POST',
				'header' =>
					"Content-type: application/x-www-form-urlencoded\r\n" .
					"Content-length: $content_length\r\n",
				'content' => $content
			]
		];
		$url = "http://androidcgi.wepiao.com/movie/info";
		$text =  file_get_contents($url, false, stream_context_create($options));
		$DataArray = json_decode($text,true);
		$json_Array = [];
		$json_Array['ret'] = 500;
		if(isset($DataArray['data']) && $DataArray['data']){
			$json_Array['ret'] = 0;
			$json_Array['data'] = $DataArray['data'];
		}
		echo CJSON::encode($json_Array);
		exit;
	}


}