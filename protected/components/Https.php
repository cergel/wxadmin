<?php

/**
 * @tutorial post、get请求的文件
 */
class Https
{
    /**
     * @tutorial 接口获取资源
     * @author liulong
     * @param unknown $arrPostData
     * @param unknown $url
     * @return mixed|Ambigous <mixed, string>
     */
    public static function getApp($arrPostData, $url)
    {
        //统一拼凑必传字段
        $arrPostData['appkey'] = empty($arrPostData['appkey']) ? '10' : $arrPostData['appkey'];
        $arrPostData['v'] = empty($arrPostData['v']) ? '2016050901' : $arrPostData['v'];
        $arrPostData['from'] = empty($arrPostData['from']) ? '7000100003' : $arrPostData['from'];
        $arrPostData['t'] = empty($arrPostData['t']) ? time() : $arrPostData['t'];
        $arrPostData['platForm'] = empty($arrPostData['platForm']) ? '1' : $arrPostData['platForm'];
        //生成sign签名
        ksort($arrPostData);
        $strKey = urldecode(http_build_query($arrPostData));
        $sign = '';
        //现在都是用的渠道为10的
        if ($arrPostData['appkey'] == 10) {
            $sign = 'jsIa9jL10Vxa9HMlEb9E4Fa15f';
        }
        $strMd5 = MD5($sign . $strKey);
        $arrPostData['sign'] = strtoupper($strMd5);
        $data = self::getPost($arrPostData, $url);
        $data = json_decode($data, true);
        return $data;
    }

    /**
     * post 请求数据
     * @param unknown $arrPostData
     * @param unknown $url
     * @return mixed
     */
    public static function getPost($arrPostData, $url, $isJson = FALSE, $isRequest = FALSE, $build = true)
    {
        //$header =['X-Request-Id: 1','charset=utf-8','Content-Type: application/json'];
        $header = [];
        if ($isRequest)
            $header = ['X-Request-Id: 10', 'charset=utf-8'];
        if ($isJson) {
            $header[] = 'Content-Type: application/json';
            $arrPostData = json_encode($arrPostData);
        } else {
            if ($build)
                $arrPostData = http_build_query($arrPostData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

//        if (!class_exists('\CURLFile') && defined('CURLOPT_SAFE_UPLOAD')) {
//            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, FALSE);
//        }
        if (class_exists('\CURLFile', false)) {
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            }
        }

        curl_setopt($ch, CURLOPT_POST, true);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostData);
        if (!empty($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (class_exists('ApiLog')) {
            ApiLog::addLog($url, ['data' => $arrPostData, 'header' => $header], ['res' => $data, 'code' => $httpCode], 'post');

        }
        return $data;
    }

    /**
     * get 请求数据
     * @param unknown $arrPostData
     * @param unknown $url
     * @return mixed
     */
    public static function getUrl($arrPostData = '', $url)
    {
        if (empty($url))
            return false;
        if (!empty($arrPostData))
            $url .= "?" . http_build_query($arrPostData);
        $data = file_get_contents($url);
        if (class_exists('ApiLog')) {
            ApiLog::addLog($url, $arrPostData, $data, 'get');
        }
        return $data;
    }

    /**
     * 根据cinemaID获取cinema的内容
     * @param $cinemaId
     */
    public static function getCinemaInfoByCinemaId($cinemaId)
    {
        $strContent = file_get_contents("http://commoncgi.wepiao.com/data/v5/cinemas/100/info_cinema_$cinemaId.json");
        $cinema = [];
        if (preg_match("/^MovieData\.set\(\"\w+\",(.*)\);/ius", $strContent, $arr)) {
            $cinema = json_decode($arr[1], 1);
        }
        if (!empty($cinema['info'])) {
            $cinema = $cinema['info'];
        } else {
            $cinema = isset($cinema['id']) ? $cinema : [];
        }
        return $cinema;
    }

    public static function curlGetPost($url = "", $method = "GET", $data = array())
    {
        $query = array();
        $curl = curl_init();

        foreach ($data as $k => $v) {
            $query[] = $k . '=' . urldecode($v);
        }
        if (strtoupper($method) == 'GET' && $data) {
            $url .= '?' . implode('&', $query);
        } elseif (strtoupper($method) == 'POST' && $data) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&', $query));
        }

//            //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
//            //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 50);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if (class_exists('ApiLog')) {
            ApiLog::addLog($url, ['data' => $data, 'header' => []], ['res' => $output, 'code' => $httpCode], $method);
        }
        return $output;
    }

    /**
     * 获取所以城市列表
     */
    public static function getCityList()
    {
        $url = Yii::app()->params['om_base_url']['city_list'];
        $res = self::curlGetPost($url);
        $res = json_decode($res, 1);
        return !empty($res['data']) ? $res['data'] : [];
    }

    /**
     * 获取所以城市列表
     */
    public static function getCinemaList()
    {
        $url = Yii::app()->params['om_base_url']['cinema_list'] . '?status=0';
        $res = self::curlGetPost($url);
        $res = json_decode($res, 1);
        return !empty($res['data']) ? $res['data'] : [];
    }


}