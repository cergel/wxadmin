<?php

/**
 * @tutorial 操作文件（包含图片）,腾讯云的
 * @author liulong
 */
class CosUpload
{
    const  BUCKTE_NANE = 'baymax';
    const  BUCKTE_URL = 'https://baymax-cos.wepiao.com';

    /**
     * 上传文件
     * @param $localPath
     * @param $urlPath
     * @param bool|TRUE $insertOnly
     */
    public static function upload($localPath, $cosPath, $insertOnly = false)
    {
        if (!is_file($localPath)) {
            return false;
        }
        $res = self::createFolder($cosPath);
        if ($res) {
            $arrData = [];
            $arrData['dstPath'] = $cosPath;
            $arrData['insertOnly'] = $insertOnly ? 1 : 0;
            $arrData['file'] = "@" . $localPath;
            $arrData = self::getBuckte($arrData);
            if (class_exists('\CURLFile', false)) {
                $arrData['file'] = new \CURLFile(realpath($localPath));
            } else {
                $arrData['file'] = "@" . realpath($localPath);
            }
            $url = Yii::app()->params->commoncgi['cos_upload'];
            $res = Https::getPost($arrData, $url, false, false, false);
            $res = json_decode($res, true);
            $arrData['request'] = $res;
            Log::model()->sysLog('Cos_Upload', json_encode($arrData));
            if (empty($res['code']) && !empty($res['data']['resource_path'])) {
                return self::BUCKTE_URL . $res['data']['resource_path'];
            }
        }
        return false;
    }

    /**
     * 创建路径
     * @param $url
     * @return bool|int
     */
    public static function createFolder($url)
    {
        $url = dirname($url);
        $res = self::statFolder($url);
        if (isset($res['code']) && empty($res['code'])) {
            return true;
        } else {
            $arrData = [];
            $arrData['path'] = $url;
            $arrData = self::getBuckte($arrData);
            $url = Yii::app()->params->commoncgi['cos_reateFolder'];
            $arrData = Https::getPost($arrData, $url);
            $arrData = json_decode($arrData, true);
            if (isset($arrData['code']) && empty($arrData['code'])) {
                return true;
            } else return false;
        }
    }

    /**
     * 判断路径是否存在
     * @param $url
     * @return array|mixed
     */
    public static function statFolder($url)
    {
        $url = dirname($url);
        $arrData = [];
        $arrData['path'] = $url;
        $arrData = self::getBuckte($arrData);
        $url = Yii::app()->params->commoncgi['cos_statFolder'];
        $arrData = Https::getPost($arrData, $url);
        $arrData = json_decode($arrData, true);
        return $arrData;
    }

    /**
     * 获取bucketName
     * @param $arrData
     * @return mixed
     */
    private static function getBuckte($arrData)
    {
        $arrData['bucketName'] = self::BUCKTE_NANE;
        return $arrData;
    }


}
