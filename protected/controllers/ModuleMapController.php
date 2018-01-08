<?php

/**
 * BayMax主页模块地图
 *
 * @author CHAIYUE
 * @version 2016-12-13
 */
class ModuleMapController extends Controller
{
    public $layout = '//layouts/main';
    static private $groupMap = null;
    static private $blackOrWhite = null;
    static private $config = null;

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

    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('mapIndex', 'view'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * 获取地图列表(数据处理)
     */
    public function actionMapIndex()
    {
        //加载配置文件
        $config = require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'homePageModular.php');
        self::$config = $config;
        //处理用户Map
        $userid = Yii::app()->getUser()->getId();
        $userinfo = User::model()->findByPk($userid);
        $groupId = $userinfo->sGroupId;
        if ($groupId == 0) {
            self::$groupMap = [];
        } else {
            $groupinfo = UserGroup::model()->findByPk($groupId);
            self::$groupMap = $groupinfo ? json_decode(stripslashes($groupinfo->authList), true) : [];
            self::$blackOrWhite = $groupinfo ? $groupinfo->blackOrWhite : [];
            self::$config['treeMap'] = $this->handleMap();
        }
        $treeMap = self::$config['treeMap'];
        $MapDetail = self::$config['detail'];
        //迭代器展开树形结构
        $html = '';
        foreach ($treeMap as $key => $map) {
            if (!isset($MapDetail[$key])) {
                continue;
            } else {
                $detail = $MapDetail[$key];
            }
            $ModuleId = $key == '/' ? 'quanzhan' : $key;
            $ModuleName = $detail['name'];
            $html .= '<div class="panel panel-heading">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" data-parent="" href="#' . $ModuleId . '" aria-expanded="false" class="collapsed">' . $ModuleName . '</a>
                            </h5>
                        </div>
                        <div id="' . $ModuleId . '" class="panel-collapse collapse in" aria-expanded="true">
                            <div class="panel-body">';
            $arrayiter = new RecursiveArrayIterator($map);
            $iteriter = new RecursiveIteratorIterator($arrayiter);
            foreach ($iteriter as $value) {
                if (!isset($MapDetail[$value])) {
                    continue;
                } else {
                    $valueDetail = $MapDetail[$value];
                }
                $url = isset($valueDetail['urlC']) && !empty($valueDetail['urlC']) ? $valueDetail['urlC'] : $this->createUrl($valueDetail['url']);
                $html .= ' <a type="button" href="' . $url . '" class="btn btn-w-m btn-link">' . $valueDetail['name'] . '</a>';
            }
            $html .= '    </div>
                        </div>
                     </div>';
        }
        echo $html;
    }

    public function loadModel($id)
    {
        $model = Log::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function handleMap()
    {
        $blackOrWhite = self::$blackOrWhite;
        $groupMap = self::$groupMap;
        $classLast = self::$config['treeMap'];
        //闭包递归处理
        $recursion = function ($classLast) use ($groupMap, $blackOrWhite, &$recursion) {
            $classLast = array_map(function (&$value) use ($groupMap, $blackOrWhite, $recursion) {
                if (!is_array($value)) {
                    if (($blackOrWhite == 1 && in_array($value, $groupMap)) || ($blackOrWhite == 2 && !in_array($value, $groupMap))) {
                        return false;
                    }
                    return $value;
                } else {
                    return $recursion($value);
                }
            }, $classLast);
            return array_filter($classLast);
        };
        return $recursion($classLast);
    }
}