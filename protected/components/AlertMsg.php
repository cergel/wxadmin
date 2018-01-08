<?php

/**
 * alert输出trait
 *
 * @author CHAIYUE
 * @version 2016-10-26
 */
trait AlertMsg
{
    /**
     * 输出json
     * @param $code
     * @param string $msg
     * @param array $data
     */
    public function json_alert($code, $msg = '', $data = [])
    {
        echo json_encode(['code' => (int)$code, 'msg' => $msg, 'data' => $data]);
        exit();
    }

    /**
     * 返回结果
     * @param $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    public function alert_info($code, $msg = '', $data = [])
    {
        return ['code' => (int)$code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * 递归过滤null
     * @param array $data
     * @return array
     */
    private function null2empty($data = [])
    {
        return $data;
    }
}