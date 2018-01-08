<?php

class Service
{
    public static function getOperName($code) {
        switch ($code) {
            case 1000:
                return '创建未接入会话';
            case 1001:
                return '接入会话';
            case 1002:
                return '主动发起会话';
            case 1004:
                return '关闭会话';
            case 1005:
                return '抢接会话';
            case 2001:
                return '公众号收到消息';
            case 2002:
                return '客服发送消息';
            case 2003:
                return '客服收到消息';
        }
    }

    /**
     * 生成Excel
     * @param $records
     * @param $starttime
     * @throws CException
     * @throws PHPExcel_Exception
     */
    public static function generateExcel($records, $starttime) {
        spl_autoload_unregister(array('YiiBase','autoload'));
        Yii::import('ext.PHPExcel.PHPExcel', true);
        spl_autoload_register(array('YiiBase','autoload'));

        $fileName = '客服记录-'.date('Y-m-d', $starttime);

        $PHPExcel = new PHPExcel();

        //设置基本信息
        $PHPExcel->getProperties()->setCreator("baymax")
            ->setLastModifiedBy("baymax")
            ->setTitle("北京微影时代科技有限公司")
            ->setSubject("客服记录")
            ->setDescription("")
            ->setKeywords("客服记录")
            ->setCategory("");

        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle($fileName);

        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(100);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

        $PHPExcel->getActiveSheet()->setCellValue('A1', '时间');
        $PHPExcel->getActiveSheet()->setCellValue('B1', 'openid');
        $PHPExcel->getActiveSheet()->setCellValue('C1', '内容');
        $PHPExcel->getActiveSheet()->setCellValue('D1', '状态');
        $PHPExcel->getActiveSheet()->setCellValue('E1', '客服');

        foreach ($records as $key => $record) {
            $rowIndex = $key + 2;
            $PHPExcel->getActiveSheet()->setCellValue('A'.$rowIndex, date('Y/m/d H:i:s', $record['time']));
            $PHPExcel->getActiveSheet()->setCellValue('B'.$rowIndex, $record['openid']);
            $PHPExcel->getActiveSheet()->setCellValue('C'.$rowIndex, mb_convert_encoding(mb_convert_encoding($record['text'],'GB2312', 'UTF-8'),'UTF-8','GB2312')); // 处理乱码
            $PHPExcel->getActiveSheet()->setCellValue('D'.$rowIndex, self::getOperName($record['opercode']));
            $PHPExcel->getActiveSheet()->setCellValue('E'.$rowIndex, $record['worker']);
        }

        //保存为2003格式
        $objWriter = new PHPExcel_Writer_Excel5($PHPExcel);

        if (!file_exists(Yii::app()->basePath . '/runtime/weixin_service'))
            mkdir(Yii::app()->basePath . '/runtime/weixin_service', 0777, true);
        $objWriter->save(Yii::app()->basePath . '/runtime/weixin_service/' . date('Y-m-d', $starttime) . '.xls');
    }

    /**
     * 取得AccessToken
     * @return string
     */
    public static function getAccessToken () {
        $accessToken = '';
        $retryTimes  = 0;
        if (!$accessToken && $retryTimes<3) {
            $content = file_get_contents(sprintf(
                Yii::app()->params['wechat']['access_token_url'],
                Yii::app()->params['wechat']['app_id'],
                Yii::app()->params['wechat']['app_secret']
            ));
            if ($content) {
                $result = json_decode($content, true);
                if ($result['ret'] == '0') {
                    $accessToken = $result['data'];
                }
                $retryTimes++;
            }
        }
        return $accessToken;
    }

    /**
     * 从微信接口返回全部内容
     * @param $starttime
     * @param $endtime
     * @return array
     */
    public static function getRecords($starttime, $endtime) {
        set_time_limit(0);
        $accessToken = self::getAccessToken();
        if (!$accessToken)
            return false;
        $records = [];
        $pageindex = 1;
        $retryTimes = 0;

        while(true) {
            $URL = "https://api.weixin.qq.com/customservice/msgrecord/getrecord?access_token=";
            $data = array(
                "starttime" => $starttime,
                "endtime"   => $endtime,
                "pageindex" => $pageindex,
                "pagesize"  => 50,
            );
            $ch = curl_init($URL.$accessToken);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/javascript'));
            $output = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($output, true);
            // 如果accesstoken失效重新获取
            if (isset($result['errcode']) && $result['errcode'] == 4001) {
                $accessToken = self::getAccessToken();
                if (!$accessToken)
                    return false;
                continue;
            }
            // 最后一页结束循环
            if (isset($result['recordlist']) && !$result['recordlist'])
                break;
            if (isset($result['recordlist']))
                $records = array_merge($records, $result['recordlist']);

            // 重试或者下一页
            if (isset($result['recordlist']))
                $pageindex++;
            else
                $retryTimes++;

            if ($retryTimes > 100)
                return false;
        }
        return $records;
    }
}
