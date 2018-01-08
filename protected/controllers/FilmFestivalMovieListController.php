<?php

class FilmFestivalMovieListController extends Controller
{
    use AlertMsg;
    private $movieInfo;

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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxCreate', 'ajaxDel', 'ajaxGetMovieInfo'),
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
        $model = FilmFestivalMovieList::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }


    /**
     * 删除电影节相关影片
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
     * 添加电影节相关影片
     */
    public function actionAjaxCreate()
    {
        $filmFestivalId = $_POST['film_festival_id'];
        $UpdateSql1 = $UpdateSql2 = $UpdateSql3 = $UpdateSql4 = $CreateSql1 = $UpdateSql5 = '';
        $error = 0;
        foreach ($_POST['sort_num'] as $key => $val) {
            //$model = new FilmFestivalMovieList();
            if (isset($_POST['movie_list_id'][$key])) {
                $id = $_POST['movie_list_id'][$key];
                //$model = $this->loadModel($id);
                $UpdateSql1 .= sprintf("WHEN %d THEN '%s' ", $id, (int)$_POST['movie_id'][$key]);
                $UpdateSql2 .= sprintf("WHEN %d THEN '%s' ", $id, (int)$_POST['sort_num'][$key]);
                $UpdateSql3 .= sprintf("WHEN %d THEN '%s' ", $id, addslashes($_POST['row_piece_time'][$key]));
                $UpdateSql4 .= sprintf("WHEN %d THEN '%s' ", $id, trim($_POST['row_piece_time'][$key]) ? 1 : 0);
                $UpdateSql5 .= sprintf("WHEN %d THEN '%s' ", $id, addslashes($_POST['movie_name'][$key]));
            } else {
                $CreateSql1 .= sprintf(" ('%d','%s','%s','%s','%s','%s','%s','%d'),",
                    (int)$filmFestivalId,
                    (int)$_POST['movie_id'][$key],
                    addslashes($val),
                    addslashes($_POST['movie_name'][$key]),
                    addslashes($_POST['row_piece_time'][$key]),
                    time(),
                    time(),
                    trim($_POST['row_piece_time'][$key]) ? 1 : 0);
            }
        }
        if (!empty($CreateSql1)) {
            $CreateSql = 'INSERT INTO `t_film_festival_movie_list` (`film_festival_id`, `movie_id`, `sort_num`, `movie_name`,`row_piece_time`, `created`, `updated`,`status_type`) VALUES ' . trim($CreateSql1, ',');
            try {
                $re = Yii::app()->db->createCommand($CreateSql)->execute();
            } catch (Exception $exception) {
                $error = 1;
            }
        }
        if (!empty($UpdateSql1) && !empty($UpdateSql2) && !empty($UpdateSql3) && !empty($UpdateSql4)) {
            $UpdateSql = "UPDATE t_film_festival_movie_list
                            SET `movie_id`= CASE id
                            $UpdateSql1  
                            END,
                            `sort_num`= CASE id
                            $UpdateSql2
                            END,
                            `movie_name`= CASE id
                            $UpdateSql5
                            END,
                            `row_piece_time`= CASE id
                            $UpdateSql3
                            END,
                            `status_type`= CASE id
                            $UpdateSql4
                            END,
                            updated = " . time() . " 
                            WHERE id IN (" . implode($_POST['movie_list_id'], ',') . ")";
            try {
                $re = Yii::app()->db->createCommand($UpdateSql)->execute();
            } catch (Exception $exception) {
                $error = 1;
            }
        }
        if ($error) {
            $arrData = [];
            $status = 199;
            $msg = '影片保存有误';
        } else {
            $arrData = [];
            $status = 200;
            $msg = '影片保存成功';
        }

        echo $this->_jsonData($status, $msg);
    }

    public function actionAjaxGetMovieInfo()
    {
        $movieId = $_GET['movie_id'];
        $re = FilmFestival::getMovieInfo($movieId);
        $this->json_alert($re['code'], $re['msg'], $re['data']);
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