<?php

/**
 * 媒资库评论注水脚本
 */
class MsdbCommand extends CConsoleCommand
{

    const CHANNEL_ID = 28;
    /**
     * 导入评分注水数据--批量导入，一次性完成
     */
    public function actionSaveMovieScore()
    {
        $url =dirname(__FILE__).'/../../files/movie.xlsx';
        $res = $this->format_excel2array($url);
        foreach($res as $key=> &$val){
            if($key <2)continue;
            $movie = Movie::model()->findByPk($val['A']);
            if(empty($movie)){
               $this->addMovie($val);
            }elseif(empty($movie->scoreFillNum0) && empty($movie->scoreFillNum20) && empty($movie->scoreFillNum40) && empty($movie->scoreFillNum60) && empty($movie->scoreFillNum80) && empty($movie->scoreFillNum100)){
                $movie->movieName = $val['B'];
                $movie->scoreFillNum0 = $val['C'];
                $movie->scoreFillNum20 = $val['D'];
                $movie->scoreFillNum40 = $val['E'];
                $movie->scoreFillNum60 = $val['F'];
                $movie->scoreFillNum80 = $val['G'];
                $movie->scoreFillNum100 = $val['H'];
                $movie->save();
            }
            $val = false;
        }
        echo count($res)."\n";
        exit;
    }

    /**
     * 批量注水看过--调用真实的接口
     */
    public function actionSaveMovieSeen()
    {$time = time();
        $url =dirname(__FILE__).'/../../files/seens.xlsx';
        $res = $this->format_excel2array($url);
        $arrUser = [];
        foreach($res as $key=>$val){
            if($key <3)continue;
            for($i=1;$i<=$val['C'];$i++){
                if($time + 3000 <= time()){
                    $arrUser = [];
                    $time = time();
                }
                if(empty($arrUser[$i])){
                    $arrData = ['channelId'=>self::CHANNEL_ID,'str'=>$i.'_base_weiying'];
                    $arrData = Https::getPost($arrData,Yii::app()->params['commoncgi']['encrypt_token']);
                    $arrData = json_decode($arrData,true);
                    if(!empty($arrData['data']['encryptStr'])){
                        $arrData =$arrData['data']['encryptStr'];
                        $arrUser[$i] = $arrData;
                    }else{
                        continue;
                    }
                }
                $this->seenMovie($arrUser[$i],$val['A']);
            }
            echo $val['A']." count {$val['C']} \n";
        }
    }

    /**
     * 看过接口调动
     * @param $token
     * @param $movieId
     */
    private function seenMovie($token,$movieId)
    {
        $url = Yii::app()->params['comment']['movie_seen'];
        $url = str_replace('{movieId}',$movieId,$url);
        $arrData = ['channelId'=>self::CHANNEL_ID,'seen'=>1,'token'=>$token];
        $res = Https::getPost($arrData,$url);
    }


    /**
     * 添加数据
     * @param $val
     * @return bool
     */
    private function addMovie($val){
        $movie = new Movie();
        $movie->id = $val['A'];
        $movie->movieName = $val['B'];
        $movie->scoreFillNum0 = $val['C'];
        $movie->scoreFillNum20 = $val['D'];
        $movie->scoreFillNum40 = $val['E'];
        $movie->scoreFillNum60 = $val['F'];
        $movie->scoreFillNum80 = $val['G'];
        $movie->scoreFillNum100 = $val['H'];
        $movie->score = $val['I']*10;
        $movie->baseScoreCount = $val['C'] +$val['D']+$val['E']+$val['F']+$val['G']+$val['H'];
        $movie->scoreCount = 0;
        $movie->commentCount=0;
        $movie->baseWantCount = 0;
        $movie->wantCount = 0;
        $movie->seenCount;
        $movie->created = $movie->updated = time();
        return $movie->save();
    }

    /**
     * 读取数据
     * @param string $filePath
     * @param int $sheet
     * @return array|void
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    private function format_excel2array($filePath='',$sheet=0){
        //读取excel
        spl_autoload_unregister(array('YiiBase','autoload'));
        Yii::import('ext.PHPExcel.PHPExcel', true);
        spl_autoload_register(array('YiiBase','autoload'));

        if(empty($filePath) or !file_exists($filePath)){die('file not exists');}
        $PHPReader = new PHPExcel_Reader_Excel2007();        //建立reader对象
        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($filePath)){
                echo 'no Excel';
                return ;
            }
        }
        $PHPExcel = $PHPReader->load($filePath);        //建立excel对象
        $currentSheet = $PHPExcel->getSheet($sheet);        //**读取excel文件中的指定工作表*/
        $allColumn = $currentSheet->getHighestColumn();        //**取得最大的列号*/
        $allRow = $currentSheet->getHighestRow();        //**取得一共有多少行*/
        $data = array();
        for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
                $addr = $colIndex.$rowIndex;
                $cell = $currentSheet->getCell($addr)->getValue();
                if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                    $cell = $cell->__toString();
                }
                $data[$rowIndex][$colIndex] = $cell;
            }
        }
        return $data;
    }


}
