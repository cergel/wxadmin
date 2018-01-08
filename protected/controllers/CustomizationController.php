<?php

class CustomizationController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/main';

	public function actionIndex()
	{
        $data = array();
        $model = new CustomizationSeat;
        $data['items'] = $model->findAll();
		$this->render('index',$data);
	}


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
                'actions'=>array('index','create','update','delete','upload'),
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
        $model = new CustomizationSeat;
        if(isset($_POST['Post']))
        {
            $Data = $_POST['Post'];
            $model=new CustomizationSeat();
            $check_exist = $model->find('MovieId=:id', array(':id'=>$Data['MovieId']));
            if(!empty($check_exist)) {
                $OutPut = [];
                $OutPut['status'] = false;
                $OutPut['msg'] = "创建失败,电影的定制化选坐方案已经存在!";
                echo CJSON::encode($OutPut);
                exit;
            }

            $model->MovieId = $Data['MovieId'];
            $model->Created_time = date("Y-m-d H:i:s");
            $model->Updated_time = date("Y-m-d H:i:s");
            $model->Start = $Data['Start'];
            $model->End = $Data['End'];
            $model->Config = $Data['Config'];
            $model->Status = 1;
            if($model->save()) {
                $OutPut = [];
                $OutPut['status'] = true;
                $OutPut['msg'] = "创建成功!";
                echo json_encode($OutPut);
                exit;
            }
        } else {
            $this->render('create',array(
                'model'=>$model
            ));
        }
    }

    public function actionUpdate($id){
        $model=new CustomizationSeat();
        $model = $model->find('SeatId=:id', array(':id'=>$id));
        if( isset( $_POST['Post'] ) )
        {
            $Data = $_POST['Post'];
            $model->MovieId = $Data['MovieId'];
            $model->Updated_time = date("Y-m-d H:i:s");
            $model->Start = $Data['Start'];
            $model->End = $Data['End'];
            $model->Config = $Data['Config'];

            if($model->save()) {
                $OutPut = [];
                $OutPut['status'] = true;
                $OutPut['msg'] = "创建成功!";
                echo CJSON::encode($OutPut);
                exit;
            }
        } else {
            $this->render('update',array(
                'model'=>$model
            ));
        }
    }

    public function actionDelete(){
        if(isset($_POST['Post']))
        {
            $Data = $_POST['Post'];
            $model=new CustomizationSeat();
            $model = $model->find('SeatId=:id', array(':id'=>$Data['SeatId']));
            $model->delete();
            $OutPut = [];
            $OutPut['status'] = true;
            $OutPut['msg'] = "删除成功!";
            echo CJSON::encode($OutPut);
            exit;
        }
    }



}