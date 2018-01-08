<?php

/**
 * BayMax主页模块列表
 *
 * @author CHAIYUE
 * @version 2016-12-08
 */
class HeadRbac extends CWidget
{
    public $moduleObj = null;
    public $parentIndex = null;
    private static $config = null;
    private static $groupMap = null;
    private static $blackOrWhite = null;//1黑2白

    /**
     * 入口配置初始化
     */
    public function run()
    {
        $config = require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'homePageModular.php');
        //获取头部
        if (!self::$config) {
            self::$config = $config;
        }
        //获取用户配置信息
        if (!isset(self::$groupMap)) {
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
                //用户过滤
                $moduleId = $this->moduleObj->id;
                $actionId = $this->moduleObj->getAction()->getId();
                if (((!in_array($moduleId . '2', self::$groupMap) && !in_array($moduleId, self::$groupMap) && self::$blackOrWhite == 2)) || (in_array($moduleId . '2', self::$groupMap) && in_array($moduleId, self::$groupMap) && self::$blackOrWhite == 1)) {
                    if (!in_array($moduleId, ['site', 'default']) && !($moduleId == 'user' && $actionId == 'profile')) {
                        throw new CHttpException(401, '该模块暂未授权,请联系超级管理员');
                    }
                }
            }
        }
        if (!$this->parentIndex) {
            $MainList = array_map(function ($val) use ($config) {
                return array_merge(['index' => $val], self::$config['detail'][$val]);
            }, array_keys(self::$config['treeMap']));
            echo $this->CreateHead($MainList);
        } else {
            echo $this->CreateLeft();
        }
    }

    /**
     * 生成顶部
     * @param $MainList
     * @return string
     */
    private function CreateHead($MainList)
    {
        $html = '<div class="navbar-buttons navbar-header pull-left" role="navigation">';
        $html .= '<ul class="nav ace-nav">';
        foreach ($MainList as $value) {
            $active = (isset($this->moduleObj->module) && $this->moduleObj->module->id == $value['index']) || (!isset($this->moduleObj->module) && '/' == $value['index']) ? 'active' : '';
            $url = $this->moduleObj->createUrl($value['url']);
            $html .= '<li class="mod-nav grey ' . $active . '">
                        <a class="global" href="' . $url . '">' . $value['name'] . '</a>
                      </li>';
        }
        $html .= '</ul></div>';
        return $html;
    }

    /**
     * 生成左部
     * @return string
     */
    public function CreateLeft()
    {
        $classLast = isset(self::$config['treeMap'][$this->parentIndex]) ? self::$config['treeMap'][$this->parentIndex] : [];
        $classDetail = self::$config['detail'];
        $html = '';
        $createLi = function ($value) use ($classDetail) {
            $detail = $classDetail[$value];
            $value = isset($detail['index']) && !empty($detail['index']) ? $detail['index'] : $value;
            if (isset($detail['active']) && !empty($detail['active'])) {
                $active = $this->moduleObj->getAction()->getId() == $detail['active']['action'] && $this->moduleObj->id == $detail['active']['class'] ? 'active open' : '';
            } else if (isset($detail['also'])) {
                $active = $this->moduleObj->id == $value && $detail['also'] ? 'active open' : '';
            } else {
                $active = $this->moduleObj->id == $value ? 'active open' : '';
            }
            $url = isset($detail['urlC']) && !empty($detail['urlC']) ? $detail['urlC'] : $this->moduleObj->createUrl($detail['url']);
            $gly = isset($detail['gly']) && !empty($detail['gly']) ? $detail['gly'] : 'fa fa-caret-right';
            $html =
                '<li class="' . $active . '">
                    <a href="' . $url . '">
                        <i class="menu-icon ' . $gly . '"></i>
                        <span class="menu-text"> ' . $detail['name'] . ' </span>
                    </a>
                </li>';
            return $html;
        };
        foreach ($classLast as $key => $value) {
            if (is_array($value)) {
                $detail = $classDetail[$key];
                $gly = isset($detail['gly']) && !empty($detail['gly']) ? $detail['gly'] : 'glyphicon glyphicon-picture';
                $active = in_array($this->moduleObj->id, $value) ? 'active open' : '';
                $html .=
                    '<li class="' . $active . '">
                          <a href="javascript:;" class="dropdown-toggle">
                              <i class="menu-icon ' . $gly . '"></i>
                              <span class="menu-text"> ' . $detail['name'] . '</span>
                              <b class="arrow fa fa-angle-down"></b>
                          </a>
                          <b class="arrow"></b>
                          <ul class="submenu">';
                foreach ($value as $index) {
                    $html .= $createLi($index);
                }
                $html .= '</ul></li>';
            } else {
                $html .= $createLi($value);
            }
        }
        return $html;
    }

    /**
     * 用户过滤
     * @return mixed
     */
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