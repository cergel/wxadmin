<?php

class FilmFestivalController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    use AlertMsg;

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
                'actions' => array('index', 'create', 'update', 'delete', 'ajaxUpload', 'ajaxCheckUrlParam',
                    'ajaxLoadScreeningUnit', 'ajaxLoadCinemaList', 'ajaxLoadMovieList', 'ajaxGetMovieInfo'),
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
        $model = new FilmFestival;
        if (isset($_POST['FilmFestival'])) {
            $is_new = 1;
            if (isset($_POST['FilmFestival']['id']) && $_POST['FilmFestival']['id'] > 0) {
                $model = $model = $this->loadModel($_POST['FilmFestival']['id']);
                $ticket_time = $model->ticket_time;
                $is_new = 0;
            };
            $re = $this->checkUrlParam($_POST['FilmFestival']['url_param'], isset($_POST['FilmFestival']['id']) ? $_POST['FilmFestival']['id'] : null);
            if (!$re) {
                $this->json_alert(1, '该参数已被占用,请确保唯一性');
            }
            $model->attributes = $_POST['FilmFestival'];
            $model->ticket_time = strtotime($_POST['FilmFestival']['ticket_time']);
            $model->start_time = strtotime($_POST['FilmFestival']['start_time']);
            $model->end_time = strtotime($_POST['FilmFestival']['end_time']);
            $model->create_user = Yii::app()->getUser()->getName();
            if ($is_new) {
                $model->created = time();
            }
            $model->updated = time();
            //顶部推荐位
            $top_info = '';
            if ($_POST['FilmFestival']['open_top']) {
                if (!isset($_POST['FilmFestival']['categoryName']) || empty($_POST['FilmFestival']['categoryName'])) {
                    $this->json_alert(1, '请输入分类名!');
                }
                if (empty($_POST['FilmFestival']['top_info'])) {
                    $this->json_alert(1, '请录入顶部推荐详情!');
                }
                $top_info_data = $this->array_Handle($_POST['FilmFestival']['top_info']);
                $top_info_data = $this->array_sort($top_info_data, function ($movieId) {
                    if (!is_numeric($movieId)) {
                        $this->json_alert(1, '未找到ID为' . $movieId . '的电影');
                    }
                    $re = FilmFestival::getMovieInfo($movieId);
                    if ($re['code'] != 0) {
                        $this->json_alert(1, '未找到ID为' . $movieId . '的电影');
                    }
                });
                $top_info['categoryName'] = $_POST['FilmFestival']['categoryName'];
                $top_info['topInfoData'] = $top_info_data;
                $top_info = json_encode($top_info);
            }
            $model->top_info = $top_info;
            //电影节介绍
            $introduce = '';
            if ($_POST['FilmFestival']['open_introduce']) {
                if (empty($_POST['FilmFestival']['introduce'])) {
                    $this->json_alert(1, '请录入电影节介绍!');
                }
                //$_POST['FilmFestival']['introduce']
                $copy = $_POST['FilmFestival']['introduce']['copy'];
                $background = $_POST['FilmFestival']['introduce']['background'];
                $top = $_POST['FilmFestival']['introduce']['top'];
                if (empty($background)) {
                    $this->json_alert(1, '请输入电影节介绍背景色!');
                }
                if (empty($top)) {
                    $this->json_alert(1, '请输入电影节介绍顶部图片!');
                }
                if (empty($copy)) {
                    $this->json_alert(1, '请输入电影节介绍文案编辑!');
                }
                $introduce = json_encode(['copy' => $copy, 'background' => $background, 'top' => $top]);
            }
            $model->introduce = $introduce;
            //电影节日程
            $schedule = '';
            if ($_POST['FilmFestival']['open_schedule']) {
                if (empty($_POST['FilmFestival']['schedule'])) {
                    $this->json_alert(1, '请录入电影节日程!');
                }
                $copy = $_POST['FilmFestival']['schedule']['copy'];
                $background = $_POST['FilmFestival']['schedule']['background'];
                $top = $_POST['FilmFestival']['schedule']['top'];
                if (empty($background)) {
                    $this->json_alert(1, '请输入电影节日程背景色!');
                }
                if (empty($top)) {
                    $this->json_alert(1, '请选择电影节介绍顶部图片!');
                }
                if (empty($copy)) {
                    $this->json_alert(1, '请输入电影节日程文案编辑!');
                }
                $schedule = json_encode(['copy' => $copy, 'background' => $background, 'top' => $top]);
            }
            $model->schedule = $schedule;
            //异常短信
            $SMS = '';
            if ($_POST['FilmFestival']['open_abnormal']) {
                if (empty($_POST['FilmFestival']['SMS'])) {
                    $this->json_alert(1, '请录入短信详情!');
                }
                $SMS = $this->array_Handle($_POST['FilmFestival']['SMS']);
                $data = [];
                foreach ($SMS as $value) {
                    if (empty($value['cinemaId'])) {
                        $this->json_alert(1, '短信推荐影院ID不能为空!');
                    }
                    if (empty($value['copy'])) {
                        $this->json_alert(1, '短信推荐文案不能为空!');
                    }
                    $cinemaInfo = Https::getCinemaInfoByCinemaId($value['cinemaId']);
                    if (empty($cinemaInfo)) {
                        $this->json_alert(1, '短信推荐影厅id' . $value['cinemaId'] . ',未找到影厅信息');
                    }
                    $data[] = $value;
                }
                $SMS = json_encode($data);
            }
            $model->abnormal_SMS = $SMS;
            //静态css
            $this->createStaticFile($_POST['FilmFestival']['visual_color'], $_POST['FilmFestival']['url_param']);
            if ($model->save()) {
                if ($is_new) {
                    Yii::app()->user->setFlash('success', '创建成功');
                    $this->json_alert(0, '创建成功');
                } else {
                    //如果修改购票日期修改通知延时队列
                    if ($ticket_time != strtotime($_POST['FilmFestival']['ticket_time'])) {
                        FilmFestivalCinemaList::pushCinemaByFilmFestivalId($_POST['FilmFestival']['id']);
                    }
                    Yii::app()->user->setFlash('success', '更新成功成功');
                    $this->json_alert(0, '更新成功成功');
                }
            } else {
                $error = $model->getErrors();
                $this->json_alert(1, current(current($error)));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['FilmFestival'])) {
            $model->attributes = $_POST['FilmFestival'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', '更新成功');
                $this->redirect(array('update', 'id' => $model->id));
            }
        }
        $model->ticket_time = date('Y-m-d H:i:s', $model->ticket_time);
        $model->start_time = date('Y-m-d H:i:s', $model->start_time);
        $model->end_time = date('Y-m-d H:i:s', $model->end_time);
        //顶部推荐
        if ($model->open_top) {
            $model->top_info = json_decode($model->top_info);
        }
        //电影节介绍
        if ($model->open_introduce) {
            $model->introduce = json_decode($model->introduce);
        }
        //电影节日程
        if ($model->open_schedule) {
            $model->schedule = json_decode($model->schedule);
        }
        //异常短信
        if ($model->open_abnormal) {
            $model->abnormal_SMS = json_decode($model->abnormal_SMS);
        }
        //根据id获取电影节相关片单列表
        $singleChip = FilmFestivalSingleChip::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        //获取影院相关信息
        $cinemaList = FilmFestivalCinemaList::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        //展映相关信息
        $screeningUnit = FilmFestivalScreeningUnit::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        //影片相关信息
        //$movieList = FilmFestivalMovieList::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        $this->render('update', array(
            'model' => $model,
            'singleChip' => $singleChip,
            'cinemaList' => $cinemaList,
            'screeningUnit' => $screeningUnit,
            'movieList' => [],
        ));
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
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new FilmFestival('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['FilmFestival']))
            $model->attributes = $_GET['FilmFestival'];

        $this->render('index', array(
            'model' => $model,
        ));
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
        $model = FilmFestival::model()->findByPk($id);
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

    /**
     * 文件上传cos
     * @param $tmpFile
     * @return bool|string
     */
    private function upload($tmpFile)
    {
        if (!in_array(strtolower($tmpFile->getExtensionName()), ["gif", "jpeg", "jpg", "bmp", "png"])) {
            Yii::app()->user->setFlash('error', '请确图片文件格式正确');
            return $this->alert_info(1, '请确图片文件格式正确');
        }
        //保存在数据库里的路径，/uploads开头
        $path = '/FilmFestival/uploads/';
        $fileName = date('YmdHis') . rand(1000, 9999) . uniqid() . $tmpFile->getExtensionName();
        // 上传到腾讯云
        $url = CosUpload::upload($tmpFile->tempName, $path . $fileName);
        if (!empty($url)) {
            return $url;
        } else {
            return false;
        }
    }

    /**
     * 数组迭代连接器
     * @param $array
     * @return MultipleIterator
     */
    private function array_Handle($array)
    {
        $keys = array_keys($array);
        $mit = new MultipleIterator(MultipleIterator::MIT_KEYS_ASSOC);
        foreach ($keys as $key) {
            $iterator = new ArrayIterator($array[$key]);
            $mit->attachIterator($iterator, $key);
        }
        return $mit;
    }

    /**
     * 数组快排
     * @param $array
     * @param $func
     * @return array
     */
    private function array_sort($array, Closure $func = null)
    {
        $sort_order = [];
        $data = [];
        foreach ($array as $value) {
            $sort_order[] = $value['sort'];
            if ($func) {
                $func($value['movieId']);
            }
            $data[] = $value;
        }
        array_multisort($sort_order, SORT_ASC, SORT_NUMERIC, $data);
        return $data;
    }

    /**
     * 异步图片上传
     */
    public function actionAjaxUpload()
    {
        $tmpFile = CUploadedFile::getInstanceByName('file');
        if (!in_array(strtolower($tmpFile->getExtensionName()), ["gif", "jpeg", "jpg", "bmp", "png"])) {
            Yii::app()->user->setFlash('error', '请确图片文件格式正确');
            return $this->alert_info(1, '请确图片文件格式正确');
        }
        //保存在数据库里的路径，/uploads开头
        $path = '/FilmFestival/uploads/';
        $fileName = date('YmdHis') . rand(1000, 9999) . uniqid() . '.' . $tmpFile->getExtensionName();
        // 上传到腾讯云
        $url = CosUpload::upload($tmpFile->tempName, $path . $fileName);
        if (!empty($url)) {
            $this->json_alert(0, '上传成功', $url);
        } else {
            $this->json_alert(1, '失败');
        }
    }

    /**
     * 异步获取展映单元
     * @param $id
     */
    public function actionAjaxLoadScreeningUnit($id)
    {
        $screeningUnit = FilmFestivalScreeningUnit::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        $this->renderPartial('screeningUnit', compact('screeningUnit'));
    }

    /**
     * 异步获取影院列表
     * @param $id
     */
    public function actionAjaxLoadCinemaList($id)
    {
        $cinemaList = FilmFestivalCinemaList::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        $this->renderPartial('cinemaList', compact('cinemaList'));
    }

    /**
     * 异步获取影片列表
     * @param $id
     */
    public function actionAjaxLoadMovieList($id)
    {
        $movieList = FilmFestivalMovieList::model()->findAllByAttributes(array('film_festival_id' => $id), array('order' => 'sort_num'));
        $this->renderPartial('movieList', compact('movieList'));
    }

    /**
     *  异步获取影片id
     */
    public function actionAjaxGetMovieInfo()
    {
        if (!isset($_POST['movieId'])) {
            $this->json_alert(1, '参数错误');
        }
        $movieId = $_POST['movieId'];
        if (!is_numeric($movieId)) {
            $this->json_alert(1, '参数错误');
        }
        //$MovieInfo = $this->getMovieInfo($movieId);
        $commonCgiUrl = Yii::app()->params['movie']['getMovieName'];
        $url = $commonCgiUrl . $movieId . ".json";
        $data = Https::curlGetPost($url);
        $res = json_decode($data, true);
        if ($res['ret'] != 0 || empty($res['data']) || !isset($res['data']['name'])) {
            $this->json_alert(1, '未找到');
        }
        $this->json_alert(0, '', ['movieId' => $movieId, 'movieName' => $res['data']['name']]);
    }

    /**
     * 检查参数的唯一性
     * @param $UrlParam
     * @param null $id
     * @return bool
     */
    private function checkUrlParam($UrlParam, $id = null)
    {
        $res = Yii::app()->db->createCommand()
            ->select('id')
            ->from('t_film_festival');
        if (!$id) {
            $res->where('url_param=:url_param', array(':url_param' => $UrlParam));
        } else {
            $res->where('url_param=:url_param AND id<>:id', array(':url_param' => $UrlParam, ':id' => $id));
        }
        $res = $res->queryRow();
        if ($res) {
            return false;
        } else {
            return true;
        }
    }

    public function actionAjaxCheckUrlParam()
    {
        $id = $_POST['id'];
        $UrlParam = $_POST['url_param'];
        if (empty($UrlParam)) {
            echo 'false';
            exit();
        }
        $re = $this->checkUrlParam($UrlParam, $id);
        if ($re) {
            echo 'true';
            exit();
        } else {
            echo 'false';
            exit();
        }
        echo 'false';
        exit();
    }

    /**
     * 生成静态文件
     * @param $mainColor
     * @param $url_param
     */
    private function createStaticFile($mainColor, $url_param)
    {
        $static = " .mainColor{ color: $mainColor!important; }
                    .mainBorderColor{ border-color: $mainColor!important; }
                    .mainBgColor{ background-color: $mainColor!important; }
                    .TabBar .Tabs__item.active .Tabs__link,
                    .Nav .MainNav__Item.current,
                    .Nav .SubNav__Item.current,
                    .mySchedule__Date,
                    .titleBar .more,
                    .Search__bar .cancel,
                    .Search__bar .Text.active:before,
                    .FilterBar__Item.current b,
                    .Modal__footer [class*=\"btn\"]:not(.btnDisabled){ color: $mainColor!important; }
                    
                    .Button:not([disabled]){
                        border-color: $mainColor!important;
                        background: $mainColor!important;
                    }
                    .Button--inverse:not([disabled]){
                        color: $mainColor!important;
                        background-color: transparent!important;
                    }";
        $dir = '/uploads/FilmFestivalStatic/';
        $path = dirname(Yii::app()->BasePath) . $dir;
        if (!is_dir($path)) {
            mkdir($path, '0755', true);
        }
        $filename = $path . $url_param . '.css';
        if (file_exists($filename)) {
            @unlink($filename);
        }
        file_put_contents($filename, $static);
    }
}
