<?php

class FilmFestivalScreeningUnitController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxCreate', 'ajaxDel'),
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
        $model = FilmFestivalScreeningUnit::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }


    /**
     * 删除电影节相关 展映单元
     */
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

    /**
     * 添加电影节相关 展映单元
     */
    public function actionAjaxCreate()
    {

//        print_r($_POST);die;
        if (!$_POST) {
            $status = 190;
            $msg = '添加数据为空';
            $this->_jsonData($status, $msg);
            exit();
        }
        if (!$_POST['sort_num']) {
            $status = 191;
            $msg = '缺少必要参数';
            echo $this->_jsonData($status, $msg);
            exit();
        }
        if (empty($_POST['film_festival_id'])) {
            $status = 192;
            $msg = '缺少电影节id';
            echo $this->_jsonData($status, $msg);
            exit();
        }

        foreach ($_POST['sort_num'] as $k => $v) {
            if (empty($_POST['level1_unit'][$k]) || empty($_POST['movie_id'][$k])) {
                $status = 191;
                $msg = '缺少必要参数';
                echo $this->_jsonData($status, $msg);
                exit();
            }
            //movie_id验证
            $movie_ids = explode(';', $_POST['movie_id'][$k]);
            foreach ($movie_ids as $movieId) {
                if (empty($movieId) || !is_numeric($movieId)) {
                    $status = 191;
                    $msg = '请输入正确的影片ID多个以;分隔';
                    echo $this->_jsonData($status, $msg,['i' => $k, 'name' => 'movie_id']);
                    exit();
                }
                $re = FilmFestival::getMovieInfo($movieId);
                if ($re['code'] != 0) {
                    $status = 191;
                    $msg = '影片ID' . $movieId . '未找到影片信息';
                    echo $this->_jsonData($status, $msg,['i' => $k, 'name' => 'movie_id']);
                    exit();
                }
            }
        }
        $filmFestivalId = $_POST['film_festival_id'];

        foreach ($_POST['sort_num'] as $key => $val) {
            $model = new FilmFestivalScreeningUnit();

            if (isset($_POST['screening_unit_id'][$key])) {
                $id = $_POST['screening_unit_id'][$key];
                $model = $this->loadModel($id);
            }
            $arrData['film_festival_id'] = $filmFestivalId;
            $arrData['level1_unit'] = $_POST['level1_unit'][$key];
            $arrData['sort_num'] = $val;
            $arrData['level2_unit'] = $_POST['level2_unit'][$key];
            $arrData['movie_id'] = $_POST['movie_id'][$key];
            $arrData['created'] = time();
            $arrData['updated'] = time();
            $model->attributes = $arrData;
//            print_r($arrData);die;
//            echo "<br/>";
            if ($model->save()) {
                $arrData = [];
                $status = 200;
                $msg = '展映保存成功';
            } else {
                $status = 199;
                $msg = "展映保存失败";
                break;
            }
        }
        echo $this->_jsonData($status, $msg);

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
}