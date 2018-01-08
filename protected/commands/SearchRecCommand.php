<?php

class SearchRecCommand extends CConsoleCommand
{
    private $id = [1, 2, 3];

    public function run($args)
    {
        $url = 'http://10.3.10.225:2004/search/integrate/aggs';
        $res = Https::getUrl([], $url);
        if (!$res) {
            return false;
        }
        $ranking = json_decode($res, true);
        $rankingArr = $ranking['data'];
        $rankingList = [];
        array_map(function ($val) use (&$rankingList) {
            $key = key($val);
            $val = current($val);
            $rankingList[$key] = $val;
        }, $rankingArr);
        $SearchRecObj = new SearchRec();
        $SearchRecObj->init();
        //刷新所有的排名
        foreach ($this->id as $id) {
            $lists = $SearchRecObj->getList($id);
            if (!is_array($lists) || empty($lists))
                continue;
            foreach ($lists as $list) {
                $counterId = $list['id'];
                $score = 0;
                $info = json_decode($list['data'], true)['data'];
                if (empty($info) || !is_array($info))
                    continue;
                foreach ($info as $value) {
                    if (isset($rankingList[$value])) {
                        $score += $rankingList[$value];
                    }
                }
                $SearchRecObj->setScore($id, $counterId, $score);
            }
        }
        //写入排名
        $re = $SearchRecObj->setStr();
    }
}