<?php

/**
 *
 */
class LogFileInfo
{

// // so better to use this
    public static function logInfo( $path,$info )
    {
        if(empty($path))return false;
        $path .= "/".date('Ymd');
        UploadFiles::createPath($path);
        $path .= '/'.date('H').'.log';
        $res = false;
        $info = date('Y-m-d H:i:s').'  '.$info;
        $info .= "\n\r";
        if(file_put_contents($path, $info,FILE_APPEND))
            $res = true;
    return $res;
    }


    
}
