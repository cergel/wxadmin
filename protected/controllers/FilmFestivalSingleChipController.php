<?php

class FilmFestivalSingleChipController extends Controller
{
    use AlertMsg;
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    private $movieInfo;

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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxCreate', 'ajaxDel', 'ajaxLoadList'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return FilmFestival the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = FilmFestivalSingleChip::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param FilmFestival $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'film-festival-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAjaxDel()
    {
        $id = $_GET['id'];
        $res = $this->loadModel($id)->delete();
        if ($res) {
            $status = 200;
            $msg = '删除成功';
        } else {
            $status = 199;
            $msg = '删除失败';
        }
        echo $this->_jsonData($status, $msg);

    }

    public function actionAjaxCreate()
    {
        //参数验证
        if (!isset($_POST['sort_num'])) {
            $status = 190;
            $msg = '没有添加任何数据';
            echo $this->_jsonData($status, $msg);
            exit;
        }
        if (!($_POST['sort_num'])) {
            $status = 197;
            $msg = '参数不能为空';
            echo $this->_jsonData($status, $msg);
            exit();
        }
        if (!$_POST['film_festival_id']) {
            $status = 198;
            $msg = '请先添加电影节信息';
            echo $this->_jsonData($status, $msg);
            exit;
        }

        $sort = $_POST['sort_num'];
        $filmFestivalId = $_POST['film_festival_id'];
        $model = new FilmFestivalSingleChip();
        $status = 200;
        for ($i = 0; $i < count($sort); $i++) {
            if (empty($_POST['sort_num'][$i])) {
                $status = 196;
                $msg = '请补全' . $i + 1 . '片单的排序';
                echo $this->_jsonData($status, $msg, ['i' => $i, 'name' => 'sort_num']);
                exit();
            }
            if (empty($_POST['title'][$i])) {
                $status = 196;
                $msg = '请补全' . ($i + 1) . '片单的标题';
                echo $this->_jsonData($status, $msg, ['i' => $i, 'name' => 'title']);
                exit();
            }
            if (empty($_POST['author_name'][$i])) {
                $status = 196;
                $msg = '请补全' . ($i + 1) . '片单的作者名';
                echo $this->_jsonData($status, $msg, ['i' => $i, 'name' => 'author_name']);
                exit();
            }
            if (empty($_POST['brief'][$i])) {
                $status = 196;
                $msg = '请补全' . ($i + 1) . '片单的描述';
                echo $this->_jsonData($status, $msg, ['i' => $i, 'name' => 'brief']);
                exit();
            }
            if (empty($_POST['movie_id'][$i])) {
                $status = 196;
                $msg = '请补全' . ($i + 1) . '片单的影片ID';
                echo $this->_jsonData($status, $msg, ['i' => $i, 'name' => 'movie_id']);
                exit();
            }
            //影片id验证
            $movie_ids = explode(';', $_POST['movie_id'][$i]);
            foreach ($movie_ids as $movie_id) {
                if (FilmFestival::getMovieInfo($movie_id)['code'] == 0)
                    continue;
                $status = 196;
                $msg = '未找到影片ID为' . $movie_id . '影片';
                echo $this->_jsonData($status, $msg, ['i' => $i, 'name' => 'movie_id']);
                exit();
            }
            if (empty($_POST['movie_id'][$i])) {
                $status = 196;
                $msg = '请补全' . ($i + 1) . '片单的影片ID';
                echo $this->_jsonData($status, $msg, ['i' => $i, 'name' => 'movie_id']);
                exit();
            }
            if (!isset($_POST['single_chip_id'][$i]) && empty($_POST['cover_map'][$i])) {
                $status = 188;
                $msg = '请补全' . ($i + 1) . '片单的封面图片';
                echo $this->_jsonData($status, $msg);
                exit();
            }
            if (!isset($_POST['single_chip_id'][$i]) && empty($_POST['author_portrait'][$i])) {
                $status = 187;
                $msg = '请补全' . ($i + 1) . '片单的作者头像';
                echo $this->_jsonData($status, $msg);
                exit();
            }
        }

        $transaction = Yii::app()->db->beginTransaction();//创建事务
        for ($i = 0; $i < count($sort); $i++) {
            $model = new FilmFestivalSingleChip();
            // 片单编辑
            if (isset($_POST['single_chip_id'][$i])) {
                $id = $_POST['single_chip_id'][$i];
                $model = $this->loadModel($id);
                $arrData['cover_map'] = $model->cover_map;
                $arrData['author_portrait'] = $model->author_portrait;
            } else {
                $arrData['created'] = time();
            }
//                die;
            // 后续可添加验证图片大小 格式是否符合要求
            $arrData['film_festival_id'] = $filmFestivalId;
            $arrData['movie_id'] = $_POST['movie_id'][$i];
            $arrData['sort_num'] = $_POST['sort_num'][$i];
            $arrData['title'] = $_POST['title'][$i];
            $arrData['author_name'] = $_POST['author_name'][$i];
            $arrData['brief'] = $_POST['brief'][$i];
            $arrData['cover_map'] = $_POST['cover_map'][$i];
            $arrData['author_portrait'] = $_POST['author_portrait'][$i];
            $arrData['updated'] = time();
            $model->attributes = $arrData;
            if ($model->save()) {
                $arrData = [];
                $msg = '片单保存成功';
            } else {
                $status = 199;
                $msg = "片单保存失败";
                break;
            }
        }
        $transaction->commit();//提交事务

        echo $this->_jsonData($status, $msg);
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
        $fileName = md5(file_get_contents($tmpFileName) . rand(1000, 9999) . time());
        $fileName = $fileName . '.' . $extension;//文件名:  md5(文件内容).类型
        // 上传到腾讯云
        $url = CosUpload::upload($tmpFileName, $path . '/' . $fileName);
        if (!empty($url)) {
            return $url;
        } else {
            return false;
        }
    }

    /**
     * @param $status
     * @param $msg
     * @param string $data
     * @return string
     */
    private function _jsonData($status, $msg, $data = '')
    {
        $arr = array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        );
        return json_encode($arr);
    }

    //异步刷新
    public function actionAjaxLoadList($id)
    {
        $singleChip = FilmFestivalSingleChip::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        $this->renderPartial('singleChip', compact('singleChip'));
    }
}
