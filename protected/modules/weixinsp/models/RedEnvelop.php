<?php
Yii::import('ext.RedisManager', true);

class RedEnvelop
{
    use AlertMsg;

    public function save($data)
    {
        $config = Yii::app()->params->redis_data['pee']['write'];
        $redis = RedisManager::getInstance($config);
        if (empty($data)) {
            return $this->alert_info(1, '操作失败');
        }
        $redis->set('weixinsp_red_envelop', json_encode($data));
        return $this->alert_info(0, '保存成功');
    }

    public function getInfo()
    {
        $config = Yii::app()->params->redis_data['pee']['read'];
        $redis = RedisManager::getInstance($config);
        return $redis->get('weixinsp_red_envelop');
    }
}