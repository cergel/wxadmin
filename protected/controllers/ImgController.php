<?php

class ImgController extends Controller
{
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
                'actions' => array('ajaxUpload'),
                'users' => array('*'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }
    /**
     * 图片异步上传接口
     */
    public function actionAjaxUpload()
    {
        $tmpFileName = $_FILES['UpLoadFile']['tmp_name'];
        $fileName = $_FILES['UpLoadFile']['name'];
        $r = $this->uploadFile($tmpFileName, $fileName);
        if ($r) {
            $arrReturn['success'] = 1;
            $arrReturn['path'] = $r;
        } else {
            $arrReturn['success'] = 0;
        }
        echo json_encode($arrReturn);exit;
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
        $fileName = md5(file_get_contents($tmpFileName).rand(1000,9999).time());
        $fileName = $fileName . '.' . $extension;//文件名:  md5(文件内容).类型
        $url = $this->processImang($tmpFileName,$fileName);
        if(!empty($url)){
            $tmpFileName = $url;
        }
        // 上传到腾讯云
        $url = CosUpload::upload($tmpFileName,$path.'/'.$fileName);
        if(!empty($url)){
            return $url;
        }else{
            return false;
        }
    }
    /**
     * 压缩图片
     */
    private function processImang($uploadDir,$fileName){
        $data = file_get_contents($uploadDir);
        $data = base64_encode($data);
        $arrData = [];
        $arrData['channelId'] = 8;
        $arrData['fileName'] = $fileName;
        $arrData['content'] = $data;
        $url = "http://img-handle.wepiao.com/common/sohu-image/img-process";
        $res = Https::getPost($arrData,$url);
        $res = json_decode($res,true);
        if(!empty($res['data']['imgUrl'])){
            $info = file_get_contents($res['data']['imgUrl']);
            //$image = 'new'.$image;
            //$uploadDir = Yii::app()->basePath . '/../uploads/app_daySign/'.$image;
            file_put_contents($uploadDir.$fileName,$info);
            return $uploadDir.$fileName;
        }else return '';
    }


}
