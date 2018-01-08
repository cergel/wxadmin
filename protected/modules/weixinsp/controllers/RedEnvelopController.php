<?php

class RedEnvelopController extends Controller
{
    use AlertMsg;
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
                'actions' => array('index', 'save'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $redEnvelopObj = new RedEnvelop();
        $data = $redEnvelopObj->getInfo();
        if (!empty($data)) {
            $data = json_decode($data, true);
        }
        $this->render('index', ['data' => $data]);
    }

    public function actionSave()
    {
        if (!isset($_POST['redEnvelop']['redEnvelopStatus'])) {
            $this->json_alert(1, '参数错误');
        }
        $data = ['redEnvelopStatus' => $_POST['redEnvelop']['redEnvelopStatus'],
            'pid' => isset($_POST['redEnvelop']['pid']) ? trim($_POST['redEnvelop']['pid']) : 0,
            'icon' => isset($_POST['redEnvelop']['icon']) ? trim($_POST['redEnvelop']['icon']) : '',
            'text1' => isset($_POST['redEnvelop']['text1']) ? trim($_POST['redEnvelop']['text1']) : '',
            'text2' => isset($_POST['redEnvelop']['text2']) ? trim($_POST['redEnvelop']['text2']) : '',
        ];
        $redEnvelopObj = new RedEnvelop();
        $re = $redEnvelopObj->save($data);
        Yii::app()->user->setFlash('success', '保存成功');
        $this->json_alert($re['code'], $re['msg']);
    }
}
