<?php

/**
 * 获取演出Open Api Oauth Client端封装.
 *
 * @author XuRongYi<xurongyi@wepiao.com>
 * @date 2015-04-29
 * @update 该sdk有修改：原本的sdk内部集成了memcache的配置获取，先修改为外部参数传入（最优的办法，其实是Di注入）
 */
class showOauthClient
{
    /**
     * movie app id on show open api platform.
     */
    protected static $appId     = 'ff8080814a76ce16014a7a4fcbec0023';

    /**
     * movie app secret key on show open api platform.
     */
    protected static $appSecret = '2d422d41-1b38-481e-bc16-43e9f9ad20e9';

    /**
     * current openapi access token.
     */
    protected static $curToken  = '';

    /**
     * wether is debug model.
     */
    protected static $isDebug   = false;

    /**
     * access token expired seconds.
     */
    protected static $tokenExpiredIn    = 3600;

    /**
     * default object cache
     */
    protected static $instance_ = null;

    /**
     * memcache object.
     */
    protected static $memcache;

    /**
     * get a self single object.
     *
     * @params boolean $isDebug Is debug model.
     *
     * @return static
     */
    public static function Instance($isDebug = false,$memConfig=array())
    {
        //if ($isDebug) { static::$isDebug = true; }

        static::$isDebug = $isDebug;

//        static::$memcache   = new Memcache();
//        static::$memcache->connect($memConfig['host'], $memConfig['port']);

        $class = get_called_class();
        if (!static::$instance_) {
            static::$instance_ = new $class;
            $token  = static::$instance_->getToken();

            if ($token) {
                static::$curToken   = $token;
            } else {
                static::$instance_->initAccessToken();
            }
        }

        return static::$instance_;
    }

    public function __destruct()
    {
        if (static::$memcache) {
            static::$memcache->close();
        }
    }

    /**
     * get show interface data.
     * @update 该方法有修改，不走memcache缓存。
     */
    public function call($interfaceUrl, $params)
    {
        /*$key    = 'show_' . md5($interfaceUrl, http_build_query($params));
        $data   = static::$memcache->get($key);
        if ($data) {
            return $data;
        }*/
        
        $uri    = $this->getInterfaceUri() . 'rest/' . static::$curToken . '/' . $interfaceUrl;
        $postData   = $params ? array('data' => $params) : array();
        if (!$postData) {
            $uri .= '/{}';
        }

        $data   = $this->callRemoteData($uri, $postData);
        /*if ($data) {
            static::$memcache->add($key, static::$curToken, MEMCACHE_COMPRESSED, static::$tokenExpiredIn);
        }*/

        return $data;
    } 

    /**
     * get interface uri by ENV.
     */
    public function getInterfaceUri()
    {
        if (static::$isDebug == 1) {
            return 'http://test.show.wepiao.com/api/';
        } elseif(static::$isDebug == 2) {
            return 'http://web.show.wepiao.com/api/';
        }elseif(static::$isDebug == 3) {
            return 'http://www.wepiao.com/api/';
        }
    }

    /**
     * get basic author infomation.
     */
    private static function getAuthorInfo()
    {
        $curTimeStamp   = time();
        $author         = static::$appId . '-' . $curTimeStamp;
        $authorPwd      = md5($curTimeStamp . static::$appSecret);

        return $author . ':' . $authorPwd;
    }

    /**
     * get access token.
     */
    public function getToken()
    {
        return static::$curToken;
    }

    /**
     * init access token.
     */
    public function initAccessToken()
    {
        $key    = 'show_access_token';

/*
        $token  = static::$memcache->get($key);
        if ($token) {
            static::$curToken   = $token;
            return $token;
        }
*/
 
        $uri    = $this->getInterfaceUri() . 'auth';
        $access = $this->callRemoteData($uri, array(), self::getAuthorInfo());

        $token  = '';
        if (isset($access['data']['access_token'])) {
            $token  = $access['data']['access_token'];

            //static::$memcache->add($key, $token, MEMCACHE_COMPRESSED, static::$tokenExpiredIn);

            static::$curToken   = $token;
        }

        return $token;
    }

    /**
     * call remote api date.
     * @update 该方法有修改，增加httpcode是否为200的验证，非200的响应code以及返回内容有误，均返回空数组
     */
    private function callRemoteData($apiUrl, array $data = array(), $basicUserPwd = null)
    {
        $ch = curl_init();
        if ($basicUserPwd) {
            curl_setopt($ch, CURLOPT_USERPWD, $basicUserPwd);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $result = curl_exec($ch);
        $iHttpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $result = json_decode($result, true);
        curl_close($ch);
        return $result;
        
        $return = [];
        if ($iHttpCode == '200') {
            if (isset($result['code']) && $result['code'] == '200') {
                $return['data'] = $result['data'];
                $return['ret']  = '0';
                $return['sub']  = '0';
                $return['msg']  = 'success';
            }else{
                $return['data'] = $result['data'];
                $return['ret']  = '-1';
                $return['sub']  = '-1';
                $return['msg']  = !empty($result['message']) ? $result['message'] : '';
            }
        }
        return $return;
    }

}


/*
$obj    = Vendor_showOauthClient::Instance(2); 
// pageNum%5D=1&data%5BpageSize%5D=10&data%5Bstatus%5D=-1&data%5BunionId%5D=owJ__t0k-UygQX3lJPo_Ma0WuKmI
$params = array(
    'pageNum' => 1,
    'pageSize'=> 10,
    'status'  => -1,
    'unionId' => 'owJ__t0k-UygQX3lJPo_Ma0WuKmI'
);
$data   = $obj->call('Order/Order/getUserOrderList', $params);
die(var_dump($data));
*/
