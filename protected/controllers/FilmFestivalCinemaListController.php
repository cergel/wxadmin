<?php

class FilmFestivalCinemaListController extends Controller
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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxCreate', 'ajaxDel', 'ajaxGetCinemaInfo'),
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
        $model = FilmFestivalCinemaList::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }


    /**
     * 删除电影节相关影院
     */
    public function actionAjaxDel()
    {
        $id = $_GET['id'];
        $model = $this->loadModel($id);
        $job_id = $model->job_id;
        $res = $model->delete();
        if ($res) {
            if ($job_id) {
                FilmFestivalCinemaList::pushCinemaDel($job_id);
            }
            $status = 200;
            $msg = '删除成功';
        } else {
            $status = 199;
            $msg = '删除失败';
        }
        echo $this->_jsonData($status, $msg);

    }

    /**
     * 添加电影节相关影院
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
        $cinemas = [];
        $cinema_ids = [];
        foreach ($_POST['sort_num'] as $k => $v) {
            if (empty($_POST['cinema_id'][$k])) {
                $status = 191;
                $msg = '缺少必要参数';
                echo $this->_jsonData($status, $msg);
                exit();
            }
            $cinema_id = $_POST['cinema_id'][$k];
            if (in_array($cinema_id,$cinema_ids)) {
                $status = 191;
                $msg = '影厅id' . $cinema_id . '重复';
                echo $this->_jsonData($status, $msg);
                exit();
            }
            $cinema_ids[] = $cinema_id;
            $cinemaInfo = Https::getCinemaInfoByCinemaId($cinema_id);
            if (empty($cinemaInfo)) {
                $status = 192;
                $msg = '影厅id为' . $cinema_id . ',未找到影厅信息';
                echo $this->_jsonData($status, $msg);
                exit();
            }
            $cinemas[$cinema_id] = $cinemaInfo;
        }
        $filmFestivalId = $_POST['film_festival_id'];

        foreach ($_POST['sort_num'] as $key => $val) {
            $model = new FilmFestivalCinemaList();
            $job_id = '';
            $cinema_name = '';
            if (isset($_POST['cinema_list_id'][$key])) {
                $id = $_POST['cinema_list_id'][$key];
                $model = $this->loadModel($id);
                $job_id = $model->job_id;
                $cinema_name = $model->cinema_name;
            }
            //通知延时队列
            $arrData['film_festival_id'] = $filmFestivalId;
            $arrData['cinema_id'] = $_POST['cinema_id'][$key];
            $arrData['sort_num'] = $val;
            $arrData['cinema_name'] = $cinemas[$_POST['cinema_id'][$key]]['name'];
            $arrData['city_id'] = isset($cinemas[$_POST['cinema_id'][$key]]) ? $cinemas[$_POST['cinema_id'][$key]]['city_id'] : 0;
            $arrData['created'] = time();
            $arrData['updated'] = time();
            $model->attributes = $arrData;
//            print_r($arrData);die;
//            echo "<br/>";
            if ($model->save()) {
                //发送通知
                if (empty($cinema_name) || $cinema_name != $cinemas[$_POST['cinema_id'][$key]]['name'] || ($cinema_name == $cinemas[$_POST['cinema_id'][$key]]['name'] && empty($job_id))) {
                    FilmFestivalCinemaList::pushCinemaAdd($job_id, $filmFestivalId, $_POST['cinema_id'][$key]);
                }
                $arrData = [];
                $status = 200;
                $msg = '影院保存成功';
            } else {
                $status = 199;
                $msg = "影院保存失败";
                break;
            }
        }
//        exit();
        echo $this->_jsonData($status, $msg);

    }

    /**
     * 根据影院id获取影院信息 name
     * id 影院id
     * return json
     */
    public function actionAjaxGetCinemaInfo()
    {
        $cinemaId = $_REQUEST['id'];
        $cinemaInfo = Https::getCinemaInfoByCinemaId($cinemaId);
        if ($cinemaInfo) {
            $cinemaName = $cinemaInfo['name'];
            $status = 200;
            $msg = 'success';
            $data = array(
                'name' => $cinemaName
            );
        } else {
            $status = 199;
            $msg = 'failure';
            $data = array();
        }
        echo $this->_jsonData($status, $msg, $data);
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