<?php

class FulisheResController extends Controller
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
            array(
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index'),  //'users'=>array('@'), // 这个为啥不生效？
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
	public function actionIndex()
	{
        $json = Yii::app()->params['Fulishe']['target_dir'].'/resource.json';
        $arrData = [];
        $arrData['Img'] ='';
        $arrData['localImg'] ='';
        $arrData['color'] ='1aabe1';
        $arrData['status'] =1;
        $arrData['info'] ='';
        if(is_file($json)){
            $json = file_get_contents($json);
            if(preg_match("/(?:\()(.*)(?:\))/i", $json, $arr)){
                $arrData = json_decode($arr[1],1);
            }
        }
		if(isset($_POST['fulishe']))
		{
		    $arrDatas = $_POST['fulishe'];
		    $arrImg = $_FILES['Img'];
		    if (!empty($arrImg['size'])){
		        $name = explode('.',$_FILES['Img']['name']);
		        $name =$name[count($name)-1];
		        $name ="fulishe".rand(10000, 99999).'.'.$name;
		        $uploadDir = Yii::app()->params['Fulishe']['target_dir'] .'/Img';
		        if (!file_exists($uploadDir))
		            mkdir($uploadDir, 0777, true);
		        move_uploaded_file($_FILES['Img']['tmp_name'],$uploadDir.'/'.$name);
		        $arrData['Img'] = Yii::app()->params['Fulishe']['cdn'].'Img/'.$name;
		        $arrData['localImg'] = Yii::app()->params['Fulishe']['local'].'Img/'.$name;
		    }
		    $arrData['color'] =$arrDatas['color'];
		    $arrData['status'] =$arrDatas['status'];
		    $arrData['info'] =$arrDatas['info'];
		    if (!file_exists(dirname(Yii::app()->params['Fulishe']['target_dir'].'/resource.json')))
		        mkdir(dirname(Yii::app()->params['Fulishe']['target_dir'] . '/resource.json'), 0777, true);
		    file_put_contents(Yii::app()->params['Fulishe']['target_dir'].'/resource.json',"callbackresource(".json_encode($arrData).")");
                Yii::app()->user->setFlash('success','修改成功');
		}
		$arrData['Img'] = empty($arrData['Img'])?'':$arrData['Img'];
		if (empty($arrData['localImg']))
		    $arrData['localImg'] = $arrData['Img'];
        $this->render('index', array(
            'model' => $arrData,
        ));

	}
	
}
