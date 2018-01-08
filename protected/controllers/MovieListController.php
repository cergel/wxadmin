<?php

class MovieListController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            array(// 操作日志过滤器
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
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'create', 'update', 'delete', 'updown', 'ajaxgetmovieinfo', 'ajaxremovemovie'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new MovieList;
        if (isset($_POST['MovieList'])) {
            $arrData = $_POST['MovieList'];

            if (!empty($_FILES['MovieList']['tmp_name']['author_image'])) {
                $tmpAuthorFileName = $_FILES['MovieList']['tmp_name']['author_image'];
                $fileNameAuthor = $_FILES['MovieList']['name']['author_image'];
                $fileSizeAuthor = $_FILES['MovieList']['size']['author_image'];
                if ($fileSizeAuthor > 1024*32) {
                    Yii::app()->user->setFlash('error', '作者头像不能大于32K');
                    $this->redirect(array('create'));
                }
                $urlAuthorImage = $this->uploadFile($tmpAuthorFileName, $fileNameAuthor);
                $arrData['author_image'] = $urlAuthorImage;
            }
            if (!empty($_FILES['MovieList']['tmp_name']['share_image'])) {
                $tmpShareFileName = $_FILES['MovieList']['tmp_name']['share_image'];
                $fileNameShare = $_FILES['MovieList']['name']['share_image'];
                $fileSizeShare = $_FILES['MovieList']['size']['share_image'];
                if ($fileSizeShare > 1024*32) {
                    Yii::app()->user->setFlash('error', '分享图标不能大于32K');
                    $this->redirect(array('create'));
                }
                $urlShareImage = $this->uploadFile($tmpShareFileName, $fileNameShare);
                $arrData['share_image'] = $urlShareImage;
            }
            $arrData['create_time'] = time();
            $arrData['update_time'] = time();
            if (!empty($_POST['MovieList']['online_time'])) {
                $online_time = strtotime($_POST['MovieList']['online_time']);
                if ($online_time <= time()) {
                    Yii::app()->user->setFlash('error', '上线时间不能小于当前时间');
                    $this->redirect(array('create', 'id' => $model->id));
                }
                $arrData['online_time'] = $online_time;
            }
            $arrData['share_platform'] = implode(',', $_POST['MovieList']['share_platform']);
            $model->attributes = $arrData;
            if ($model->save()) {
                if (isset($_POST['movie_id'])) {
                    $this->addRelation($model->id);
                }
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('index'));
            }
        }
        $model->share_platform = array(1, 2, 6, 7);
        $this->render('create', array(
            'model' => $model,
        ));
    }

    private function addRelation($id) {
        Yii::app()->db->createCommand()->delete('t_movie_list_relation', "movie_list_id = $id");
        $movieNum = count($_POST['movie_id']);
//        $movieIdArr = range(30, 888);
//        $movieTitle = range(100,9400,10);
//                $movieDesc = range(100,9400,10);

        $transaction= Yii::app()->db->beginTransaction();//创建事务
        foreach ($_POST['movie_id'] as $key => $val) {
            $movieListRelation = new MovieListRelation();
            $movieListRelation->movie_id = $val;
            $movieListRelation->movie_list_id = $id;
            $movieListRelation->movie_title =  $_POST['movie_title'][$key];
            $movieListRelation->movie_desc = $_POST['movie_desc'][$key];
            $movieListRelation->sort = $key;
            $movieListRelation->save();
            if(($key % 500 == 0 && $key!=0) || $key == ($movieNum-1)) {
                $transaction->commit();//提交事务
                $transaction= Yii::app()->db->beginTransaction();//创建事务

            }
        }
    }
    
    /**
     *
     * @param $tmpFileName
     * @param $fileName
     * @return bool|string
     */
    private function uploadFile($tmpFileName, $fileName)
    {
        //保存在数据库里的路径，/uploads开头
        $path = '/uploads/active/' . date("Ymd") . '/' . date('Hi');
        $extension = pathinfo($fileName);
        $extension = $extension['extension'];
        $fileName = md5(file_get_contents($tmpFileName).rand(1000,9999).time());
        $fileName = $fileName . '.' . $extension;//文件名:  md5(文件内容).类型
        // 上传到腾讯云
        $url = CosUpload::upload($tmpFileName,$path.'/'.$fileName);
        if(!empty($url)){
            return $url;
        }else{
            return false;
        }
    }


    public function actionAjaxGetMovieInfo() {
        $movieId = $_GET['movie_id'];
        $commonCgiUrl = Yii::app()->params['movie']['getMovieName'];
        $url = $commonCgiUrl .$movieId . ".json";
        $data = Https::curlGetPost($url);
        echo $data;
    }
    
    /*
     * 调用垣辛接口删除影片ID
     */
    public function actionAjaxRemoveMovie() {
        $listId = $_GET['listId'];
        $movieId = $_GET['movieId'];
        $commonCgiUrl = Yii::app()->params['film_list']['removeFilmListMovie'];
        $url = $commonCgiUrl . "?channelId=8&listId={$listId}&movieId={$movieId}&skey=l5K4A4nhJhElaJnkOqSryvCq1jJAEhcL";
        $data = Https::curlGetPost($url);
        echo $data;
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
      
        $model = $this->loadModel($id);
        if (isset($_POST['MovieList'])) {
            $arrData = $_POST['MovieList'];
            if (!empty($_FILES['MovieList']['tmp_name']['author_image'])) {
                $tmpAuthorFileName = $_FILES['MovieList']['tmp_name']['author_image'];
                $fileNameAuthor = $_FILES['MovieList']['name']['author_image'];
                $fileSizeAuthor = $_FILES['MovieList']['size']['author_image'];
                if ($fileSizeAuthor > 1024*32) {
                    Yii::app()->user->setFlash('error', '作者头像不能大于32K');
                    $this->redirect(array('update', 'id' => $model->id));
                }
                $urlAuthorImage = $this->uploadFile($tmpAuthorFileName, $fileNameAuthor);
                $arrData['author_image'] = $urlAuthorImage;
            }else{
                $arrData['author_image'] = $model->author_image;
            }
            if (!empty($_FILES['MovieList']['tmp_name']['share_image'])) {
                $tmpShareFileName = $_FILES['MovieList']['tmp_name']['share_image'];
                $fileNameShare = $_FILES['MovieList']['name']['share_image'];
                $fileSizeShare = $_FILES['MovieList']['size']['share_image'];
                if ($fileSizeShare > 1024*32) {
                    Yii::app()->user->setFlash('error', '分享图标不能大于32K');
                    $this->redirect(array('update', 'id' => $model->id));
                }
                $urlShareImage = $this->uploadFile($tmpShareFileName, $fileNameShare);
                $arrData['share_image'] = $urlShareImage;
            }
            else {
                $arrData['share_image'] = $model->share_image;
            }
            $arrData['update_time'] = time();
            $arrData['share_platform'] = implode(',', $_POST['MovieList']['share_platform']);
            if (!empty($_POST['MovieList']['online_time'])) {
                $online_time = strtotime($_POST['MovieList']['online_time']);
                if ($online_time <= time() && $model->state == 0) {
                    Yii::app()->user->setFlash('error', '上线时间不能小于当前时间');
                    $this->redirect(array('update', 'id' => $model->id));
                }
                $arrData['online_time'] = $online_time;
            }
            $model->attributes = $arrData;
            if ($model->save()) {
                if (isset($_POST['movie_id'])) {
                    $this->addRelation($model->id);
                }
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('index'));
            }
        }
        $arrMovies = MovieListRelation::model()->findAllByAttributes(array('movie_list_id' => $model->id));
        $model->share_platform = explode(',', $model->share_platform);
        $model->online_time = empty($model->online_time) ? '' : date('Y-m-d H:i:s', $model->online_time);
        $this->render('update', array(
            'model' => $model,
            'arrMovies' => $arrMovies,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        MovieList::model()->updateByPk($id, array('is_del' => -1));
        $this->delMovieListRelation($id);
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
    
    /**
     * 删除关联影片
     * @param type $id
     */
     private function delMovieListRelation($id)
    {
         MovieListRelation::model()->deleteAllByAttributes(array('movie_list_id' => $id));
    }

    // 修改片单的状态 上线 下线
    public function actionUpdown($id) {
        $model = $this->loadModel($id);
        $status = $model->state;
        $online_time = $model->online_time;
        $now = time();

        if ($status == 0) {
            if ($online_time) {
                Yii::app()->user->setFlash('error', $id . '已经设置定时上线');
                $this->redirect(array('index'));
            } else {
                //上线
                MovieList::model()->updateByPk($id, array('state' => 1, 'online_time' => $now));
                Yii::app()->user->setFlash('success', $id . '上线成功');
                $this->redirect(array('index'));
            }
        } else {
            //下线
            MovieList::model()->updateByPk($id, array('state' => 0,'online_time'=>0));
            Yii::app()->user->setFlash('success', $id . '下线成功');
            $this->redirect(array('index'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new MovieList('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MovieList']))
            $model->attributes = $_GET['MovieList'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return MovieList the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = MovieList::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param MovieList $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'movie-list-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
