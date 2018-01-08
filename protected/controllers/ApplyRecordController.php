<?php
/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/5/18
 * Time: 18:52
 */
class ApplyRecordController extends Controller{
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('index','out'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public function actionIndex($id ='')
    {
        $model=new ApplyRecord('search');
        $model->unsetAttributes();  // clear any default values
        if(!empty($id)){
            $_GET['ApplyRecord']['a_id'] = $id;
            $model->attributes=$_GET['ApplyRecord'];
        }
        if (isset($_GET['ApplyRecord'])){
            $model->attributes = $_GET['ApplyRecord'];
        }

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * 导出
     * @param string $id
     */
    public function actionOut()
    {
        $arrParams = $_GET['ApplyRecord'];
        $arrF =['id','a_id','open_id','user_name','phone','remark_content','channel_id'];
        $arrLike = ['remark_content','user_name'];
        $where = 'SELECT * FROM t_apply_record WHERE 1=1';
        foreach($arrParams as $k=>$v){
            if($v === '' || !in_array($k,$arrF)){
                continue;
            }
            if(in_array($k,$arrLike)){
                $where .= " AND `$k` LIKE '%$v%' ";
            }else{
                $where .= " AND `$k` = '$v' ";
            }
        }
        $info = ApplyRecord::model()->findAllBySql($where);
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=out_data.xls");
        echo  '<table><tr><td>ID</td>><td>报名时间</td><td>活动ID</td><td>用户id</td><td>姓名</td><td>手机号</td><td>备注</td><td>渠道Id</td></tr>';
        foreach($info as $val){
            echo '<tr>';
            echo '<td>'.$val->id.'</td>';
            echo '<td>'.date('Y-m-d H:i:s',$val->create_time).'</td>';
            echo '<td>'.$val->a_id.'</td>';
            echo '<td>'.$val->open_id.'</td>';
            echo '<td>'.$val->user_name.'</td>';
            echo '<td>'.$val->phone.'</td>';
            echo '<td>'.$val->remark_content.'</td>';
            echo '<td>'.$val->channel_id.'</td>';
            echo '</tr>';
        }
        echo '</table>';

        //$info = ApplyRecord::model()->findAll($arrParams);
        exit;
    }
    /**
     * Performs the AJAX validation.
     * @param ApplyRecord $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='apply-record-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}