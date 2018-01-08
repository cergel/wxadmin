<?php

class UserTagController extends Controller
{
    public $layout = '//layouts/main';
    const CDN_APPNFS = 'https://appnfs.wepiao.com/';

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
                'actions' => array('index', 'create', 'update', 'delete','getUserInfo'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 首页：
     */
    public function actionIndex()
    {
        $model = new UserTag('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserTag']))
            $model->attributes = $_GET['UserTag'];
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * 创建
     * @throws CHttpException
     */
    public function actionCreate()
    {
        $model = new UserTag();
        $tag = [];
        if (isset($_POST['UserTag'])) {
            $arrData = $_POST['UserTag'];
            $arrData['created'] = $arrData['updated'] = time();
            $model->attributes = $arrData;
            if ($model->save()) {
                self::saveTag($model->id);
                Yii::app()->user->setFlash('success', '创建成功');
                $this->redirect(array('update', 'id' => $model->id,));
            }
        }
        $model->tag = $tag;
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * 修改
     * @param $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        if (isset($_POST['UserTag'])) {
            $arrData = $this->actionGetUserInfo($model->mobileNo);
            $model->summary = $_POST['UserTag']['summary'];
            if(isset($arrData['nickname']) && $arrData['nickname'] != $model->nickname){
                $model->nickname = $arrData['nickname'];
            }
            $model->save();
            self::saveTag($model->id);
            Yii::app()->user->setFlash('success', '更新成功');
            $this->redirect(array('update', 'id' => $model->id,));
        }
        $tag = [];
        if (is_array($model->tag)){
            foreach($model->tag as $result) {
                $tag[] = $result->tag_id;
            }
        }
        $model->tag = $tag;
        $this->render('update', array( 'model' => $model));
    }

    /**
     * 标签修改
     * @param $id
     */
    private function saveTag($id){
        //删除标签
        UserTagKol::model()->deleteAllByAttributes(['k_id'=>$id]);
        // 插入新的标签
        if(!empty($_POST['UserTag']['tag'])){
            foreach($_POST['UserTag']['tag'] as $val){
                $userTagKolModel = new UserTagKol();
                $userTagKolModel->attributes = ['k_id'=>$id,'tag_id'=>$val];
                $userTagKolModel->save();
            }
        }
        UserTag::model()->saveUserTag($id);
    }


    /**
     * 删除
     * @param $id
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        UserTagKol::model()->deleteAllByAttributes(['k_id'=>$id]);
        UserTag::model()->saveUserTag($id,1);
        $this->loadModel($id)->delete();
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function loadModel($id)
    {
        $model = UserTag::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }



    /**
     * Performs the AJAX validation.
     * @param Active $model the model to be validated
     */
    protected  function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * 根据手机号获取用户相关信息
     */
    public function actionGetUserInfo($mobileNos = '')
    {
        $mobileNo = isset($_GET['mobileNo'])?$_GET['mobileNo']:(!empty($mobileNos)?$mobileNos:'');
        $arrData = [];
        if(!empty($mobileNo)){
            $openId = UCenter::getWYOpenIdByMobileNo($mobileNo,13);
            if(!empty($openId[0])){
                $openId = $openId[0];
                $arrData['openId'] = $openId;
                $userInfo = UCenter::getUserInfoByUcid($openId);
                if(isset($userInfo['nickName'])){
                    $arrData['nickname'] = $userInfo['nickName'];
                    $arrData['photoUrl'] = $userInfo['photoUrl'] == '' ? 'https://appnfs.wepiao.com/dataImage/photo.png'  : $userInfo['photoUrl'];
                }else{
                    $arrData['nickname'] = '';
                    $arrData['photoUrl'] = '';
                }
            }else {
                $arrData['openId'] = '';
                $arrData['error']='该用户未注册';
            }
        }
        if(empty($mobileNos)){
            $arrData['sub'] = !empty($arrData)?0:1;
            echo json_encode($this->convPhotoUrl($arrData));exit;
        }else{
            return $arrData;
        }

    }

    /**
     * 转换图片地址
     * @param array $data 兼容与ret同级的数组data和最终返回的完整数组
     * @return array
     */
    private function convPhotoUrl($arrData)
    {
        $dataType = (isset($arrData['ret']) && isset($arrData['data'])) ? 1 : 0;
        $data = $dataType ? $arrData['data'] : $arrData;
        //对输出结果中的头像字段（photo,photoUrl）进行处理，没有域名的添加域名，没有头像的使用默认地址
        $PHOTO_DEFAULT = self::CDN_APPNFS . '/dataImage/photo.png';
        $AVATAR_NAMES = ['photo', 'photoUrl'];
        foreach ($AVATAR_NAMES as $k) {
            if (!empty($data[$k])) {
                if (!stristr($data[$k], 'http')) {
                    $data[$k] = self::CDN_APPNFS . $data[$k];
                }
            } elseif (isset($data[$k]) && is_array($data)) {
                $data[$k] = $PHOTO_DEFAULT;
            }
        }
        if ($dataType) {
            $arrData['data'] = $data;
            return $arrData;
        } else {
            return $data;
        }
    }
}
