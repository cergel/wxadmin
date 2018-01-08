<?php

class VoteController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

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
                'actions' => array('index', 'create', 'update', 'delete'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Vote;
        $sharePlatform = Yii::app()->params['share_platform'];

        if (isset($_POST['Vote'])) {
            
            /*
             * 验证分享图片大小限制
             */
            //var_dump($_POST);die;
            if (!empty($_FILES['Vote']['name']['share_picture'])) {
                $sharePictureSize = $_FILES['Vote']['size']['share_picture'];
                if ($sharePictureSize > 32 * 1000) {
                    $this->_errorTips('create', $model, '图片大小超过限制');
                }
                $_POST['Vote']['share_picture'] = !empty($_FILES['Vote']['name']['share_picture']) ? $_FILES['Vote']['name']['share_picture']:'';
            }
            /*
             * 验证pk图片的大小限制
             */
            if(intval($_POST['Vote']['type']) == 3) {
                $ansPic = $_FILES['Vote']['name']['answer3Picture'];
                foreach($ansPic as $k=>$v){
                    if(empty($v)){
                        $this->_errorTips('create', $model, 'pk图片不可为空');
                    }
                    else{
                       $ansPicSize = $_FILES['Vote']['size']['answer3Picture'][$k];
                       if($ansPicSize > 32*1000){
                           $this->_errorTips('create', $model, 'pk图片大小超过限制');
                       }
                    }
                }
            }
           
            
            $model->attributes = $_POST['Vote'];
            $time = time();
            $model->created = $time;
            $model->updated = $time;
            $model->end_time = strtotime($_POST['Vote']['end_time']);
            $arrPlatForm = isset($_POST['Vote']['share_platform']) ? json_encode($_POST['Vote']['share_platform']) : '[]';
            $model->share_platform = $arrPlatForm;


            $arrAnswer = array(
                'answer'=>array(),
                'picture'=>array(),
            );

            if ($model->save()) {
                //保存图片逻辑
                $arrPicture = $this->saveUploadFile($model->id);
                $model->picture = $arrPicture['picture'];
                $model->share_picture = $arrPicture['share_picture'];
                $model->save();
                switch ($_POST['Vote']['type']) {
                    case 1:
                        $arrAnswer['answer'] = $_POST['Vote']['answer1'];
                        break;
                    case 2:
                        $arrAnswer['answer'] = $_POST['Vote']['answer2'];
                        break;
                    case 3:
                        $arrAnswer['answer'] = $_POST['Vote']['answer3'];
                        $arrAnswer['picture'] = $arrPicture['answer3Picture'];
                        break;
                    default:
                        break;
                }
                /*
                 * 验证投票选项是否为空
                 */
                foreach ($arrAnswer['answer'] as $v) {
                if (empty($v)) {
                    $this->_errorTips('create', $model, '投票选项必填');
                    }else {
                    $len = mb_strlen($v,'utf-8');
                    if ($len > 35) {
                        $this->_errorTips('create', $model, '投票选项超过限制35字符');
                    }
                }
                }
                
                //保存影片
                $arrMovieIdsAndMovies = array();
                $arrMovieIds = array();
                if (!empty($_POST['Vote']['movieIds'])) {
                    $arrMovieIdsAndMovies = $_POST['Vote']['movieIds'];
                    foreach($arrMovieIdsAndMovies as $info){
                        list($movieId,$movieName) = explode('__',$info);
                        $arrMovieIds[] = $movieId;
                        $this->saveMovie($model->id, $movieId, $movieName);
                    }
                }
                //保存投票影片
                $moive_arr = [
                    'id'=>$model->id,
                    'movie_ids'=>$arrMovieIds,
                ];
                $this->request_common_cgi('movie',$moive_arr);
                //保存答案
                $sendAnswerData=$this->saveAnswer($model->id, $arrAnswer);
                //更新缓存
                $sendData=array(
                    'arrVoteData'=>array(
                        'id'=>$model->id,
                        'name'=>$model->name,
                        'endTime'=>$model->end_time,
                        'picture'=>$model->picture,
                        'sharePicture'=>$model->share_picture,
                        'shareTitle'=>$model->share_title,
                        'shareContent'=>$model->share_content,
                        'sharePlatform'=>$model->share_platform,
                        'type'=>$model->type,
                    ),
                    'arrMoviesData'=>$arrMovieIds,
                    'arrAnswerData'=>$sendAnswerData,
                    'channelId'=>3,
                );
                $this->request_common_cgi('add',$sendData);
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('index', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'sharePlatform' => $sharePlatform
        ));
    }

    private function _errorTips($action,$model,$msg){
         Yii::app()->user->setFlash('error',$msg);
         $this->redirect(array($action, 'id' => $model->id));
    }
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $sharePlatform = Yii::app()->params['share_platform'];
        if (isset($_POST['Vote'])) {
            if(!empty($_POST['Vote']['fill'])){
                foreach($_POST['Vote']['fill'] as $v){
                    if($v<0){
                        echo "注水数禁止输入负数!!!";die;
                    }
                }
            }
            if(strtotime($_POST['Vote']['end_time']) <= time() || $model->end_time <= time()) {
                Yii::app()->user->setFlash('error','更新失败!活动已经截止');
                $this->redirect(array('update', 'id' => $model->id));
            }
            $arrPicture = $this->saveUploadFile($model->id);
            
            $model->attributes = $_POST['Vote'];
            $model->updated = time();
            $model->end_time = strtotime($_POST['Vote']['end_time']);
            $arrPlatForm = isset($_POST['Vote']['share_platform']) ? json_encode($_POST['Vote']['share_platform']) : '[]';
            $model->share_platform = $arrPlatForm;
            if (!empty($arrPicture['picture'])) {
                $model->picture = $arrPicture['picture'];
            }
            if (!empty($arrPicture['share_picture'])) {
                $model->share_picture = $arrPicture['share_picture'];
            }
            if ($model->save()) {
                //保存关联影片
                $arrDelMovieIds = $this->delMovie($model->id);

                $arrMovieIdsAndNames = array();
                $arrMovieIds = array();
                if (!empty($_POST['Vote']['movieIds'])) {
                    $arrMovieIdsAndNames = $_POST['Vote']['movieIds'];
                    foreach($arrMovieIdsAndNames as $info){
                        list($movieId,$movieName) = explode('__',$info);
                        $arrMovieIds[] = $movieId;
                        $this->saveMovie($model->id, $movieId, $movieName);
                    }
                }
                //保存投票影片
                $moive_arr = [
                    'id'=>$model->id,
                    'movie_ids'=>$arrMovieIds,
                ];
                $this->request_common_cgi('movie',$moive_arr);

                //保存注水
                $arrAnswerIds = !empty($_POST['Vote']['answerIds']) ? $_POST['Vote']['answerIds'] : [];
                $arrAnswerFill = !empty($_POST['Vote']['fill']) ? $_POST['Vote']['fill'] : [];
                $sendAnswerData = $this->saveAnswerFill($arrAnswerIds, $arrAnswerFill);

                //更新缓存
                $sendData=array(
                    'arrVoteData'=>array(
                        'id'=>$model->id,
                        'name'=>$model->name,
                        'endTime'=>$model->end_time,
                        'picture'=>$model->picture,
                        'sharePicture'=>$model->share_picture,
                        'shareTitle'=>$model->share_title,
                        'shareContent'=>$model->share_content,
                        'sharePlatform'=>$model->share_platform,
                        'type'=>$model->type,
                    ),
                    'arrMoviesData'=>$arrMovieIds,
                    'arrAnswerData'=>$sendAnswerData,
                    'arrDelMovieIds'=>$arrDelMovieIds,
                    'channelId'=>3,
                );
                $this->request_common_cgi('update',$sendData);

                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        //不可修改标识
        $readOnly = time() > $model->end_time ? 1 : 0;
        //把结束时间格式化为日期
        $model->end_time = date("Y-m-d H:i:s", $model->end_time);
        //pk类型的图片展示
        $arrAnswerInfo = $this->getAnswerInfo($model->id);
        //选中的分享平台
        $arrCheckedSharePlatform = json_decode($model->share_platform, 1);
        //关联的影片
        $arrMovies = VoteMovie::model()->findAllByAttributes(array('vote_id' => $model->id));
        $this->render('update', array(
            'model' => $model,
            'sharePlatform' => $sharePlatform,
            'readOnly' => $readOnly,
            'arrAnswerInfo' => $arrAnswerInfo,
            'arrCheckedSharePlatform' => $arrCheckedSharePlatform,
            'arrMovies' => $arrMovies,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $delRe = $this->loadModel($id)->delete();
        if ($delRe) {
            $arrAnswer = array();//删除掉的答案
            $arrMovies = array();//删除掉的关联影片
            $arrAnswerObj = VoteAnswer::model()->findAll("vote_id = :vote_id", array(":vote_id"=>$id));
            if ($arrAnswerObj) {
                foreach ($arrAnswerObj as $obj) {
                    $arrAnswer[] = $obj->id;
                }
            }
            $arrMovieObj = VoteMovie::model()->findAllByAttributes(array("vote_id"=>$id));
            if($arrMovieObj){
                foreach($arrMovieObj as $obj){
                    $arrMovies[] = $obj->movie_id;
                }
            }
            //删除相关答案
            VoteAnswer::model()->deleteAll(" vote_id = :vote_id", array(":vote_id" => $id));
            //删除相关答案的用户
            foreach ($arrAnswer as $answerId) {
                VoteMembers::model()->deleteByPk($answerId);
            }
            //删除相关电影
            VoteMovie::model()->deleteAll("vote_id = :vote_id", array(":vote_id" => $id));

            //清除缓存
            $sendData = array(
                'voteId'=>$id,
                'arrMovieIds'=>$arrMovies,
                'arrAnswerIds'=>$arrAnswer,
            );
            $this->request_common_cgi('del',$sendData);
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new Vote('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Vote']))
            $model->attributes = $_GET['Vote'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Vote the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Vote::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Vote $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'vote-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function uploadFile($voteId,$tmpFileName, $fileName)
    {
        //保存在数据库里的路径，/uploads开头
        $Path = '/uploads/vote/' . $voteId;
        //本地文件路径,上传后要将文件移动到的地方
        $localPath = dirname(Yii::app()->basePath) . $Path;
        if (!is_dir($localPath)) {
            mkdir($localPath, 0777, true);//第三个参数，递归创建
        }
        $extension = pathinfo($fileName);
        $extension = $extension['extension'];
        $fileName = md5(file_get_contents($tmpFileName));
        $fileName = $fileName . '.' . $extension;//文件名:  md5(文件内容).类型
        if (move_uploaded_file($tmpFileName, $localPath . '/' . $fileName)) {
            return $Path . '/' . $fileName;
        } else {
            return false;
        }
    }

    //保存答案
    private function saveAnswer($voteId, $arrAnswer)
    {
        $returnAnswerData = array();
        foreach ($arrAnswer['answer'] as $k => $v) {
            $answerModel = new VoteAnswer();
            $answerModel->vote_id = $voteId;
            $answerModel->answer = $v;
            $answerModel->fill = 0;
            $answerModel->picture = !empty($arrAnswer['picture'][$k]) ? $arrAnswer['picture'][$k] : '';
            $saveRe = $answerModel->save();
            if($saveRe){
                $returnAnswerData[]=array(
                    'id'=>$answerModel->id,
                    'answer'=>$answerModel->answer,
                    'fill'=>0,
                    'picture'=>$answerModel->picture,
                );
            }
        }
        return $returnAnswerData;
    }

    //保存影片
    private function saveMovie($voteId, $arrMovieId, $arrMovieName)
    {
        $voteMovieModel = new VoteMovie();
        $voteMovieModel->vote_id = $voteId;
        $voteMovieModel->movie_id = $arrMovieId;
        $voteMovieModel->movie_name = $arrMovieName;
        return $voteMovieModel->save();
    }

    //清空影片,返回删除掉的影片
    private function delMovie($voteId)
    {
        $return = [];
        $delMovie = VoteMovie::model()->findAll("vote_id = :vote_id", array(":vote_id" => $voteId));
        if (!empty($delMovie)) {
            foreach ($delMovie as $v) {
                $return[] = $v->movie_id;
            }
        }
        VoteMovie::model()->deleteAll("vote_id = :vote_id", array(":vote_id" => $voteId));
        return $return;
    }


    //保存上传的图片
    private function saveUploadFile($voteId)
    {
        
        $return = [
            'picture' => '',
            'share_picture' => '',
            'answer3Picture' => []
        ];
        //保存picture
        if (!empty($_FILES['Vote']['name']['picture'])) {
            $return['picture'] = $this->uploadFile($voteId,$_FILES['Vote']['tmp_name']['picture'], $_FILES['Vote']['name']['picture']);
        }
        //保存share_picture
        if (!empty($_FILES['Vote']['name']['share_picture'])) {
            $return['share_picture'] = $this->uploadFile($voteId,$_FILES['Vote']['tmp_name']['share_picture'], $_FILES['Vote']['name']['share_picture']);
        }
        //保存answer_photo
        if (!empty($_FILES['Vote']['name']['answer3Picture'])) {
            foreach ($_FILES['Vote']['name']['answer3Picture'] as $k => $v) {
                if (!empty($v)) {
                    $return['answer3Picture'][] = $this->uploadFile($voteId,$_FILES['Vote']['tmp_name']['answer3Picture'][$k], $_FILES['Vote']['name']['answer3Picture'][$k]);
                }
            }
        }
        return $return;
    }

    //获取答案信息，包括真实的人数,百分比等等
    protected function getAnswerInfo($voteId)
    {
        $answerInfo = VoteAnswer::model()->findAllByAttributes(array("vote_id" => $voteId));
        foreach ($answerInfo as $k => $v) {
            $answerInfo[$k]->answerRealNum = VoteMembers::model()->count("vote_answer_id=:vote_answer_id", array(":vote_answer_id" => $v->id));
        }
        $allNum = 0;
        $reallyNum = 0;
        foreach ($answerInfo as $v) {
            $allNum += $v->fill;
            $allNum += $v->answerRealNum;
            $reallyNum += $v->answerRealNum;
        }
        foreach ($answerInfo as $k => $v) {
            if ($allNum > 0) {
                $answerInfo[$k]->answerRatio = round(($v->fill + $v->answerRealNum) / $allNum, 2) * 100;
                
            } else {
                $answerInfo[$k]->answerRatio = 0;
            }
            if ($reallyNum > 0) {
                $answerInfo[$k]->answerRatioReally = round(($v->answerRealNum) / $reallyNum, 2) * 100;
            }
            else {
                 $answerInfo[$k]->answerRatioReally = 0;
            }
        }
        //var_dump($answerInfo);die;
        return $answerInfo;
    }

    //保存答案的注水数
    public function saveAnswerFill($arrAnswerIds, $arrAnswerFill)
    {
        $returnData = array();
        if (!empty($arrAnswerFill)) {
            foreach ($arrAnswerIds as $k => $v) {
                $model = VoteAnswer::model()->findByPk($v);
                $model->fill = $arrAnswerFill[$k];
                if($model->save()){
                    $returnData[]=array(
                        'id'=>$model->id,
                        'fill'=>$model->fill,
                    );
                }
            }
        }
        return $returnData;
    }

    //对commoncgi发起请求更新缓存
    protected function request_common_cgi($type,$arrData){
        switch($type){
            case 'add':
                $commonCgiKey = 'vote_add';
                break;
            case 'update':
                $commonCgiKey = 'vote_update';
                break;
            case 'del':
                $commonCgiKey = 'vote_del';
                break;
            case 'movie':
                $commonCgiKey = 'vote_movie';
                break;
            default:
                echo "错误的请求类型";die;
                break;
        }
        $commonCgiUrl = Yii::app()->params['commoncgi'][$commonCgiKey];
        $commonCgiData = $arrData;
        Log::model()->logFile($commonCgiKey,$commonCgiUrl);
        Log::model()->logFile($commonCgiKey,json_encode($commonCgiData));
        $r=Https::getPost($commonCgiData, $commonCgiUrl);
        Log::model()->logFile($commonCgiKey,json_encode($r));
        $r=json_decode($r,true);
        if($r['ret']!=0 || $r['sub']!=0){
            $outData['msg']=$r['msg'];
        }else{
            $outData=[
                'succ'=>1,
                'msg'=>'成功'
            ];
        }
        return json_encode($outData);
    }
}
