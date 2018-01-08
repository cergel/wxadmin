<?php
class ServiceController extends Controller
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
				'actions'=>array('index','export','download'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionExport()
	{
        // 保证时区正确
        date_default_timezone_set('Asia/Shanghai');
        $starttime   = strtotime($_POST['date']);
        $path = Yii::app()->basePath . '/runtime/weixin_service/' . date('Y-m-d', $starttime) . '.xls';
        // 如果不存在或者大于昨天则生成文件
        if (!file_exists($path) || $starttime >= strtotime(date('Y-m-d'))) {
            $endtime = $starttime + 86400 - 1;
            // 通过微信公众平台接口取得全部聊天记录
            $records = Service::getRecords($starttime, $endtime);
            if ($records === false) {
                exit(json_encode(array(
                    'ret' => -1,
                    'data' => '',
                )));
            }
            // 生成Excel
            Service::generateExcel($records, $starttime);
        }
        exit(json_encode(array(
            'ret' => 0,
            'data' => '' . date('Y-m-d', $starttime) . '.xls',
        )));
	}

	public function actionIndex()
	{
		$this->render('index');
	}

    public function actionDownload()
    {
        set_time_limit(0);
        $path = $_GET['path'];
        if (file_exists(Yii::app()->basePath . '/runtime/weixin_service/' . $path)) {

            $fileName = '客服记录-'.$path;

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
            header("Content-Type:application/force-download");
            header("Content-Type:application/vnd.ms-execl");
            header("Content-Type:application/octet-stream");
            header("Content-Type:application/download");

            //多浏览器下兼容中文标题
            $encoded_filename = urlencode($fileName);
            $ua = $_SERVER["HTTP_USER_AGENT"];
            if (preg_match("/MSIE/", $ua)) {
                header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
            } else if (preg_match("/Firefox/", $ua)) {
                header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
            }

            header("Content-Transfer-Encoding:binary");
            file_put_contents('php://output', file_get_contents(Yii::app()->basePath . '/runtime/weixin_service/' . $path));
        }
    }
}
