<?php

/**
 * @tutorial 二维码
 */
class QrCodeBase
{
    /**
     * 解析图片二维码内容
     * @param $imgPatg
     * @return bool
     */
    public static  function getInfo($imgPatg)
    {
        include_once(Yii::app()->params['base_url'].'/protected/vendor/php-qr-decoder/lib/QrReader.php');
        $qrCode = new QrReader($imgPatg);
        $text = $qrCode->text();
        return $text;
    }

    /**
     * 生成二维码，包含水印等
     * @param $str
     * @param $imgPath
     * @param string $logo
     * @param string $errorCorrectionLevel
     * @param int $matrixPointSize
     */
    public static function setInfo($str,$imgPath,$logo='',$errorCorrectionLevel='H',$matrixPointSize = 6)
    {
        include_once(Yii::app()->params['base_url'].'/protected/vendor/phpqrcode/phpqrcode.php');
        QRcode::png($str,$imgPath,$errorCorrectionLevel,$matrixPointSize,2);
        $QR = $imgPath;//已经生成的原始二维码图

        if (!empty($logo)) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
            imagepng($QR, $imgPath);
        }
    }
}
