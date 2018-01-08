<?php

class SearchRecController extends Controller
{
    use AlertMsg;
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';
    private $rec_data = [1 => '综合搜索-电影搜索', 2 => '综合搜索-影院搜索', 3 => '综合搜索-影人搜索'];
    private $rec_name = [1 => 'movie', 2 => 'cinema', 3 => 'actor'];
    private $per_num = 100;

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
                'actions' => array('saveThesaurus', 'index', 'create', 'update', 'delete', 'info', 'thesaurus', 'export', 'csvUpload', 'editInfo'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 创建单个词库
     */
    public function actionCreate()
    {
        $ThesaurusTag = $_POST['ThesaurusTag'];
        $ThesaurusName = $_POST['ThesaurusName'];
        $ThesaurusId = $_POST['ThesaurusId'];
        $id = $_POST['Id'];
        $data = $this->rec_data;
        if (!in_array($id, array_keys($data)) || empty($ThesaurusName)) {
            $this->json_alert(1, '参数错误');
        }
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        if ($ThesaurusId > 0) {
            $SearchRecRe = $SearchRecObj->getOne($id, $ThesaurusId);
            $score = isset($SearchRecRe['score']) ? $SearchRecRe['score'] : 0;
        } else {
            $score = 0;
        }
        $re = $SearchRecObj->addOne($id, $ThesaurusName, $ThesaurusTag, $score, $ThesaurusId);
        $this->json_alert(0, '操作成功', ['counterId' => $re['counterId']]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
    }

    public function actionDelete()
    {
        $ThesaurusId = $_POST['ThesaurusId'];
        $id = $_POST['Id'];
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        $data = $this->rec_data;
        if (!in_array($id, array_keys($data)) || empty($ThesaurusId)) {
            $this->json_alert(1, '参数错误请刷新重试');
        }
        $SearchRecObj->delOne($id, $ThesaurusId);
        $this->json_alert(0, '删除成功');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $data = $this->rec_data;
        $this->render('index', array('data' => $data));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SearchRec the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = SearchRec::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SearchRec $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'SearchRec-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 推荐词设置(显示页)
     * @param $id
     * @throws CHttpException
     */
    public function actionInfo($id)
    {
        $data = $this->rec_data;
        if (!in_array($id, array_keys($data)))
            throw new CHttpException(404, ' Did not find this ID ');
        $data_name = $this->rec_name;
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        $rec_data = $SearchRecObj->getStr();
        $rec_data = $rec_data[$data_name[$id]] ? $rec_data[$data_name[$id]] : [];
        $this->render('info', ['id' => $id, 'title' => $data[$id], 'data' => $rec_data]);
    }

    /**
     * 词库设置(显示)
     * @param $id
     * @throws CHttpException
     */
    public function actionThesaurus($id)
    {
        //获取当前词库的列表
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_num = $this->per_num;
        $total = $SearchRecObj->getTotal($id);
        $paginate = ['page' => $page, 'total' => $total, 'perPage' => $per_num];
        $offset = ($page - 1) * $per_num;
        $limit = [$offset, $per_num];
        //清空全部
        //$SearchRecList = $SearchRecObj->flushAllRec($id);
        $SearchRecList = $SearchRecObj->getList($id, $limit);
        $data = $this->rec_data;
        if (!in_array($id, array_keys($data)))
            throw new CHttpException(404, ' Did not find this ID ');
        $this->render('thesaurus', ['paginate' => $paginate, 'id' => $id, 'SearchRecList' => $SearchRecList, 'title' => $data[$id]]);
    }

    /**
     * 批量导入词库
     */
    public function actionCsvUpload()
    {
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        $id = $_POST['id'];
        $data = $this->rec_data;
        if (!$id || !in_array($id, array_keys($data))) {
            $this->json_alert('1', '未找到该综合搜索条目');
        }
        if (empty($_FILES['UploadCsv'])) {
            $this->json_alert('1', '请选择上传文件');
        }
        //上传csv
        $upload_csv = CUploadedFile::getInstanceByName('UploadCsv');
        $upload_re = $this->upload($upload_csv);
        $csv_file = $upload_re['data']['path_file_name'];
        //清空当前列表
        $SearchRecObj->flushAllRec($id);
        //spl迭代器(插入集合)
        $spl_object = new SplFileObject($csv_file, 'rb');
        $spl_object->seek(filesize($csv_file));
        $num = $i = $spl_object->key();
        foreach ($spl_object as $val) {
            if (empty($val)) {
                continue;
            }
            if ($i == $num) {
                $i--;
                continue;
            }
            $arr = str_getcsv(mb_convert_encoding($val, 'utf-8', 'gbk'));
            $field = $arr[1];
            $data = $arr[0];
            $SearchRecObj->addOne($id, $field, $data, $i);
            $i--;
        }
        $this->json_alert('0', '上传成功');
    }

    /**
     * 上传文件处理
     * @param $csv
     * @return array
     */
    private function upload($csv)
    {
        if (!in_array(strtolower($csv->getExtensionName()), ['csv'])) {
            return $this->alert_info('1', '请保证上传文件格式正确');
        }
        $dir = '/uploads/SearchRec/';
        $path = dirname(Yii::app()->BasePath) . $dir;
        if (!is_dir($path)) {
            mkdir($path, '0755', true);
        }
        $file_name = date('YmdHis') . rand(1000, 9999) . '.' . $csv->getExtensionName();
        $re = $csv->saveAs($path . $file_name, true);
        return $this->alert_info('0', '上传成功', ['file_name' => $file_name, 'path_name' => $path, 'path_file_name' => $path . $file_name]);
    }

    /**
     *  导出Csv
     * @param $id
     * @throws CHttpException
     */
    public function actionExport($id)
    {
        $data = $this->rec_data;
        if (!in_array($id, array_keys($data)))
            throw new CHttpException(404, ' Did not find this ID ');
        set_time_limit(0);
        // 获取搜索条件
        header('Content-type:text/csv');
        header('Content-Disposition: attachment;filename="词库_' . date('Y-m-d') . '.csv"');
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        $head = array('词库', '推荐词');
        $head_str = implode(',', $head);
        $head_str = mb_convert_encoding($head_str, 'gbk', 'utf-8');
        // 将数据通过fwrite写到文件句柄
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        $SearchRecList = $SearchRecObj->getList($id);
        fwrite($fp, $head_str . PHP_EOL);
        foreach ($SearchRecList as $value) {
            $line = [];
            $data = json_decode($value['data'], true);
            $line[] = isset($data['data']) ? implode($data['data'], '|') : '';
            $line[] = isset($data['name']) ? $data['name'] : '';
            if (!empty($line)) {
                $line_str = implode(',', $line);
                $line_str = mb_convert_encoding($line_str, 'gbk', 'utf-8');
                fwrite($fp, $line_str . PHP_EOL);
            }
        }
        fclose($fp);
        exit();
    }

    /**
     * 设置排序
     */
    public function actionEditInfo()
    {
        $num = $_POST['num'];
        $name = $_POST['name'];
        $id = $_POST['id'];
        if (!is_numeric($num) || ($num <= 0 || $num > 10)) {
            $this->json_alert(1, '请输入1到10的正整数');
        }
        if (empty($name) || empty($id)) {
            $this->json_alert(1, '参数错误');
        }
        //设置排序
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        $SearchRecObj->SetMainIndex($id, $name, $num);
        //获取最新排序
        $data_name = $this->rec_name;
        $rec_data = $SearchRecObj->getMainIndex();
        $rec_data = $rec_data[$data_name[$id]];
        $SearchRecObj->saveStr($id, array_map(function ($val) {
            return $val['name'];
        }, $rec_data));
        $html = '';
        foreach ($rec_data as $val) {
            $html .=
                '<div class="form-group">
                        <div class="col-sm-5"> 
                            <input type="type" class="form-control" value="' . $val['name'] . '" readonly="readonly"> 
                        </div> 
                        <div class="col-sm-2"> 
                            <input onchange="scoreChange(this)" type="type" class="form-control score" autocomplete="off" info="' . $val['name'] . '" value="' . $val['score'] . '"/>
                        </div>
                    </div>';
        }
        $this->json_alert(0, '修改成功', $html);
    }

    /***
     * 批量更新
     * @param $id
     */
    public function actionSaveThesaurus($id)
    {
        $data = $this->rec_data;
        if (!in_array($id, array_keys($data))) {
            $this->json_alert(1, '参数错误');
        }
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        $ThesaurusIds = $_POST['ThesaurusId'];
        $ThesaurusTags = $_POST['ThesaurusTag'];
        $ThesaurusNames = $_POST['ThesaurusName'];
        $ThesaurusDelete = $_POST['ThesaurusDelete'];
        $ThesaurusPage = (int)$_POST['ThesaurusPage'];
        //数据过滤
        $data = [];
        //插入/更新
        $Total = $SearchRecObj->getTotal($id);
        $per_num = $this->per_num;
        $num = $Total - ($ThesaurusPage - 1) * $per_num;
        if ($num > 0) {
            foreach ($ThesaurusNames as $key => $ThesaurusName) {
                $ThesaurusName = trim($ThesaurusName);
                $ThesaurusTag = trim($ThesaurusTags[$key]);
                $ThesaurusId = trim($ThesaurusIds[$key]);
                if (empty($ThesaurusName) || empty($ThesaurusTag)) {
                    $this->json_alert(1, '请填写完整所有的输入框');
                }
                $re = $SearchRecObj->inspectRepeat($id, $ThesaurusName);
                if ($re && $re != $ThesaurusId) {
                    continue;
                }
                $re = $SearchRecObj->addOne($id, $ThesaurusName, $ThesaurusTag, --$num, $ThesaurusId);
            }
        }
        //删除
        if (!empty($ThesaurusDelete)) {
            $ThesaurusDeleteIds = explode(',', $ThesaurusDelete);
            foreach ($ThesaurusDeleteIds as $ThesaurusDeleteId) {
                $SearchRecObj->delOne($id, $ThesaurusDeleteId);
            }
        }
        $this->json_alert(0, '操作成功');
    }
}