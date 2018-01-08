<?php
Yii::import('ext.RedisManager', true);

/**
 * Created by PhpStorm.
 * User: tanjunlin
 * Date: 2016/7/29
 * Time: 11:51
 */
class FlushCdn
{

    const FLUSH_CDN_KEY = "flush_cdn_data_";
    const FLUSH_CDN_KEY_TIME = 72000;
    const CDN_URL = "http://q.intra.wepiao.com/api/cdn/refresh?url=%s";
    const LIST_FLUSH_CDN_KEY = 'baymax_flush_cdn_data_list';
    static $redis;

    //redis初始化逻辑
    public static function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['cache']['write'];
        static::$redis = RedisManager::getInstance($config);
    }

    /**将拼接好的url放入redis集合中 ---  修改为将必要的文件放入CDN中
     * @param $localPath
     * @param $cdnUrl
     * 其中$cdnUrl为拼接好的cdn的url路径,可以是数组
     */
    public static function setUrlToRedis($cdnUrl,$type= 'push_cdn_user')
    {
        FlushCdn::setRedis();

        if(!is_array($cdnUrl)){
            $arrCdnUrl[] = $cdnUrl;
        }else{
            $arrCdnUrl = $cdnUrl;
        }
        foreach ($arrCdnUrl as $value) {
            $arrData = ['time'=>time()+100,'url'=>$value];
            static::$redis->lpush(self::LIST_FLUSH_CDN_KEY,json_encode($arrData));
//            static::$redis->setsAdd(self::FLUSH_CDN_KEY . intval(time() / 300), $value);
        }
        $info['params'] = $cdnUrl;
        $info['response'] = $cdnUrl;
        Log::model()->logFile($type, json_encode($info));
        static::$redis->expire(self::FLUSH_CDN_KEY, self::FLUSH_CDN_KEY_TIME);
    }

    /**
     * 从redis集合中读取url 并调用接口刷新
     */
    public static function getUrlFromRedis($time =0,$type='flush_cdn_shell')
    {
        $key = self::FLUSH_CDN_KEY . intval((time() - 300) / 300);
        self::_getRedisCdn($key,$type);
        if(!empty($time)){
            //处理现在的
            $key = self::FLUSH_CDN_KEY . intval(time() / 300);
            self::_getRedisCdn($key,$type);
        }
    }
    public static function _getRedisCdn($key,$type='flush_cdn_shell')
    {
        FlushCdn::setRedis();
        $flag = static::$redis->sCard($key);
        if ($flag) {
            $urls = static::$redis->setsFindAll($key);
            if($urls){
                $chunkArr = array_chunk($urls,70);//测试过，腾讯与接口最大支持了93条url，这里放70应该没问题
                foreach($chunkArr as $arrUrl){
                    $strRequestUrl = implode('\n',$arrUrl);
                    $strRequestUrl = sprintf(self::CDN_URL, $strRequestUrl);
                    $r=FlushCdn::retryHttp($strRequestUrl,$type);
                }
            }
        }
    }


    /**请求刷新cdn接口，如若刷新三次仍为失败返回false
     * @param $url
     * @return bool
     */
    public static function retryHttp($url,$type='flush_cdn_shell')
    {
        $type = empty($type)?'flush_cdn_shell':$type;
        $i = 0;
        $flag = false;
        while ($i < 3) {
            $ret = Https::getUrl('', $url);
            $ret = json_decode($ret, true);
            $info['url'] = $url;
            $info['response'] = $ret;
            $i++;
            if ($ret) {
                if ($ret["ret"] == 0) {
                    $flag = true;
                    Log::model()->logFile($type, json_encode($info));
                    break;
                }
            }
            sleep(1);
            Log::model()->logFile($type, 'error:' . json_encode($info));
        }
        return $flag;
    }

    /**遍历给定的本地路径文件，拼接上cdn地址
     * @param $localPath    本地路径
     * @param $cdnUrl       cdn地址
     * @param string $dir
     * @param array $finalUrl
     * @return array
     */
    public static function  readAllFile($cdnUrl, $localPath, $dir = "/", $finalUrl = [])
    {
        if (empty($localPath)) {
            $finalUrl[] = $cdnUrl;
        } else {
            $localDir = $localPath . $dir;
            //是否是文件夹
            if (is_dir($localDir)) {
                //打开目录句柄返回目录句柄的 resource
                if ($handle = opendir($localDir)) {
                    //从目录句柄中的 resource读取条目
                    //返回目录中下一个文件的文件名
                    while (($file = readdir($handle)) !== false) {
                        if ($file != "." && $file != "..") {
                            $localUrl = $localDir . $file;
                            if ((is_dir($localUrl))) {
                                $finalUrl = FlushCdn::readAllFile($cdnUrl, $localPath, $dir . $file . "/", $finalUrl);
                            } else {
                                $finalUrl[] = $cdnUrl . $dir . $file;
                            }
                        }
                    }
                }
            }
        }
        return $finalUrl;
    }
}