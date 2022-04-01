<?php
/*
 * @Author: Brightness
 * @Date: 2022-04-01 16:46:58
 * @LastEditors: Brightness
 * @LastEditTime: 2022-04-01 16:46:59
 * @Description:  QRcode
*/
require_once dirname(__FILE__) . '/phpqrcode/qrlib.php';

class PHPQrcode_Lib{
    public function png($text, $outfile = false, $level = 'L', $size = 3, $margin = 4, $saveandprint = false) {
        return QRcode::png($text, $outfile, $level, $size, $margin, $saveandprint);
    } 
}