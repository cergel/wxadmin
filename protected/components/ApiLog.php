<?php
include 'protected/vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * api记录日志
 */
class ApiLog
{
    public static function addLog($api_name, $request, $response, $request_type = 'post')
    {
        try {
            $log_path = '/data/logs/wxadmin/api/' . date('Y-m-d');
            if (!is_dir($log_path)) {
                @mkdir($log_path, 0777, true);
            }
            $logger = new Logger('api_logger');
            $stream = new StreamHandler($log_path . '/' . Yii::app()->controller->id . '.log', Logger::DEBUG);
            //$stream->setFormatter(new \Monolog\Formatter\JsonFormatter());
            $logger->pushHandler($stream);
            $logger->addInfo($api_name, array('request' => $request, 'response' => $response, 'request_type' => $request_type));
        } catch (Exception $exception) {
            
        }
    }
}