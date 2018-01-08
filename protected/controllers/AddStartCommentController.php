<?php
class AddStartCommentController extends Controller
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
				'actions'=>array('index','checkName','addComment'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

    //入口
    public function actionIndex(){
        $this->render('index');
    }

    //检测姓名
    public function actionCheckName(){
        $name = $_GET['name'];
        $ucid = $_GET['openId'];
        $outData=[
            'num'=>0,
            'ucid'=>[]
        ];
        $sql = 'select ucid from t_comment_star where nickName = :startName and ucid = :ucid';
        $connection=Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindParam(":startName",$name);
        $command->bindParam(":ucid",$ucid);
        $re=$command->queryAll();
        $count = count($re);
        if(!empty($count)){
            $outData['num']=$count;
            foreach($re as $v){
                $outData['ucid'][]=$v['ucid'];
            }
        }
        echo json_encode($outData);
    }

    //添加评论
    public function actionAddComment(){
        $outData=[
            'succ'=>0,
            'msg'=>''
        ];
        //判断参数是否完整
        if( !isset($_REQUEST['content']) ||
            !isset($_REQUEST['OpenID']) ||
            !isset($_REQUEST['movieId']) ||
            !isset($_REQUEST['channel'])
        ){
            $outData['msg']="参数不完整";
            echo json_encode($outData);return;
        }
        //影片id
        if($_REQUEST['movieId']<0){
            $outData['msg']="请填写正确影片id";
            echo json_encode($outData);return;
        }
        //判断ucid
        $url = Yii::app()->params['ucenter_api']['user_info'];
        $arrPostData=array("openId"=>$_REQUEST['OpenID'],'content'=>$_REQUEST['content'],'channelId'=>$_REQUEST['channel'],'movieId'=>$_REQUEST['movieId']);
        Log::model()->logFile('add_comment_star',$url);
        Log::model()->logFile('add_comment_star',json_encode($arrPostData));
        $r=Https::getPost($arrPostData, $url,true,true);
        Log::model()->logFile('add_comment_star',json_encode($r));
        $arrResponse = json_decode($r,true);
        if(isset($arrResponse['data'])){
            if($arrResponse['data']['stat']!=2){
                $outData['msg']="此openid不在明星名单中";
                echo json_encode($outData);return;
            }
        }else{
            $outData['msg']="错误的openid";
            echo json_encode($outData);return;
        }
        //发起http请求
        $commonCgiUrl = Yii::app()->params['comment']['add_start_comment'];
        $commonCgiData = array('content'=>$_REQUEST['content'],'openId'=>$_REQUEST['OpenID'],'movieId'=>$_REQUEST['movieId'],'channelId'=>$_REQUEST['channel']);
        Log::model()->logFile('add_comment_star',$commonCgiUrl);
        Log::model()->logFile('add_comment_star',json_encode($commonCgiData));
        $r=Https::getPost($commonCgiData, $commonCgiUrl);
        Log::model()->logFile('add_comment_star',json_encode($r));
        $r=json_decode($r,true);
        if($r['ret']!=0 || $r['sub']!=0){
            $outData['msg']=$r['msg'];
        }else{
            $outData=[
                'succ'=>1,
                'msg'=>'成功'
            ];
        }
        echo json_encode($outData);
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
