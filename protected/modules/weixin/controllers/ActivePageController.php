<?php

class ActivePageController extends Controller
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
            array(
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'index', 'delete', 'deleteimg', '_activity'),  //'users'=>array('@'), // 这个为啥不生效？
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * ajax搜索活动
     */
    public function action_activity()
    {
        $keyword = $_GET['keyword'];
        if (!$keyword) exit;
        $arrData = ActivePage::getActiveInfo($keyword);
        $arrOutPut = [];
        if (!empty($arrData)) {
            $arrOutPut[] = [
                'iResourceID' => empty($arrData['resId']) ? '' : $arrData['resId'],
                'sBonusName' => empty($arrData['name']) ? '' : $arrData['name'],
            ];
        }
        exit(json_encode($arrOutPut));
        /*        $criteria = new CDbCriteria();
                $criteria->select = 'iResourceID,sBonusName';
                if (is_numeric($keyword)) {
                    $criteria->condition='iResourceID=:iResourceID';
                    $criteria->params=array(':iResourceID'=>$keyword);
                } else {
                    $criteria->addSearchCondition('sBonusName', $keyword);
                }
                $results = BonusResource::model()->findAll( $criteria );
                $output = array();
                foreach($results as $result) {
                    $output[] = array(
                        'iResourceID' => $result->iResourceID,
                        'sBonusName'  => $result->sBonusName,
                    );
                }
                exit(json_encode($output));
                */
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new ActivePage;
        $selectedCinemas = array();
        if (isset($_POST['ActivePage'])) {
            $_POST['ActivePage']['iShowStartTime'] = !empty($_POST['ActivePage']['iShowStartTime'])?strtotime($_POST['ActivePage']['iShowStartTime']):0;
            $_POST['ActivePage']['iShowEndTime'] = !empty($_POST['ActivePage']['iShowEndTime'])?strtotime($_POST['ActivePage']['iShowEndTime']):0;
            $content = [];
            if (!empty($_POST['ActivePage']['iType']) && $_POST['ActivePage']['iType'] == '2') {
                $arrMovieData = CommonAPI::curlopen('movieMore', ['movie' => implode('|', $_POST['film'])]);
                foreach ($_POST['film'] as $k => $img) {
                    if (!empty($_FILES['img']['error'][$k]) || empty($_FILES['img']['name'][$k])) continue;
                    $content[$k]['imgData']['name'] = $_FILES['img']['name'][$k];
                    $content[$k]['imgData']['type'] = $_FILES['img']['type'][$k];
                    $content[$k]['imgData']['tmp_name'] = $_FILES['img']['tmp_name'][$k];
                    $content[$k]['imgData']['error'] = $_FILES['img']['error'][$k];
                    $content[$k]['imgData']['size'] = $_FILES['img']['size'][$k];
                    $name = explode('.', $_FILES['img']['name'][$k]);
                    $content[$k]['img'] = $content[$k]['imgData']['img'] = date('YmdHis') . rand(1000, 9999) . $k . '.' . $name[count($name) - 1];
                    $content[$k]['order'] = empty($_POST['sort'][$k]) ? '10' : $_POST['sort'][$k];
                    $content[$k]['id'] = $content[$k]['movie_id'] = $_POST['film'][$k];
                    $content[$k]['name'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['name'] : '';
                    $content[$k]['actor'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['actor'] : '';
                    $content[$k]['director'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['director'] : '';
                    $content[$k]['remark'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['remark'] : '';
                    $content[$k]['poster_url'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['poster_url'] : '';
                    $content[$k]['starTime'] = $content[$k]['endTime'] = 0;
                }
                $_POST['ActivePage']['sContent'] = json_encode($content);
            } else {
                $_POST['ActivePage']['sContent'] = '';
            }
            $_POST['ActivePage']['iTime'] = !empty($_POST['ActivePage']['iTime']) ? strtotime($_POST['ActivePage']['iTime']) : '';
            $_POST['ActivePage']['iPreheatEndTime'] = !empty($_POST['ActivePage']['iPreheatEndTime']) ? strtotime($_POST['ActivePage']['iPreheatEndTime']) : '';
            $_POST['ActivePage']['iEndTime'] = !empty($_POST['ActivePage']['iEndTime']) ? strtotime($_POST['ActivePage']['iEndTime']) : '';
            $model->attributes = $_POST['ActivePage'];
            if (isset($_POST['cinemas']))
                $selectedCinemas = $_POST['cinemas'];
            $sPic = CUploadedFile::getInstance($model, 'sPic');
            if ($sPic) {
                $model->sPic = 'banner.' . $sPic->getExtensionName();//获取文件名
            }
            $sSharePic = CUploadedFile::getInstance($model, 'sSharePic'); //获取表单名为filename的上传信息
            if ($sSharePic) {
                $model->sSharePic = 'share.' . $sSharePic->getExtensionName();//获取文件名
            }
            if ($model->validate()) {
                if ($model->save()) {
                    $iType = $model->iType == '2' ? '_More' : '';
                    // 保存活动关联
                    if (isset($_POST['iResourceID']) && $_POST['iResourceID']) {
                        foreach ($_POST['iResourceID'] as $iResourceID) {
                            $ab = new ActivePageBonusResource();
                            $ab->iResourceID = $iResourceID;
                            $ab->iActivePageID = $model->iActivePageID;
                            $ab->save();
                        }
                    }
                    // 处理影城关联
                    if (isset($_POST['cinemas'])) {
                        foreach ($_POST['cinemas'] as $k => $cinema) {
                            $ac = new ActivePageCinema();
                            $ac->iActivePageID = $model->iActivePageID;
                            $ac->iCinemaID = $cinema;
                            $ac->save();
                        }
                    }
                    //先定死吧
                    $arrDataInfo = ["active_page_new"];
                    //生成图片地址
                    $targetDir = Yii::app()->params['active_page_new']['target_dir'] . $iType . '/' . $model->iActivePageID;
                    //拷贝文件，生成目录待会处理
                    $model->makeFile();
//                    $json = '';
//                    foreach ($arrDataInfo as $val) {
//                        $json = $model->iType == '1' ? $model->makeFile($val, $json) : $model->makeFileMore($val, $json);
//                    }
                    //图片处理
                    if ($sPic) {
                        $sPic->saveAs($targetDir . '/images/' . $model->sPic, true);
                        //循环拷贝其他地址的图片
//                        foreach ($arrDataInfo as $value) {
//                            if ($targetDir . '/images/' . $model->sPic != Yii::app()->params[$value]['target_dir'] . $iType . '/' . $model->iActivePageID . '/images/' . $model->sPic) {
//                                UploadFiles::copyFile($targetDir . '/images/' . $model->sPic, Yii::app()->params[$value]['target_dir'] . $iType . '/' . $model->iActivePageID . '/images/' . $model->sPic, true);
//                            }
//                        }
                    }
                    if ($sSharePic) {
                        $sSharePic->saveAs($targetDir . '/images/' . $model->sSharePic, true);
                        //循环拷贝其他地址的图片
//                        foreach ($arrDataInfo as $values) {
//                            if ($targetDir . '/images/' . $model->sSharePic != Yii::app()->params[$values]['target_dir'] . $iType . '/' . $model->iActivePageID . '/images/' . $model->sSharePic) {
//                                UploadFiles::copyFile($targetDir . '/images/' . $model->sSharePic, Yii::app()->params[$values]['target_dir'] . $iType . '/' . $model->iActivePageID . '/images/' . $model->sSharePic, true);
//                            }
//                        }
                    }
//                    if ($model->iType == '2' && $content) {
//                        //循环保存图片
//                        foreach ($content as $imgData) {
//                            if (is_file($imgData['imgData']['tmp_name']))
//                                foreach ($arrDataInfo as $values) {
//                                    UploadFiles::copyFile($imgData['imgData']['tmp_name'], Yii::app()->params[$values]['target_dir'] . $iType . '/' . $model->iActivePageID . '/images/' . $imgData['imgData']['img'], TRUE);
//                                }
//                        }
//                    }
                    Yii::app()->user->setFlash('success', '活动模板创建成功');
                    $this->redirect(array('update', 'id' => $model->iActivePageID));
                }
            }
        }
        $activities = array();
        if (isset($_POST['iResourceID'])) {
            foreach ($_POST['iResourceID'] as $resource) {
                $arrData = ActivePage::getActiveInfo($resource);
                if (!empty($arrData)) {
                    $activities[] = (object)[
                        'iResourceID' => empty($arrData['resId']) ? '' : $arrData['resId'],
                        'sBonusName' => empty($arrData['name']) ? '' : $arrData['name'],
                    ];
                }
            }
        }
        $model->iShowStartTime = $model->iShowEndTime = '';
        $model->iType = !empty($model->iType) ? $model->iType : '1';
        $model->iTime = $model->iPreheatEndTime = $model->iEndTime = '';
        $this->render('create', array(
            'model' => $model,
            'activities' => $activities,
            'selectedCinemas' => $selectedCinemas,
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
        $selectedCinemas = array();
        if (is_array($model->cinemas) && !empty($model->cinemas))
            foreach ($model->cinemas as $result) {
                $selectedCinemas[] = $result['iCinemaID'];
            }
        if (isset($_POST['ActivePage'])) {
            $_POST['ActivePage']['iShowStartTime'] = !empty($_POST['ActivePage']['iShowStartTime'])?strtotime($_POST['ActivePage']['iShowStartTime']):0;
            $_POST['ActivePage']['iShowEndTime'] = !empty($_POST['ActivePage']['iShowEndTime'])?strtotime($_POST['ActivePage']['iShowEndTime']):0;
            $type = $model->iType == '1' ? '' : '_More';
            $content = [];
            $allContent = [];
            $_POST['ActivePage']['iTime'] = !empty($_POST['ActivePage']['iTime']) ? strtotime($_POST['ActivePage']['iTime']) : '';
            $_POST['ActivePage']['iPreheatEndTime'] = !empty($_POST['ActivePage']['iPreheatEndTime']) ? strtotime($_POST['ActivePage']['iPreheatEndTime']) : '';
            $_POST['ActivePage']['iEndTime'] = !empty($_POST['ActivePage']['iEndTime']) ? strtotime($_POST['ActivePage']['iEndTime']) : '';
            $arrJson = json_decode($model->sContent, true);
            if ($model->iType == '2' && !empty($_POST['film'])) {
                $arrMovieData = CommonAPI::curlopen('movieMore', ['movie' => implode('|', $_POST['film'])]);
                foreach ($_POST['film'] as $k => $img) {
                    if (!empty($_FILES['img']['error'][$k]) || empty($_FILES['img']['name'][$k])) continue;
                    $content[$k]['imgData']['name'] = $_FILES['img']['name'][$k];
                    $content[$k]['imgData']['type'] = $_FILES['img']['type'][$k];
                    $content[$k]['imgData']['tmp_name'] = $_FILES['img']['tmp_name'][$k];
                    $content[$k]['imgData']['error'] = $_FILES['img']['error'][$k];
                    $content[$k]['imgData']['size'] = $_FILES['img']['size'][$k];
                    $name = explode('.', $_FILES['img']['name'][$k]);
                    $content[$k]['img'] = $content[$k]['imgData']['img'] = date('YmdHis') . rand(1000, 9999) . $k . '.' . $name[count($name) - 1];
                    $content[$k]['order'] = empty($_POST['sort'][$k]) ? '10' : $_POST['sort'][$k];
                    $content[$k]['id'] = $content[$k]['movie_id'] = $_POST['film'][$k];
                    $content[$k]['name'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['name'] : '';
                    $content[$k]['actor'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['actor'] : '';
                    $content[$k]['director'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['director'] : '';
                    $content[$k]['remark'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['remark'] : '';
                    $content[$k]['poster_url'] = !empty($arrMovieData[$content[$k]['id']]) ? $arrMovieData[$content[$k]['id']]['poster_url'] : '';
                    $content[$k]['starTime'] = $content[$k]['endTime'] = 0;
                }
                if (!empty($arrJson) && !empty($content))
                    $allContent = array_merge($arrJson, $content);
                elseif (!empty($content)) $allContent = $content;
                else $allContent = $arrJson;
                $_POST['ActivePage']['sContent'] = json_encode($allContent);
            } else {
                $_POST['ActivePage']['sContent'] = $model->sContent;
            }
            $_POST['ActivePage']['iType'] = $model->iType;
            $sPic = CUploadedFile::getInstance($model, 'sPic');
            if (!$sPic)
                unset($_POST['ActivePage']['sPic']);
            $sSharePic = CUploadedFile::getInstance($model, 'sSharePic');
            if (!$sSharePic)
                unset($_POST['ActivePage']['sSharePic']);
            $model->setAttributes($_POST['ActivePage']);
//            $model->sTempurl = $_POST['ActivePage']['sTempurl'];
            if (isset($_POST['cinemas']))
                $selectedCinemas = $_POST['cinemas'];
            //三种类型判断
            if (empty($_POST['ActivePage']['iwx'])) {
                $model->iwx = 0;
            } else $model->iwx = 1;
            if (empty($_POST['ActivePage']['iqq'])) {
                $model->iqq = 0;
            } else $model->iqq = 1;
            if (empty($_POST['ActivePage']['imobile'])) {
                $model->imobile = 0;
            } else $model->imobile = 1;
            if ($model->validate()) {

                //图片
                if ($sPic) {
                    $filename = 'banner.' . $sPic->getExtensionName();
                    $model->sPic = $filename;
                }
                if ($sSharePic) {
                    $filename = 'share.' . $sSharePic->getExtensionName();
                    $model->sSharePic = $filename;
                }
                if ($model->save()) {
                    // 处理影城关联
                    ActivePageCinema::model()->deleteAllByAttributes(array(
                        'iActivePageID' => $model->iActivePageID
                    ));
                    if (isset($_POST['cinemas'])) {
                        foreach ($_POST['cinemas'] as $k => $cinema) {
                            $cnc = new ActivePageCinema();
                            $cnc->iActivePageID = $model->iActivePageID;
                            $cnc->iCinemaID = $cinema;
                            $cnc->save();
                        }
                    }
                    // 保存活动关联
                    ActivePageBonusResource::model()->deleteAllByAttributes(array('iActivePageID' => $model->iActivePageID));
                    if ($_POST['iResourceID']) {
                        foreach ($_POST['iResourceID'] as $iResourceID) {
                            $ab = new ActivePageBonusResource();
                            $ab->iResourceID = $iResourceID;
                            $ab->iActivePageID = $model->iActivePageID;
                            $ab->save();
                        }
                    }

                    $arrDataInfo = [];
                    if (empty($arrDataInfo))
                        $arrDataInfo[] = "active_page_new";
                    //$arrDataInfo =["active_page","active_page_QQ","active_page_Mobile"];

                    //生成图片
                    $targetDir = Yii::app()->params['active_page_new']['target_dir'] . $type . '/' . $model->iActivePageID;
                    $model->makeFile();
//                    $json = '';
//                    foreach ($arrDataInfo as $val) {
//                        $json = $model->iType == '1' ? $model->makeFile($val, $json) : $model->makeFileMore($val, $json);
//                    }
                    if ($sPic) {
                        $sPic->saveAs($targetDir . '/images/' . $model->sPic, true);
                        //循环拷贝其他地址的图片
//                        foreach ($arrDataInfo as $value) {
//                            if ($targetDir . '/images/' . $model->sPic != Yii::app()->params[$value]['target_dir'] . $type . '/' . $model->iActivePageID . '/images/' . $model->sPic) {
//                                UploadFiles::copyFile($targetDir . '/images/' . $model->sPic, Yii::app()->params[$value]['target_dir'] . $type . '/' . $model->iActivePageID . '/images/' . $model->sPic, true);
//                                //copy($targetDir.'/images/' . $model->sPic, Yii::app()->params[$value]['target_dir'] .$type. '/' . $model->iActivePageID.'/images/' . $model->sPic);
//                            }
//                        }
                    }
                    if ($sSharePic) {
                        $sSharePic->saveAs($targetDir . '/images/' . $model->sSharePic, true);
                        //循环拷贝其他地址的图片
//                        foreach ($arrDataInfo as $values) {
//                            if ($targetDir . '/images/' . $model->sSharePic != Yii::app()->params[$values]['target_dir'] . $type . '/' . $model->iActivePageID . '/images/' . $model->sSharePic) {
//                                UploadFiles::copyFile($targetDir . '/images/' . $model->sSharePic, Yii::app()->params[$values]['target_dir'] . $type . '/' . $model->iActivePageID . '/images/' . $model->sSharePic, true);
//                                //copy($targetDir.'/images/' . $model->sSharePic, Yii::app()->params[$values]['target_dir'] .$type. '/' . $model->iActivePageID.'/images/' . $model->sSharePic);
//                            }
//                        }
                    }
//                    if ($model->iType == '2' && !empty($content)) {
//                        //循环保存图片
//                        foreach ($content as $imgData) {
//                            if (is_file($imgData['imgData']['tmp_name']))
//                                foreach ($arrDataInfo as $values) {
//                                    UploadFiles::copyFile($imgData['imgData']['tmp_name'], Yii::app()->params[$values]['target_dir'] . $type . '/' . $model->iActivePageID . '/images/' . $imgData['imgData']['img'], TRUE);
//                                }
//                        }
//                    }
                    //记录-刷新CDN
                    if(!empty($arrDataInfo)){
                        $arrCdn = [];
                        foreach($arrDataInfo as $active){
                            $arrCdn[] = Yii::app()->params[$active]['final_url'] . '/' . $model->iActivePageID.'/index.html';
                            $arrCdn[] = Yii::app()->params[$active]['final_url'] . '/' . $model->iActivePageID.'/images/'.$model->sSharePic;
                            $arrCdn[] =  Yii::app()->params[$active]['final_url'] . '/' . $model->iActivePageID.'/images/'.$model->sPic;
                        }
                        FlushCdn::setUrlToRedis($arrCdn);

                    }
//                     $model->makeFile();
//                     if ($sPic) {
//                         $sPic->saveAs($targetDir . '/images/' . $model->sPic, true);
//                     }
//                     if ($sSharePic) {
//                         $sSharePic->saveAs($targetDir . '/images/' . $model->sSharePic, true);
//                     }
//                    FlushCdn::setUrlToRedis(Yii::app()->params['active_page']['final_url'] . '/' . $model->iActivePageID, Yii::app()->params['active_page']['target_dir'] . '/' . $model->iActivePageID);
                    Yii::app()->user->setFlash('success', '活动模板更新成功');
                    $this->redirect(array('update', 'id' => $model->iActivePageID));
                }
            }
        }
        $activities = array();
        foreach (ActivePageBonusResource::model()->findAllByAttributes(array('iActivePageID' => $model->iActivePageID)) as $resource) {
            $arrData = ActivePage::getActiveInfo($resource->iResourceID);
            if (!empty($arrData)) {
                $activities[] = (object)[
                    'iResourceID' => empty($arrData['resId']) ? '' : $arrData['resId'],
                    'sBonusName' => empty($arrData['name']) ? '' : $arrData['name'],
                ];
            }
        }
        $model->iTime = date('Y-m-d H:i:s', $model->iTime);
        $model->iPreheatEndTime = date('Y-m-d H:i:s', $model->iPreheatEndTime);
        $model->iEndTime = date('Y-m-d H:i:s', $model->iEndTime);
        $model->iShowEndTime = !empty($model->iShowEndTime )?date('Y-m-d',$model->iShowEndTime ):'';
        $model->iShowStartTime = !empty($model->iShowStartTime )?date('Y-m-d',$model->iShowStartTime ):'';
        $this->render('update', array(
            'model' => $model,
            'activities' => $activities,
            'selectedCinemas' => $selectedCinemas
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * ajax删除指定的数据
     */
    public function actionDeleteimg()
    {
        $id = Yii::app()->request->getPost("id");
        $simg = Yii::app()->request->getPost("simg");
        $model = $this->loadModel($id);
        if ($model->sContent) {
            $sContent = json_decode($model->sContent, true);
            foreach ($sContent as &$val) {
                if ($val['img'] == $simg) {
                    $val = '';
                }
            }
            //echo json_encode($sContent);exit;
            $sContent = array_filter($sContent);
            $sContent = array_values($sContent);
            $model->sContent = json_encode($sContent);
        }
        if ($model->save()) {
            //更新模板
// 			$discoveryBannerModel=new DiscoveryBanner();
// 			$discoveryBannerModel->saveMemcache('app_discovery'," banner.iStatus ='1' AND banner.iType = '1'");
// 			$discoveryBannerModel->saveMemcache('app_discovery_new'," banner.iStatus ='1'");
            //DiscoveryBanner::createJson();
            echo 1;
        } else
            echo 0;
        exit;
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new ActivePage('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActivePage']))
            $model->attributes = $_GET['ActivePage'];
        $this->render('index', array(
            'model' => $model,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ActivePage the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ActivePage::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ActivePage $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'active-page-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
