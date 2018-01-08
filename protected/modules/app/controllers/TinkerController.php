<?php

class TinkerController extends Controller
{
    public function actionIndex()
    {
        $model = new TinkerVersion("search");
        $model->unsetAttributes();
        $this->render('index', array('model' => $model,));
    }

    public function actionNewver()
    {
        $model = new TinkerVersion();
        $modelExist = $model->find('app_version=:version', array(':version' => $_POST['version']));
        if ($modelExist) {
            echo json_encode(['ret' => -1, 'msg' => "该版本已经存在"]);
            exit;
        }

        $arrData = [];
        $arrData['app_version'] = $_POST['version'];
        $arrData['remark'] = $_POST['remark'];
        $arrData['created_by'] = Yii::app()->getUser()->getName();
        $arrData['channelId'] = 9;
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
        $model_version = new TinkerVersion;
        $version = $model_version->find('app_version=:version', array(':version' => $version));
        if (!$version) {
            exit("不存在此版本");
        }
        $count = $model_version->getVerCount($version->app_version);

        if (isset($_FILES['patch'])) {
            //判断文件夹是否存在如果不存在则创建
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
                    $md5 = md5_file($_FILES['patch']['tmp_name']);
                    $link = CosUpload::upload($_FILES['patch']['tmp_name'], '/app-patch/' . $target_file_name);
                }

                if ($link) {
                    $model_version->addPatch($link, $md5, $version->app_version, $count['num'], $openId);
                }

                //跳转到某版本所有的补丁列表
                header('Location:  /app/tinker/view/version/' . $version->app_version);
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
        $model_version = new TinkerVersion;
        $version = $model_version->find('app_version=:version', array(':version' => $version));
        if (!$version) {
            exit("不存在此版本");
        }
        //获取当前版本下所有的补丁
        $Allpatch = $model_version->getAllPatch($version->app_version);
        $this->render('view', array('items' => $Allpatch, 'version' => $version));
    }

    public function actionDelpatch($patch, $appver)
    {
        $model_version = new TinkerVersion;
        $model_version->delPatch($patch);
        header('Location:  /app/tinker/view/version/' . $appver);
    }

    public function actionDelete($version)
    {
        $model_version = new TinkerVersion;
        $model_version->delVersion($version);
        header('Location:  /app/tinker');
    }

    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }

    public function actions()
    {
        // return external action classes, e.g.:
        return array(
            'action1'=>'path.to.ActionClass',
            'action2'=>array(
                'class'=>'path.to.AnotherActionClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
    */
}