<?php

class AssetsController extends Controller
{
    // Uncomment the following methods and override them if needed
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
                'actions' => array('index', 'upload', 'rename', 'delete', 'cos'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $AssetsCfg = Yii::app()->params['Assets'];
        $dir = $AssetsCfg['target_dir'];
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $list = glob($dir . "/*");
        $data['list'] = $list;
        $this->render('index', $data);
    }

    public function actionUpload()
    {
        $AssetsCfg = Yii::app()->params['Assets'];
        $dir = $AssetsCfg['target_dir'];
        if ($_FILES['file']['error'] == 0) {

            $filename = $_FILES['file']['name'];
            $tmp = explode(".", $filename);
            //处理文件名扩展因为前端是MIME检测所以考虑没有扩展名的情况出现
            if ($tmp) {
                $ext = array_pop($tmp);
                $target_file_name = date("YmdHis") . uniqid() . ".{$ext}";
            } else {
                $target_file_name = date("YmdHis") . '.' . uniqid();
            }

            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], $dir . '/' . $target_file_name);
            }

            $data['status'] = true;
            $data['text'] = "上传成功";
            $data['url'] = $target_file_name;
            echo json_encode($data);
            //检查重命名后的MIME防止上传PHP木马
            $mime = mime_content_type($dir . '/' . $target_file_name);
            if ($mime == "text/x-php") {
                unlink($dir . '/' . $target_file_name);
            }
            exit;
        } else {
            $data['status'] = false;
            $data['text'] = "上传失败";
            echo json_encode($data);
            exit;
        }
    }

    public function actionRename()
    {
        $AssetsCfg = Yii::app()->params['Assets'];
        $dir = $AssetsCfg['target_dir'];
        if (empty($_POST['filename']) || empty($_POST['newname'])) {
            $data['status'] = false;
            $data['text'] = "源文件名和目的文件名不能为空";
            echo json_encode($data);
            exit;
        }

        if ($_POST['filename'] == $_POST['newname']) {
            $data['status'] = false;
            $data['text'] = "源文件名不能等于目的文件名";
            echo json_encode($data);
            exit;
        }

        $src = current(glob($dir . "/{$_POST['filename']}"));
        if (!$src) {
            $data['status'] = false;
            $data['text'] = "源文件名不存在";
            echo json_encode($data);
            exit;
        }

        $des = current(glob($dir . "/{$_POST['newname']}"));
        if ($des) {
            $data['status'] = false;
            $data['text'] = "目的文件名已存在";
            echo json_encode($data);
            exit;
        }

        rename($src, $dir . "/{$_POST['newname']}");
        $data['status'] = true;
        $data['text'] = "操作成功";
        echo json_encode($data);

        //检查重命名后的MIME防止上传PHP木马
        $mime = mime_content_type($dir . "/{$_POST['newname']}");
        if ($mime == "text/x-php") {
            unlink($dir . "/{$_POST['newname']}");
        }
        exit;
    }

    public function actionDelete()
    {
        $AssetsCfg = Yii::app()->params['Assets'];
        $dir = $AssetsCfg['target_dir'];

        if (empty($_POST['filename'])) {
            $data['status'] = false;
            $data['text'] = "源文件名不能为空";
            echo json_encode($data);
            exit;
        }

        $src = current(glob($dir . "/{$_POST['filename']}"));
        if (!$src) {
            $data['status'] = false;
            $data['text'] = "文件不存在";
            echo json_encode($data);
            exit;
        }

        unlink($dir . "/{$_POST['filename']}");
        $data['status'] = true;
        $data['text'] = "操作成功";
        echo json_encode($data);
    }


    public function actionCos()
    {

        if ($_FILES['file']['error'] == 0) {

            $filename = $_FILES['file']['name'];
            $tmp = explode(".", $filename);
            //处理文件名扩展因为前端是MIME检测所以考虑没有扩展名的情况出现
            if ($tmp) {
                $ext = array_pop($tmp);
                $target_file_name = date("YmdHis") . uniqid() . ".{$ext}";
            } else {
                $target_file_name = date("YmdHis") . '.' . uniqid();
            }

            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $link = CosUpload::upload($_FILES['file']['tmp_name'], '/app-biz/' . $target_file_name);
            }

            $data['status'] = true;
            $data['text'] = "上传成功";
            $data['url'] = $link;
            echo json_encode($data);
            exit;
        } else {
            $data['status'] = false;
            $data['text'] = "上传失败";
            echo json_encode($data);
            exit;
        }
    }


}