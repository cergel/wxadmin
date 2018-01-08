<?php

class JspatchController extends Controller
{
    private $aesKey = "9c3435a7dde2c8a2";
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
//            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('index', 'create', 'viewpatch', 'delete', 'patch', 'newver', 'view', 'delpatch'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $model = new JspatchVersion("search");
        $model->unsetAttributes();
        $this->render('index', array('model' => $model,));
    }

    public function actionNewver()
    {
        $model = new JspatchVersion;
        $modelExist = $model->find('app_version=:version', array(':version' => $_POST['version']));
        if ($modelExist) {
            echo json_encode(['ret' => -1, 'msg' => "该版本已经存在"]);
            exit;
        }

        $arrData = [];
        $arrData['app_version'] = $_POST['version'];
        $arrData['remark'] = $_POST['remark'];
        $arrData['created_by'] = Yii::app()->getUser()->getName();
        $arrData['channelId'] = 8;
        $model->attributes = $arrData;
        $model->created_at = date("Y-m-d H:i:s", time());
        $model->updated_at = date("Y-m-d H:i:s", time());
        if ($model->save()) {
            echo json_encode(['ret' => 0, 'msg' => "新建版本成功"]);
            exit;
        }
    }

    //新建补丁
    public function actionPatch($version)
    {
        $model_version = new JspatchVersion;
        $version = $model_version->find('app_version=:version', array(':version' => $version));
        if (!$version) {
            exit("不存在此版本");
        }
        $count = $model_version->getVerCount($version->app_version);

        $JspatchCfg = Yii::app()->params['JsPatch'];
        $dir = $JspatchCfg['target_dir'];


        if (isset($_FILES['patch'])) {
            //判断文件夹是否存在如果不存在则创建
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            //移动文件到jspatch目录
            if ($_FILES['patch']['error'] == 0) {

                $openId = empty($_POST['openId']) ? "" : $_POST['openId'];
                $filename = $_FILES['patch']['name'];
                $tmp = explode(".", $filename);
                //处理文件名扩展因为前端是MIME检测所以考虑没有扩展名的情况出现
                if ($tmp) {
                    $ext = array_pop($tmp);
                    $tmp[] = uniqid();
                    $target_file_name = implode(".", $tmp) . ".{$ext}";
                } else {
                    $target_file_name = $filename . '.' . uniqid();
                }

                if (is_uploaded_file($_FILES['patch']['tmp_name'])) {
                    move_uploaded_file($_FILES['patch']['tmp_name'], $dir . '/' . $target_file_name);
                }
                //检查重命名后的MIME防止上传PHP木马
                $mime = mime_content_type($dir . '/' . $target_file_name);
                if ($mime == "text/x-php") {
                    unlink($dir . '/' . $target_file_name);
                }
                //打开文件
                $file_content = file_get_contents($dir . '/' . $target_file_name);
                //AES加密文件
                $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
                $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->aesKey,
                    $file_content, MCRYPT_MODE_ECB, $iv);
                file_put_contents($dir . '/' . $target_file_name, $ciphertext);
                //保存文件
                $md5 = md5_file($dir . '/' . $target_file_name);
                $model_version->addPatch($target_file_name, $md5, $version->app_version, $count['num'], $openId);
                //跳转到某版本所有的补丁列表
                header('Location:  /app/jspatch/view/version/' . $version->app_version);
                exit;
            }
        };


        $data = [];
        $data['version'] = $version->app_version;
        $data['versioncount'] = $count['num'];
        $this->render('create', array('model' => $data));
    }

    public function actionView($version)
    {
        $model_version = new JspatchVersion;
        $version = $model_version->find('app_version=:version', array(':version' => $version));
        if (!$version) {
            exit("不存在此版本");
        }
        //获取当前版本下所有的补丁
        $Allpatch = $model_version->getAllPatch($version->app_version);
        $this->render('view', array('items' => $Allpatch, 'version' => $version));
    }

    public function actionViewpatch($patch)
    {
        $model_version = new JspatchVersion;
        //判断是否需要更新
        if (isset($_POST['openId'])) {
            $openId = trim($_POST['openId']);
            $model_version->updatePatch($patch, $openId);
        }
        $Patch = $model_version->getPatch($patch);
        $JspatchCfg = Yii::app()->params['JsPatch'];
        $dir = $JspatchCfg['target_dir'];
        $content = file_get_contents($dir . '/' . $Patch[0]['content']);
        //对AES进行解密
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $content = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->aesKey,
            $content, MCRYPT_MODE_ECB, $iv);
        $this->render('viewpatch', array('model' => $Patch[0], 'content' => $content, 'openId' => $Patch[0]['openId']));
    }

    public function actionDelpatch($patch, $appver)
    {
        $model_version = new JspatchVersion;
        $model_version->delPatch($patch);
        header('Location:  /app/jspatch/view/version/' . $appver);
    }

    public function actionDelete($version)
    {
        $model_version = new JspatchVersion;
        $model_version->delVersion($version);
        header('Location:  /app/jspatch');
    }
}