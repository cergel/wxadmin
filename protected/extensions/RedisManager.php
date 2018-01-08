<?php

/**
 * Date: 14-9-28
 * Time: 上午10:32
 */
class RedisManager
{
    public $ip;
    public $port;

    private $iTimeOut = 10;
    private $strPrefix = '';
    private $Redis = null;
    private $strRedisPwd = '';

    private static $arrSelfObj = array();

    //
    private function __construct($arrOptions = array(), $iPconn = 0)
    {
        if (!isset($arrOptions['host']) || !isset($arrOptions['port'])) {
            throw new Exception('Redis config error!');
        }
        $this->ip = $arrOptions['host'];
        $this->port = $arrOptions['port'];
        $this->iTimeOut = isset($arrOptions['timeout']) ? $arrOptions['timeout'] : $this->iTimeOut;
        $this->strPrefix = isset($arrOptions['prefix']) ? $arrOptions['prefix'] : $this->strPrefix;
        $this->strRedisPwd = isset($arrOptions['password']) ? $arrOptions['password'] : $this->strRedisPwd;
        $this->database = isset($arrOptions['database']) ? $arrOptions['database'] : 0;
//        var_dump($arrOptions);die;
        return $this->connect($iPconn);
    }

    public static function getInstance($arrOptions = array())
    {
        $index = md5(json_encode($arrOptions));
        if (empty(self::$arrSelfObj[$index])) {
            self::$arrSelfObj[$index] = new self($arrOptions);
        }
        return self::$arrSelfObj[$index];
    }

    public function connect($iPconn = 0)
    {
        if (empty($this->Redis)) {
            $this->Redis = new Redis();
            if ($iPconn) {
                $mixRet = $this->Redis->pconnect($this->ip, $this->port, $this->iTimeOut);
            } else {
                $mixRet = $this->Redis->connect($this->ip, $this->port, $this->iTimeOut);
            }
            if (!$mixRet) {
                throw new Exception("Redis server can not connect!");
            }
            if ($this->strRedisPwd) {
                $this->Redis->auth($this->strRedisPwd);
            }
            if ($this->strPrefix) {
                $this->Redis->setOption(Redis::OPT_PREFIX, $this->strPrefix);
            }
            if (isset($this->database)) {
                $this->Redis->select($this->database);
            }
        }
        return $this->Redis;

    }

    public static function close()
    {
        self::$selfObj = null;
    }

    public function listPush($strKey, $mixValue, $strType = 'L')
    {
        if ($strType == 'R') {
            return $this->Redis->rPush($strKey, $mixValue);
        } else {
            return $this->Redis->lPush($strKey, $mixValue);
        }

    }

    public function listPop($strKey, $strType = 'R')
    {
        if ($strType == 'L') {
            return $this->Redis->lPop($strKey);
        } else {
            return $this->Redis->rPop($strKey);
        }
    }

    public function listFindAll($strKey)
    {
        return $this->Redis->lRange($strKey, 0, -1);
    }

    public function setsAdd($strKey, $mixValue)
    {
        return $this->Redis->sAdd($strKey, $mixValue);
    }

    public function setsFindAll($strKey)
    {
        return $this->Redis->sMembers($strKey);
    }

    public function setsDel($strKey, $mixValue)
    {
        return $this->Redis->sRem($strKey, $mixValue);
    }

    public function hashKeys($strHashKey)
    {
        return $this->Redis->hKeys($strHashKey);
    }

    public function hashMset($strHashKey, $arrHashKeyValue)
    {
        return $this->Redis->hMset($strHashKey, $arrHashKeyValue);
    }

    public function hashGet($strKey, $mixValue)
    {
        return $this->Redis->hGet($strKey, $mixValue);
    }

    public function hashGetBatch($strKey, $arrValue)
    {
        return $this->Redis->hMGet($strKey, $arrValue);
    }

    public function hashExists($strKey, $mixValue)
    {
        return $this->Redis->hExists($strKey, $mixValue);
    }

    public function hashFindAll($strKey)
    {
        return $this->Redis->hGetAll($strKey);
    }

    public function hashDel($strKey, $mixValue)
    {
        return $this->Redis->hDel($strKey, $mixValue);
    }

    public function del($strKey)
    {
        return $this->Redis->delete($strKey);
    }

    public function listLength($key)
    {
        return $this->Redis->lLen($key);
    }

    public function setObjectInfo($key, $hashkey, $value)
    {
        return $this->Redis->hSet($key, $hashkey, $value);
    }

    public function getObjectInfo($key)
    {
        return $this->Redis->hGetAll($key);
    }

    public function getHashInfo($key, $hashkey)
    {
        return $this->Redis->hGet($key, $hashkey);
    }

    public function expire($strKey, $iSecond)
    {
        return $this->Redis->expire($strKey, $iSecond);
    }

    public function set($strKey, $strValue)
    {
        return $this->Redis->set($strKey, $strValue);
    }

    public function get($strKey)
    {
        return $this->Redis->get($strKey);
    }

    public function keys($pattern)
    {
        return $this->Redis->keys($pattern);
    }

    public function zRange($key, $start = 0, $end = -1)
    {
        return $this->Redis->ZRANGE($key, $start, $end);
    }

    public function zRem($key, $strValue)
    {
        return $this->Redis->zRem($key, $strValue);
    }

    public function zAdd($key, $score, $value)
    {
        return $this->Redis->zAdd($key, $score, $value);
    }

    public function zRevRange($key, $start = 0, $end = -1, $withScores = null)
    {
        return $this->Redis->zRevRange($key, $start, $end, $withScores);
    }

    public function exists($key)
    {
        return $this->Redis->exists($key);
    }

    public function sCard($key)
    {
        return $this->Redis->sCard($key);
    }
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->Redis,$name],$arguments);
    }
}