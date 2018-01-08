<?php

/**
 *  用户中心的操作都在这
 * Class UCenter
 */
class UCenter
{

    /**
     * 根据手机号获取用户中心的openId
     * @param $mobileNo
     */
    public static function getOpenIdByMobileNo($mobileNo)
    {
        $arrData = ['mobileNo'=>$mobileNo];
        $url =Yii::app()->params['ucenter_api']['mobileNo_openid'];
        $arrData = Https::getPost($arrData,$url,true,true);
        $arrData =  json_decode($arrData,true);
        if(empty($arrData['sub']) && !empty($arrData['data'])){
            $arrData = $arrData['data'];
        }else{
            $arrData = [];
        }
        return $arrData;
    }

    /**
     * 获取指定分类下的openId
     * @param $mobileNo
     * @param $therId
     */
    public static function getWYOpenIdByMobileNo($mobileNo,$otherId)
    {
        $arrRet = [];
        $arrData = self::getOpenIdByMobileNo($mobileNo);
        if(!empty($arrData['openIdList'])){
            $arrData = $arrData['openIdList'];
            foreach($arrData as $val){
                if($otherId == $val['otherId']){
                    $arrRet[] = $val['openId'];
                }
            }
        }
        return $arrRet;
    }

    /**
     * 根据openId获取用户信息
     * @param $mobileNo
     */
    public static function getUserInfoByUcid($ucid)
    {
        $arrData = ['openId'=>$ucid];
        $url =Yii::app()->params['ucenter_api']['user_info'];
        $arrData = Https::getPost($arrData,$url,true,true);
        $arrData = json_decode($arrData,true);
        if(!empty($arrData['data']) && empty($arrData['sum'])){
            $arrData = $arrData['data'];
        }else{
            $arrData = [];
        }
        return $arrData;
    }

    /**
     * 添加标签
     * @param $arrData
     * @return mixed
     */
    public static function saveUserTagToUCenter($arrData)
    {
        $url =Yii::app()->params['ucenter_api']['user_tag_add'];
        $arrData = Https::getPost($arrData,$url,true,true);
        $arrData = json_decode($arrData,true);
        return $arrData;
    }



}