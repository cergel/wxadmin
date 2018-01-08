<?php
Yii::import('ext.RedisManager', true);

class SearchRec
{
    use AlertMsg;
    private $redis;
    private $rec_name = [1 => 'movie', 2 => 'cinema', 3 => 'actor'];
    //主键list
    private $list_key = 'Search_Rec_List:';
    //计数器string
    private $counter_key = 'Search_Rec_Counter:';
    //排序string
    private $score_key = 'Search_Rec_Score:';
    //数据存储string
    private $data_key = 'Search_Rec_Data:';
    //字段名称
    private $key_name = ['id', 'score', 'data'];
    //当前推荐设置
    public $index_key = 'Search_Rec_set:';
    //防止重复
    public $no_repeat_key = 'Search_Rec_Repeat_Hash';
    //外部用
    public $str_key = 'search_rec';
    private static $data = [];
    private $keys = [];

    public function init()
    {
        $this->setRedis();
    }

    /**
     * redis初始化逻辑
     */
    public function setRedis()
    {
        //初始化redis逻辑
        $config = Yii::app()->params->redis_data['pee']['write'];
        $this->redis = RedisManager::getInstance($config);
    }

    public function getStr()
    {
        $str_key = $this->str_key;
        $searchRecData = $this->redis->get($str_key);
        if (empty($searchRecData)) {
            return false;
        }
        $searchRecData = json_decode($searchRecData, true);
        foreach ($searchRecData as $key => &$value) {
            foreach ($value as &$name) {
                $redis_index_key = $this->index_key . $key;
                $score = $this->redis->ZSCORE($redis_index_key, $name);
                $score = $score ? $score : 10;
                $name = ['name' => $name, 'score' => $score];
            }
        }
        return $searchRecData;
    }

    /**
     * 写入外部调用
     */
    public function setStr()
    {
        $data = $this->getMainIndex();
        foreach ($data as &$value) {
            $value = array_map(function ($val) {
                return $val['name'];
            }, $value);
        }
        $str = json_encode($data);
        $this->redis->set($this->str_key, $str);
        return $str;
    }

    public function saveStr($id, $data)
    {
        $rec_name = $this->rec_name;
        $index = $rec_name[$id];
        $str_key = $this->str_key;
        $searchRecData = $this->redis->get($str_key);
        $searchRecData = json_decode($searchRecData, true);
        $searchRecData[$index] = $data;
        $this->redis->set($str_key, json_encode($searchRecData));
    }

    /**
     * 获取当前全部关键词列表
     * @return array
     */
    public function getMainIndex()
    {
        if (self::$data) {
            return self::$data;
        }
        $rec_name = $this->rec_name;
        $data = [];
        foreach ($rec_name as $key => $value) {
            $list = $this->getList($key, [0, 3]);
            $redis_index_key = $this->index_key . $value;
            if (is_array($list) && !empty($list)) {
                $info = [];
                foreach ($list as $k => $val) {
                    $field = json_decode($val['data'], true);
                    //获取自定义排序值
                    $score = $this->redis->ZSCORE($redis_index_key, $field['name']);
                    //根据sort进行排序
                    $score = $score ? $score : 10;
                    $index = serialize(['name' => $field['name'], 'score' => $score]);
                    $info[$index] = $score;
                }
                asort($info);
                $info = array_map(function ($e) {
                    return unserialize($e);
                }, array_keys($info));
                $data[$value] = $info;
            } else {
                $data[$value] = [];
            }
        }
        self::$data = $data;
        return $data;
    }

    /**
     * 设置当前关键字排序
     * @param $id
     * @param $field
     * @param $score
     * @return array
     */
    public function SetMainIndex($id, $field, $score)
    {
        $rec_name = $this->rec_name;
        $redis_index_key = $this->index_key . $rec_name[$id];
        $this->redis->ZADD($redis_index_key, $score, $field);
    }

    /**
     * 添加一条记录
     * @param $id
     * @param $field
     * @param $data
     * @param $score
     * @param int $counterId
     * @return array
     */
    public function addOne($id, $field, $data, $score, $counterId = 0)
    {
        //获取所需的KEY
        $rec_name = $this->rec_name;
        $redis_counter_key = $this->counter_key . $rec_name[$id];
        //data过滤
        $data_arr = explode('|', $data);
        $data = [];
        $SearchRecRe = [];
        if (!$this->keys) {
            $Lists = $this->getList($id);
            foreach ($Lists as $List) {
                if (!isset($List['data'])) {
                    continue;
                }
                $List = json_decode($List['data'], true)['data'];
                $this->keys = array_merge($this->keys, $List);
            }
        }
        $keys = $this->keys;
        if ($counterId) {
            $SearchRecRe = $this->getOne($id, $counterId);
            $SearchRecRe = isset($SearchRecRe['data']['data']) ? $SearchRecRe['data']['data'] : [];
        }
        foreach ($data_arr as $val) {
            $val = trim($val);
            if (in_array($val, $keys) && !in_array($val, $SearchRecRe)) {
                continue;
            }
            if (empty($val)) {
                continue;
            }
            $data[] = $val;
            $keys[] = $val;
            $this->keys = $keys;
        }
        if (empty($data)) {
            return false;
        }
        //获取主键id
        if (!$counterId)
            $counterId = $this->redis->Incr($redis_counter_key);
        $redis_list_key = $this->list_key . $rec_name[$id];
        $redis_score_key = $this->score_key . $rec_name[$id] . $counterId;
        $redis_data_key = $this->data_key . $rec_name[$id] . $counterId;
        //防止重复
        $redis_Hash_key = $this->no_repeat_key . $rec_name[$id];
        //插入一条记录
        $this->redis->Lrem($redis_list_key, $counterId, 1);
        $this->redis->Lpush($redis_list_key, $counterId);
        $this->redis->Set($redis_score_key, $score);
        $this->redis->Hset($redis_Hash_key, $field, $counterId);
        $data = ['name' => $field, 'data' => $data];
        $this->redis->Set($redis_data_key, json_encode($data));
        return ['counterId' => $counterId];
    }

    /**
     * 检查是否重复
     * @param $id
     * @param $field
     */
    public function inspectRepeat($id, $field)
    {
        //获取所需的KEY
        $rec_name = $this->rec_name;
        $redis_Hash_key = $this->no_repeat_key . $rec_name[$id];
        return $this->redis->Hget($redis_Hash_key, $field);
    }

    /**
     * 删除一条信息
     * @param $id
     * @param $counterId
     */
    public function delOne($id, $counterId)
    {
        //获取所需的KEY
        $rec_name = $this->rec_name;
        $redis_list_key = $this->list_key . $rec_name[$id];
        $redis_score_key = $this->score_key . $rec_name[$id] . $counterId;
        $redis_data_key = $this->data_key . $rec_name[$id] . $counterId;
        $detail = $this->getOne($id, $counterId);
        //防止重复
        $redis_Hash_key = $this->no_repeat_key . $rec_name[$id];
        $this->redis->Lrem($redis_list_key, $counterId, 1);
        $this->redis->Del($redis_score_key);
        $this->redis->Del($redis_data_key);
        if (isset($detail['data']['name'])) {
            $this->redis->Hdel($redis_Hash_key, $detail['data']['name'], 1);
        }
    }

    /**
     * 删除当前所有记录
     * @param $id
     * @return bool
     */
    public function flushAllRec($id)
    {
        $rec_name = $this->rec_name;
        $redis_list_key = $this->list_key . $rec_name[$id];
        //获取当前所有
        $list = $this->redis->Lrange($redis_list_key, 0, -1);
        if (empty($list)) {
            return true;
        }
        foreach ($list as $counterId) {
            $this->delOne($id, $counterId);
        }
        $this->redis->del($redis_list_key);
    }


    /**
     * 获取列表
     * @param $id
     * @param null $limit
     * @return array
     */
    public function getList($id, $limit = null)
    {
        $key_name = $this->key_name;
        $chunk_num = count($key_name);
        //获取所需的KEY
        $rec_name = $this->rec_name;
        $redis_counter_key = $this->counter_key . $rec_name[$id];
        //获取主键id
        $redis_list_key = $this->list_key . $rec_name[$id];
        $redis_score_key = 'pee_' . $this->score_key . $rec_name[$id] . '*';
        $redis_data_key = 'pee_' . $this->data_key . $rec_name[$id] . '*';
        $sort = ['BY' => $redis_score_key,
            'SORT' => 'DESC',
            'GET' => array('#', $redis_score_key, $redis_data_key)];
        if ($limit) {
            $sort['limit'] = $limit;
        }
        $data = $this->redis->sort($redis_list_key, $sort);
        if (empty($data)) {
            return [];
        }
        if ($chunk_num > 0) {
            $data = array_chunk($data, $chunk_num);
            if ($key_name && !empty($data)) {
                $data = array_map(function ($val) use ($key_name) {
                    $new_val = [];
                    foreach ($val as $key => $value) {
                        if ($value === false) {
                            return [];
                        }
                        $new_val[$key_name[$key]] = $value;
                    }
                    return $new_val;
                }, $data);
                $data = array_filter($data);
            }
        }
        return $data;
    }

    public function getTotal($id)
    {
        $rec_name = $this->rec_name;
        $redis_list_key = $this->list_key . $rec_name[$id];
        return $this->redis->LLEN($redis_list_key);
    }

    /**
     * 根据ID获取一条
     * @param $id
     * @param $counterId
     * @return array
     */
    public function getOne($id, $counterId)
    {
        $rec_name = $this->rec_name;
        $redis_score_key = $this->score_key . $rec_name[$id] . $counterId;
        $redis_data_key = $this->data_key . $rec_name[$id] . $counterId;
        $re = [];
        //获取排序
        $score = $this->redis->get($redis_score_key);
        if (!empty($score)) {
            $re['score'] = $score;
        }
        //获取data
        $data = $this->redis->get($redis_data_key);
        if (!empty($data)) {
            $re['data'] = json_decode($data, true);
        }
        return $re;
    }

    /**
     * 设置排序
     * @param $id
     * @param $counterId
     * @param $score
     */
    public function setScore($id, $counterId, $score)
    {
        $rec_name = $this->rec_name;
        $redis_score_key = $this->score_key . $rec_name[$id] . $counterId;
        $this->redis->Set($redis_score_key, $score);
    }
}