<?php
/**
 * Author: xupp
 * Date: 2016/12/26
 * Time: 9:29
 */

namespace wechat\build;

use wechat\Wx;

class Jssdk extends Wx{

    public function getSignPackage($url = '') {
        $jsapiTicket = $this->getJsApiTicket();
        $url = $url ? $url : "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => self::$config['appId'],
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket
        $url = $this->apiUrl."/cgi-bin/ticket/getticket?type=jsapi&access_token=".$this->getAccessToken();
        $data = $this->curl($url);
        if($data['errcode'] !== 0){
            return false;
        }
        return $data['ticket'];
    }
}